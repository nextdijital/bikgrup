<?php
/**
 * BİKGRUP - Güvenli E-Posta Gönderim Motoru (SMTP & PHP Mail Fallback)
 * PHP 8.2+ / OpenSSL Uyumlu
 */

declare(strict_types=1);

require_once ROOT_PATH . '/core/Settings.php';

class Mailer
{
    /**
     * E-posta gönderir
     * 
     * @param string|array $to Alıcı e-posta adresi veya adresleri (virgülle ayrılmış veya dizi)
     * @param string $subject Konu başlığı
     * @param string $htmlBody HTML içerik
     * @param string|null $replyTo Yanıt adresi
     * @return array ['success' => bool, 'message' => string]
     */
    public static function send(string|array $to, string $subject, string $htmlBody, ?string $replyTo = null): array
    {
        // Alıcıları listeye dönüştür
        $recipients = [];
        if (is_array($to)) {
            $recipients = $to;
        } else {
            $parts = explode(',', str_replace(';', ',', $to));
            foreach ($parts as $p) {
                $trimmed = trim($p);
                if (filter_var($trimmed, FILTER_VALIDATE_EMAIL)) {
                    $recipients[] = $trimmed;
                }
            }
        }

        if (empty($recipients)) {
            return ['success' => false, 'message' => 'Geçerli bir alıcı e-posta adresi bulunamadı.'];
        }

        // SMTP ayarlarını çek
        $smtpHost   = trim(Settings::get('smtp_host', ''));
        $smtpPort   = (int)Settings::get('smtp_port', '587');
        $smtpUser   = trim(Settings::get('smtp_user', ''));
        $smtpPass   = trim(Settings::get('smtp_pass', ''));
        $smtpSecure = trim(Settings::get('smtp_secure', 'tls')); // tls, ssl veya none
        $fromEmail  = !empty($smtpUser) ? $smtpUser : Settings::get('site_email', 'no-reply@bikgrup.com');
        $fromName   = Settings::get('site_name', 'BİK GRUP');

        $result = ['success' => false, 'message' => ''];

        // Eğer SMTP bilgileri tanımlanmışsa SMTP ile gönder
        if (!empty($smtpHost) && !empty($smtpUser) && !empty($smtpPass)) {
            $result = self::sendViaSmtp($smtpHost, $smtpPort, $smtpUser, $smtpPass, $smtpSecure, $fromEmail, $fromName, $recipients, $subject, $htmlBody, $replyTo);
        }

        // SMTP başarısız olursa veya yapılandırılmamışsa PHP mail() ile dene
        if (!$result['success']) {
            $mailResult = self::sendViaPhpMail($fromEmail, $fromName, $recipients, $subject, $htmlBody, $replyTo);
            if ($mailResult['success']) {
                $result = $mailResult;
            } elseif (empty($result['message'])) {
                $result = $mailResult;
            }
        }

        // Gönderim kaydını logla
        self::logEmail($recipients, $subject, $htmlBody, $result['success'], $result['message']);

        return $result;
    }

    /**
     * Saf PHP Stream Sockets ile SMTP Üzerinden Gönderim (Harici kütüphane gerektirmez)
     */
    private static function sendViaSmtp(
        string $host,
        int $port,
        string $user,
        string $pass,
        string $secure,
        string $fromEmail,
        string $fromName,
        array $recipients,
        string $subject,
        string $htmlBody,
        ?string $replyTo
    ): array {
        $timeout = 15;
        $protocol = ($secure === 'ssl') ? 'ssl://' : 'tcp://';
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);
        $socket = @stream_socket_client($protocol . $host . ':' . $port, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT, $context);

        if (!$socket) {
            return ['success' => false, 'message' => "SMTP sunucusuna bağlanılamadı: {$errstr} ({$errno})"];
        }

        stream_set_timeout($socket, $timeout);

        $readResponse = function() use ($socket) {
            $response = '';
            while ($line = fgets($socket, 515)) {
                $response .= $line;
                if (substr($line, 3, 1) === ' ') {
                    break;
                }
            }
            return $response;
        };

        $sendCommand = function(string $cmd) use ($socket, $readResponse) {
            fputs($socket, $cmd . "\r\n");
            return $readResponse();
        };

        // Karşılama
        $res = $readResponse();
        if (substr($res, 0, 3) !== '220') {
            fclose($socket);
            return ['success' => false, 'message' => "SMTP Bağlantı hatası: {$res}"];
        }

        // EHLO
        $res = $sendCommand("EHLO localhost");

        // STARTTLS gerekiyorsa
        if ($secure === 'tls' && str_contains($res, 'STARTTLS')) {
            $res = $sendCommand("STARTTLS");
            if (substr($res, 0, 3) === '220') {
                $crypto = @stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                if (!$crypto) {
                    fclose($socket);
                    return ['success' => false, 'message' => "TLS şifreleme başlatılamadı."];
                }
                $res = $sendCommand("EHLO localhost");
            }
        }

        // AUTH LOGIN
        $res = $sendCommand("AUTH LOGIN");
        if (substr($res, 0, 3) !== '334') {
            fclose($socket);
            return ['success' => false, 'message' => "SMTP Kimlik doğrulama reddedildi: {$res}"];
        }

        $res = $sendCommand(base64_encode($user));
        if (substr($res, 0, 3) !== '334') {
            fclose($socket);
            return ['success' => false, 'message' => "SMTP Kullanıcı adı kabul edilmedi: {$res}"];
        }

        $res = $sendCommand(base64_encode($pass));
        if (substr($res, 0, 3) !== '235') {
            fclose($socket);
            return ['success' => false, 'message' => "SMTP Şifre hatalı veya giriş başarısız: {$res}"];
        }

        // MAIL FROM
        $res = $sendCommand("MAIL FROM: <{$fromEmail}>");
        if (substr($res, 0, 3) !== '250') {
            fclose($socket);
            return ['success' => false, 'message' => "MAIL FROM hatası: {$res}"];
        }

        // RCPT TO (Tüm alıcılar için)
        foreach ($recipients as $rcpt) {
            $res = $sendCommand("RCPT TO: <{$rcpt}>");
            if (substr($res, 0, 3) !== '250' && substr($res, 0, 3) !== '251') {
                fclose($socket);
                return ['success' => false, 'message' => "Alıcı reddedildi ({$rcpt}): {$res}"];
            }
        }

        // DATA
        $res = $sendCommand("DATA");
        if (substr($res, 0, 3) !== '354') {
            fclose($socket);
            return ['success' => false, 'message' => "DATA komutu reddedildi: {$res}"];
        }

        // E-Posta Başlıkları ve İçeriği
        $encodedSubject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
        $encodedFromName = "=?UTF-8?B?" . base64_encode($fromName) . "?=";
        $msgDate = date('r');

        $emailHeaders = "Date: {$msgDate}\r\n";
        $emailHeaders .= "From: {$encodedFromName} <{$fromEmail}>\r\n";
        $emailHeaders .= "To: " . implode(', ', $recipients) . "\r\n";
        if (!empty($replyTo)) {
            $emailHeaders .= "Reply-To: {$replyTo}\r\n";
        }
        $emailHeaders .= "Subject: {$encodedSubject}\r\n";
        $emailHeaders .= "MIME-Version: 1.0\r\n";
        $emailHeaders .= "Content-Type: text/html; charset=UTF-8\r\n";
        $emailHeaders .= "Content-Transfer-Encoding: 8bit\r\n";
        $emailHeaders .= "X-Mailer: BIKGRUP Mailer\r\n\r\n";

        $fullPayload = $emailHeaders . $htmlBody . "\r\n.";
        $res = $sendCommand($fullPayload);

        $sendCommand("QUIT");
        fclose($socket);

        if (substr($res, 0, 3) === '250') {
            return ['success' => true, 'message' => 'E-posta SMTP ile başarıyla iletildi.'];
        }

        return ['success' => false, 'message' => "E-posta gönderim yanıtı: {$res}"];
    }

    /**
     * PHP mail() Fallback Fonksiyonu
     */
    private static function sendViaPhpMail(
        string $fromEmail,
        string $fromName,
        array $recipients,
        string $subject,
        string $htmlBody,
        ?string $replyTo
    ): array {
        $encodedSubject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
        $encodedFromName = "=?UTF-8?B?" . base64_encode($fromName) . "?=";

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: {$encodedFromName} <{$fromEmail}>\r\n";
        if (!empty($replyTo)) {
            $headers .= "Reply-To: {$replyTo}\r\n";
        }
        $headers .= "X-Mailer: PHP/" . phpversion();

        $toHeader = implode(', ', $recipients);
        $ok = @mail($toHeader, $encodedSubject, $htmlBody, $headers);

        if ($ok) {
            return ['success' => true, 'message' => 'E-posta sunucu mail() fonksiyonu ile iletildi.'];
        }

        return ['success' => false, 'message' => 'PHP mail() fonksiyonu yerel sunucuda iletim sağlayamadı. SMTP ayarlarını girmeniz önerilir.'];
    }

    /**
     * E-posta Loglama (Her gelen teklif ve test e-postası burada kaydedilir)
     */
    private static function logEmail(array $recipients, string $subject, string $htmlBody, bool $success, string $statusMsg): void
    {
        try {
            $logDir = ROOT_PATH . '/logs';
            if (!is_dir($logDir)) {
                @mkdir($logDir, 0777, true);
            }

            $logFile = $logDir . '/mail_log.json';
            $existing = [];
            if (file_exists($logFile)) {
                $content = @file_get_contents($logFile);
                if ($content) {
                    $existing = json_decode($content, true) ?: [];
                }
            }

            array_unshift($existing, [
                'time'       => date('d.m.Y H:i:s'),
                'recipients' => $recipients,
                'subject'    => $subject,
                'status'     => $success ? 'DELIVERED' : 'LOGGED',
                'message'    => $statusMsg,
                'preview'    => strip_tags(mb_substr($htmlBody, 0, 300)) . '...'
            ]);

            // Son 50 mail kaydını sakla
            $existing = array_slice($existing, 0, 50);
            @file_put_contents($logFile, json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        } catch (\Throwable $e) {
            // sessiz devam et
        }
    }
}

