<?php
/**
 * BİKGRUP - İletişim Kontrolcüsü
 */

declare(strict_types=1);

require_once ROOT_PATH . '/core/View.php';
require_once ROOT_PATH . '/core/Security.php';
require_once ROOT_PATH . '/app/Models/Message.php';

class ContactController
{
    public function index(): void
    {
        $status = $_GET['status'] ?? null;

        View::render('contact', [
            'pageTitle' => __('nav_contact') . ' | ' . Settings::get('site_name', 'BİKGRUP'),
            'status'    => $status,
            'activeNav' => 'contact'
        ]);
    }

    public function submit(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/contact");
            exit;
        }

        // CSRF Doğrulaması
        if (!Security::validateCsrf($_POST['csrf_token'] ?? null)) {
            header("Location: " . BASE_URL . "/contact?status=csrf_error");
            exit;
        }

        // Rate Limit (Dakikada en fazla 3 form gönderimi)
        if (!Security::checkRateLimit('contact_form', 3, 60)) {
            header("Location: " . BASE_URL . "/contact?status=rate_limited");
            exit;
        }

        $name = Security::clean($_POST['name'] ?? '');
        $email = Security::clean($_POST['email'] ?? '');
        $phone = Security::clean($_POST['phone'] ?? '');
        $subject = Security::clean($_POST['subject'] ?? '');
        $message = Security::clean($_POST['message'] ?? '');

        if (empty($name) || empty($email) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Security::recordFailedAttempt('contact_form');
            header("Location: " . BASE_URL . "/contact?status=invalid");
            exit;
        }

        // Mesajı kaydet
        Message::create([
            'name'    => $name,
            'email'   => $email,
            'phone'   => $phone,
            'subject' => $subject ?: 'Genel Bilgi ve Sipariş Talebi',
            'message' => $message
        ]);

        // KULLANICI TALEBİ: Gelen Mesajlar kaldırıldığı için tüm iletişim talepleri doğrudan Hızlı Teklifler / Talepler tablosuna da eklenir
        require_once ROOT_PATH . '/app/Models/QuoteRequest.php';
        require_once ROOT_PATH . '/core/Mailer.php';
        $quoteId = QuoteRequest::create([
            'product_category' => 'İletişim Talebi: ' . ($subject ?: 'Genel Bilgi'),
            'quantity'         => '1 İletişim Formu',
            'full_name'        => $name,
            'phone'            => $phone,
            'email'            => $email,
            'notes'            => $message
        ]);

        // E-Posta Bildirimi Gönder
        $ownerEmail = Settings::get('quote_notification_email');
        if (empty($ownerEmail) || !filter_var($ownerEmail, FILTER_VALIDATE_EMAIL)) {
            $ownerEmail = Settings::get('site_email', 'destek@bikgrup.com');
        }
        $subMail = "✉️ Yeni İletişim Mesajı (#{$quoteId}): {$name} - " . ($subject ?: 'İletişim Formu');
        $panelUrl = BASE_URL . "/" . PANEL_SLUG . "/quotes";
        $bodyMail = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 14px; background: #ffffff;'>
            <div style='background: #15803d; padding: 18px 24px; border-radius: 10px; color: #ffffff; text-align: center;'>
                <h2 style='margin: 0; font-size: 20px;'>BİK GRUP TARIM</h2>
                <p style='margin: 4px 0 0; font-size: 13px; color: #dcfce7;'>Web Sitesi İletişim Formu Mesajı</p>
            </div>
            <div style='padding: 20px 0;'>
                <p><strong>Gönderen:</strong> {$name}</p>
                <p><strong>Telefon:</strong> <a href='tel:{$phone}'>{$phone}</a></p>
                <p><strong>E-Posta:</strong> <a href='mailto:{$email}'>{$email}</a></p>
                <p><strong>Konu:</strong> {$subject}</p>
                <div style='background: #f8fafc; border: 1px solid #e2e8f0; padding: 16px; border-radius: 10px; margin: 16px 0;'>
                    <strong>Mesaj:</strong><br>" . nl2br(htmlspecialchars($message)) . "
                </div>
                <div style='text-align: center; margin-top: 20px;'>
                    <a href='{$panelUrl}' style='background: #15803d; color: #ffffff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block;'>⚡ Panele Git & Görüntüle</a>
                </div>
            </div>
        </div>";
        Mailer::send($ownerEmail, $subMail, $bodyMail, $email);

        header("Location: " . BASE_URL . "/contact?status=success");
        exit;
    }
}
