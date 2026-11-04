<?php
/**
 * BİKGRUP - Canlı Veritabanı ve Galeri/Ürün Senkronizasyon Aracı
 * Bu dosya canlı sunucuya atıldığında tarayıcıdan bir kez çalıştırılarak
 * galeri tablosunu ve ana ürün kartlarını anında senkronize eder.
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$key = $_GET['key'] ?? '';
if ($key !== 'bikgrup2026') {
    http_response_code(403);
    die('<div style="font-family:sans-serif;padding:40px;text-align:center;"><h2>🔒 Erişim Yetkisi Yok</h2><p>Güvenlik anahtarı geçersiz veya eksik.</p></div>');
}

$db = Database::getConnection();
$log = [];

// 1. Galeri Tablosu Kontrolü ve Yüklenmesi
$stmt = $db->query("SHOW TABLES LIKE 'gallery_items'");
if ($stmt->rowCount() === 0) {
    $schemaFile = __DIR__ . '/config/schema.sql';
    if (file_exists($schemaFile)) {
        $sql = file_get_contents($schemaFile);
        $db->exec($sql);
        $log[] = "✅ gallery_items tablosu ve 41 adet fotoğraf/video başarıyla oluşturuldu.";
    }
} else {
    $count = (int)$db->query("SELECT COUNT(*) FROM gallery_items")->fetchColumn();
    $log[] = "ℹ️ gallery_items tablosu zaten mevcut (Toplam kayıt: {$count}).";
}

// 2. Ürün Kartları İsimlerinin Ana Ürün Adlarına Güncellenmesi
$db->exec("UPDATE products SET name_tr = 'Portakal', name_en = 'Orange' WHERE slug = 'washington-portakal'");
$db->exec("UPDATE products SET name_tr = 'Limon', name_en = 'Lemon' WHERE slug = 'mayer-limon'");
$db->exec("UPDATE products SET name_tr = 'Greyfurt', name_en = 'Grapefruit' WHERE slug = 'star-ruby-greyfurt'");
$db->exec("UPDATE products SET name_tr = 'Mandalina', name_en = 'Mandarin' WHERE slug = 'mandalina'");
$db->exec("DELETE FROM products WHERE slug = 'white-marsh-seedless' OR name_tr LIKE '%Test%'");
$log[] = "✅ Ürün kartları güncellendi: Cins isimleri kaldırıldı, ana ürün isimleri (Portakal, Limon, Greyfurt, Mandalina) tanımlandı.";
$log[] = "✅ Fazla test kayıtları ve 'White Marsh' kartı temizlendi.";

// 3. Hububat Ürünleri Kontrolü
$grainCount = (int)$db->query("SELECT COUNT(*) FROM products WHERE category_id = 4")->fetchColumn();
if ($grainCount < 4) {
    $schemaFile = __DIR__ . '/config/schema.sql';
    if (file_exists($schemaFile)) {
        $sql = file_get_contents($schemaFile);
        $db->exec($sql);
        $log[] = "✅ Hububat ürünleri (Buğday, Mısır, Ayçekirdeği, Soya) veritabanına eklendi.";
    }
} else {
    $log[] = "ℹ️ Hububat ürünleri aktif (Toplam: {$grainCount}).";
}

// 4. Quote Requests Tablosu
$stmtQ = $db->query("SHOW TABLES LIKE 'quote_requests'");
if ($stmtQ->rowCount() === 0) {
    $db->exec("CREATE TABLE IF NOT EXISTS `quote_requests` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `product_category` varchar(255) NOT NULL,
      `quantity` varchar(255) NOT NULL,
      `full_name` varchar(255) NOT NULL,
      `phone` varchar(100) NOT NULL,
      `email` varchar(255) NOT NULL,
      `notes` text DEFAULT NULL,
      `status` varchar(50) DEFAULT 'pending',
      `admin_notes` text DEFAULT NULL,
      `quote_amount` varchar(100) DEFAULT NULL,
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    $log[] = "✅ quote_requests tablosu oluşturuldu.";
}

// 5. Hostinger SMTP Ayarları
$db->exec("INSERT INTO settings (setting_key, setting_value, group_name) VALUES 
    ('smtp_host', 'smtp.hostinger.com', 'mail'),
    ('smtp_port', '465', 'mail'),
    ('smtp_secure', 'ssl', 'mail'),
    ('smtp_user', 'destek@bikgrup.com', 'mail')
    ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
$log[] = "✅ Hostinger SMTP ayarları (smtp.hostinger.com:465 SSL) tanımlandı.";
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>BİK GRUP | Canlı Veritabanı Senkronizasyonu</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f8fafc; color: #0f172a; padding: 40px 20px; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); padding: 36px; border: 1px solid #e2e8f0; }
        h1 { font-size: 24px; color: #15803d; margin-top: 0; }
        ul { list-style: none; padding: 0; margin: 20px 0; }
        li { padding: 10px 14px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; margin-bottom: 8px; font-size: 14.5px; }
        .btn { display: inline-block; background: #15803d; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 10px; font-weight: 700; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="card">
        <h1>🌿 BİK GRUP Canlı Senkronizasyon Tamamlandı</h1>
        <p>Aşağıdaki tüm güncellemeler ve veritabanı tabloları başarıyla işlendi:</p>
        <ul>
            <?php foreach ($log as $l): ?>
                <li><?= htmlspecialchars($l) ?></li>
            <?php endforeach; ?>
        </ul>
        <div style="text-align: center; margin-top: 25px;">
            <a href="<?= BASE_URL ?>/galeri" class="btn">🖼️ Galeri Sayfasını Aç</a>
            <a href="<?= BASE_URL ?>/urunler" class="btn" style="background: #ea580c; margin-left: 10px;">🍊 Ürünleri İncele</a>
        </div>
    </div>
</body>
</html>

