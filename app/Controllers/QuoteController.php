<?php
/**
 * BİKGRUP - Teklif Talep Kontrolcüsü (Frontend)
 */

declare(strict_types=1);

require_once ROOT_PATH . '/core/Security.php';
require_once ROOT_PATH . '/core/Settings.php';
require_once ROOT_PATH . '/core/Mailer.php';
require_once ROOT_PATH . '/app/Models/QuoteRequest.php';

class QuoteController
{
    public function submit(): void
    {
        // Yalnızca POST isteklerine izin ver
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Geçersiz istek türü.'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Rate Limit (Dakikada en fazla 5 teklif talebi)
        if (!Security::checkRateLimit('quote_form', 5, 60)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Lütfen tekrar denemeden önce 1 dakika bekleyin.'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Form alanlarını al ve temizle
        $category = Security::clean($_POST['product_category'] ?? '');
        $quantity = Security::clean($_POST['quantity'] ?? '');
        $fullName = Security::clean($_POST['full_name'] ?? '');
        $phone    = Security::clean($_POST['phone'] ?? '');
        $email    = Security::clean($_POST['email'] ?? '');
        $notes    = Security::clean($_POST['notes'] ?? '');

        // Doğrulama (E-posta opsiyonel, girildiyse geçerli formatta olmalı)
        if (empty($category) || empty($quantity) || empty($fullName) || empty($phone)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Lütfen tüm zorunlu alanları eksiksiz doldurunuz.'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Lütfen geçerli bir e-posta adresi giriniz veya boş bırakınız.'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Veritabanına kaydet
        try {
            $quoteId = QuoteRequest::create([
                'product_category' => $category,
                'quantity'         => $quantity,
                'full_name'        => $fullName,
                'phone'            => $phone,
                'email'            => $email,
                'notes'            => $notes
            ]);

            // Firma yetkilisine e-posta bildirimi gönder (Kullanıcının belirlediği mail)
            $ownerEmail = Settings::get('quote_notification_email');
            if (empty($ownerEmail) || !filter_var($ownerEmail, FILTER_VALIDATE_EMAIL)) {
                $ownerEmail = Settings::get('site_email', 'destek@bikgrup.com');
            }

            $subject = "⚡ Yeni Teklif Talebi (#{$quoteId}): {$category} - {$fullName}";
            $panelUrl = BASE_URL . "/" . PANEL_SLUG . "/quotes";
            $siteLogo = Settings::get('site_logo') ? BASE_URL . '/public/images/' . Settings::get('site_logo') : '';
            $dateNow = date('d.m.Y H:i');

            $body = "
            <!DOCTYPE html>
            <html lang='tr'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            </head>
            <body style='margin: 0; padding: 24px 12px; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; background-color: #f1f5f9; color: #1e293b;'>
                <div style='max-width: 620px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;'>
                    <!-- Header -->
                    <div style='background: linear-gradient(135deg, #15803d 0%, #047857 100%); padding: 26px 30px; text-align: center;'>
                        <h1 style='color: #ffffff; font-size: 22px; margin: 0; font-weight: 800; letter-spacing: -0.3px;'>BİK GRUP TARIM</h1>
                        <p style='color: #dcfce7; font-size: 13px; margin: 6px 0 0; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;'>⚡ Yeni Teklif Talebi Bildirimi</p>
                    </div>

                    <!-- İçerik -->
                    <div style='padding: 28px 30px;'>
                        <div style='display: flex; align-items: center; justify-content: space-between; border-bottom: 2px dashed #e2e8f0; padding-bottom: 16px; margin-bottom: 20px;'>
                            <div>
                                <span style='font-size: 13px; color: #64748b;'>Talep Numarası</span>
                                <div style='font-size: 20px; font-weight: 800; color: #ea580c;'>#{$quoteId}</div>
                            </div>
                            <div style='text-align: right;'>
                                <span style='font-size: 12px; color: #64748b;'>Tarih & Saat</span>
                                <div style='font-size: 14px; font-weight: 600; color: #334155;'>{$dateNow}</div>
                            </div>
                        </div>

                        <p style='font-size: 15px; color: #334155; margin: 0 0 18px 0; line-height: 1.5;'>
                            Web sitenizden yeni bir <strong>hızlı teklif talebi</strong> alındı. Müşteriye ait iletişim bilgileri ve talep detayları aşağıdadır:
                        </p>

                        <!-- Detay Kartı -->
                        <table style='width: 100%; border-collapse: separate; border-spacing: 0; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; margin-bottom: 24px; font-size: 14px;'>
                            <tr>
                                <td style='padding: 12px 16px; font-weight: 700; color: #475569; width: 38%; border-bottom: 1px solid #e2e8f0;'>Ürün / Kategori:</td>
                                <td style='padding: 12px 16px; color: #0f172a; font-weight: 700; border-bottom: 1px solid #e2e8f0;'>{$category}</td>
                            </tr>
                            <tr>
                                <td style='padding: 12px 16px; font-weight: 700; color: #475569; border-bottom: 1px solid #e2e8f0;'>Talep Edilen Miktar:</td>
                                <td style='padding: 12px 16px; color: #ea580c; font-weight: 800; border-bottom: 1px solid #e2e8f0;'>{$quantity}</td>
                            </tr>
                            <tr>
                                <td style='padding: 12px 16px; font-weight: 700; color: #475569; border-bottom: 1px solid #e2e8f0;'>Müşteri / Yetkili:</td>
                                <td style='padding: 12px 16px; color: #0f172a; font-weight: 600; border-bottom: 1px solid #e2e8f0;'>{$fullName}</td>
                            </tr>
                            <tr>
                                <td style='padding: 12px 16px; font-weight: 700; color: #475569; border-bottom: 1px solid #e2e8f0;'>Telefon Numarası:</td>
                                <td style='padding: 12px 16px; border-bottom: 1px solid #e2e8f0;'>
                                    <a href='tel:{$phone}' style='color: #15803d; font-weight: 700; text-decoration: none;'>📞 {$phone}</a>
                                </td>
                            </tr>
                            <tr>
                                <td style='padding: 12px 16px; font-weight: 700; color: #475569; border-bottom: 1px solid #e2e8f0;'>E-Posta Adresi:</td>
                                <td style='padding: 12px 16px; border-bottom: 1px solid #e2e8f0;'>
                                    " . (!empty($email) ? "<a href='mailto:{$email}' style='color: #2563eb; text-decoration: none;'>✉️ {$email}</a>" : "<span style='color: #94a3b8;'>Belirtilmedi</span>") . "
                                </td>
                            </tr>
                            <tr>
                                <td style='padding: 12px 16px; font-weight: 700; color: #475569; vertical-align: top;'>Müşteri Notu:</td>
                                <td style='padding: 12px 16px; color: #334155; line-height: 1.5;'>" . (!empty($notes) ? nl2br(htmlspecialchars($notes)) : "<span style='color: #94a3b8;'>Ek not girilmedi</span>") . "</td>
                            </tr>
                        </table>

                        <!-- Aksiyon Butonları -->
                        <div style='text-align: center; margin: 26px 0 14px;'>
                            <a href='{$panelUrl}' style='display: inline-block; background: #15803d; color: #ffffff; text-decoration: none; font-weight: 700; font-size: 14.5px; padding: 13px 28px; border-radius: 10px; box-shadow: 0 4px 12px rgba(21, 128, 61, 0.25);'>
                                ⚡ Panele Git & Teklifi Yanıtla
                            </a>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div style='background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 16px 30px; text-align: center; font-size: 12px; color: #94a3b8;'>
                        Bu e-posta, <strong>BİK GRUP</strong> kurumsal web siteniz üzerinden gönderilen otomatik bir sistem bildirimidir.
                    </div>
                </div>
            </body>
            </html>
            ";

            // Mailer motoru üzerinden gönderim yap
            Mailer::send($ownerEmail, $subject, $body, !empty($email) ? $email : null);

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => true,
                'message' => 'Teklif talebiniz başarıyla alındı! İhracat ve satış uzmanlarımız en kısa sürede sizinle iletişime geçecektir.'
            ], JSON_UNESCAPED_UNICODE);
            exit;

        } catch (Throwable $e) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'message' => 'Talep kaydedilirken bir hata oluştu. Lütfen doğrudan telefon veya WhatsApp ile iletişime geçiniz.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}

