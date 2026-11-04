<?php
/**
 * BİKGRUP - Güvenlik ve Doğrulama Katmanı
 * PHP 8.2 / 8.3 / 8.4 Uyumlu
 */

declare(strict_types=1);

class Security
{
    /**
     * CSRF Token oluşturur veya mevcut olanı döner
     */
    public static function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * CSRF Token gizli input HTML alanı
     */
    public static function csrfField(): string
    {
        $token = self::csrfToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    /**
     * CSRF Token doğrulaması yapar
     */
    public static function validateCsrf(?string $token): bool
    {
        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * XSS Korumalı Çıktı Kaçırma
     */
    public static function escape(?string $str): string
    {
        if ($str === null) {
            return '';
        }
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Dizi veya metin girdilerini güvenli hale getirir
     * @param mixed $data
     * @return mixed
     */
    public static function clean($data)
    {
        if (is_array($data)) {
            $cleaned = [];
            foreach ($data as $key => $val) {
                $cleaned[self::clean($key)] = self::clean($val);
            }
            return $cleaned;
        }
        if (is_string($data)) {
            return trim(strip_tags($data));
        }
        return $data;
    }

    /**
     * Güvenli Parola Hashleme (Modern Bcrypt)
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);
    }

    /**
     * Parola Doğrulama
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Brute-force engelleme: Hatalı giriş sayacı
     */
    public static function checkRateLimit(string $actionKey, int $maxAttempts = 5, int $lockoutSeconds = 300): bool
    {
        $sessionKey = 'rate_limit_' . $actionKey;
        $now = time();

        if (isset($_SESSION[$sessionKey])) {
            $data = $_SESSION[$sessionKey];
            if ($data['lockout_until'] > $now) {
                return false; // Hâlâ kilitli
            }
            if ($data['attempts'] >= $maxAttempts) {
                $_SESSION[$sessionKey]['lockout_until'] = $now + $lockoutSeconds;
                return false;
            }
        }
        return true;
    }

    /**
     * Başarısız deneme kaydet
     */
    public static function recordFailedAttempt(string $actionKey): void
    {
        $sessionKey = 'rate_limit_' . $actionKey;
        $now = time();

        if (!isset($_SESSION[$sessionKey]) || $_SESSION[$sessionKey]['lockout_until'] < $now) {
            $_SESSION[$sessionKey] = [
                'attempts' => 1,
                'first_attempt' => $now,
                'lockout_until' => 0
            ];
        } else {
            $_SESSION[$sessionKey]['attempts']++;
        }
    }

    /**
     * Başarılı işlemde sayacı sıfırla
     */
    public static function resetRateLimit(string $actionKey): void
    {
        $sessionKey = 'rate_limit_' . $actionKey;
        unset($_SESSION[$sessionKey]);
    }
}
