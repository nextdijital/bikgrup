<?php
/**
 * BİKGRUP - Veritabanı Yönetici Sınıfı
 * PHP 8.2 / 8.3 / 8.4 Uyumlu PDO Singleton
 */

declare(strict_types=1);

require_once __DIR__ . '/config.php';

class Database
{
    private static ?PDO $instance = null;

    /**
     * Singleton PDO Bağlantısı
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            try {
                // İlk olarak veritabanı var mı kontrol et, yoksa oluştur
                self::ensureDatabaseExists();

                $dsn = sprintf(
                    'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                    DB_HOST,
                    DB_PORT,
                    DB_NAME,
                    DB_CHARSET
                );

                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
                ];

                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);

                // Tablolar oluşturulmuş mu kontrol et
                self::ensureTablesExist();

            } catch (PDOException $e) {
                // Hata durumunda güvenli ve bilgilendirici mesaj
                if (defined('APP_ENV') && APP_ENV === 'production') {
                    error_log('BİKGRUP Veritabanı Bağlantı Hatası: ' . $e->getMessage());
                    http_response_code(503);
                    die("<div style='font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,sans-serif;padding:40px 30px;background:#ffffff;color:#1e293b;border:1px solid #e2e8f0;border-radius:18px;max-width:520px;margin:80px auto;text-align:center;box-shadow:0 20px 40px rgba(0,0,0,0.06);'>
                        <div style='font-size:36px;margin-bottom:12px;'>🌿</div>
                        <h2 style='margin:0 0 10px 0;font-size:22px;color:#0f172a;'>BİK GRUP</h2>
                        <p style='color:#64748b;font-size:15px;line-height:1.6;margin:0 0 20px 0;'>Sistemimizde kısa süreli bir bakım çalışması yürütülmektedir. Lütfen birkaç dakika sonra tekrar deneyiniz.</p>
                        <small style='color:#94a3b8;font-size:12px;'>Hizmet Kesintisi Kodu: DB_503</small>
                    </div>");
                }
                die("<div style='font-family:sans-serif;padding:30px;background:#fff1f2;color:#9f1239;border:1px solid #fecdd3;border-radius:12px;max-width:600px;margin:50px auto;box-shadow:0 10px 25px rgba(0,0,0,0.05);'>
                    <h3 style='margin-top:0;'>⚠️ Veritabanı Bağlantı Hatası</h3>
                    <p>MySQL sunucusuna bağlanırken bir sorun oluştu:</p>
                    <code style='background:#ffe4e6;padding:4px 8px;border-radius:4px;display:block;margin:10px 0;'>" . htmlspecialchars($e->getMessage()) . "</code>
                    <small>Lütfen MySQL servisinin veya veritabanı kullanıcı bilgilerinin (config.php / .env) doğruluğunu kontrol edin.</small>
                </div>");
            }
        }

        return self::$instance;
    }

    /**
     * Veritabanının var olduğunu garanti eder, yoksa oluşturur
     */
    private static function ensureDatabaseExists(): void
    {
        // Canlı sunucularda (cPanel, Plesk vb.) CREATE DATABASE yetkisi sınırlandırılmış olabilir
        try {
            $pdo = new PDO(
                sprintf('mysql:host=%s;port=%s', DB_HOST, DB_PORT),
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT]
            );
            $pdo->exec(sprintf(
                "CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci",
                DB_NAME
            ));
        } catch (\Throwable $e) {
            // Canlı sunucularda hata fırlatmadan doğrudan mevcut veritabanına bağlanır
        }
    }

    /**
     * Tabloların varlığını kontrol eder, eksik tabloları ve güncellemeleri otomatik uygular
     */
    private static function ensureTablesExist(): void
    {
        if (self::$instance === null) {
            return;
        }

        // 1. users tablosu yoksa tüm schema.sql'i çalıştır
        $stmtUsers = self::$instance->query("SHOW TABLES LIKE 'users'");
        if ($stmtUsers->rowCount() === 0) {
            $schemaFile = CONFIG_PATH . '/schema.sql';
            if (file_exists($schemaFile)) {
                $sql = file_get_contents($schemaFile);
                if ($sql !== false) {
                    self::$instance->exec($sql);
                }
            }
            return;
        }

        // 2. Canlı Sunucu Otomatik Onarım: gallery_items tablosu yoksa oluştur ve verileri yükle
        $stmtGal = self::$instance->query("SHOW TABLES LIKE 'gallery_items'");
        if ($stmtGal->rowCount() === 0) {
            $schemaFile = CONFIG_PATH . '/schema.sql';
            if (file_exists($schemaFile)) {
                $sql = file_get_contents($schemaFile);
                if ($sql !== false) {
                    self::$instance->exec($sql);
                }
            }
        }

        // 3. quote_requests tablosu yoksa oluştur
        $stmtQuotes = self::$instance->query("SHOW TABLES LIKE 'quote_requests'");
        if ($stmtQuotes->rowCount() === 0) {
            $schemaFile = CONFIG_PATH . '/schema.sql';
            if (file_exists($schemaFile)) {
                $sql = file_get_contents($schemaFile);
                if ($sql !== false) {
                    self::$instance->exec($sql);
                }
            }
        }

        // 4. Ürün Kartları Düzeltmesi (Cins isimleri yerine Ana Ürün İsimleri & Temizlik)
        try {
            self::$instance->exec("UPDATE products SET name_tr = 'Portakal', name_en = 'Orange' WHERE slug = 'washington-portakal' AND name_tr != 'Portakal'");
            self::$instance->exec("UPDATE products SET name_tr = 'Limon', name_en = 'Lemon' WHERE slug = 'mayer-limon' AND name_tr != 'Limon'");
            self::$instance->exec("UPDATE products SET name_tr = 'Greyfurt', name_en = 'Grapefruit' WHERE slug = 'star-ruby-greyfurt' AND name_tr != 'Greyfurt'");
            self::$instance->exec("DELETE FROM products WHERE slug = 'white-marsh-seedless' OR name_tr LIKE '%Test%'");
        } catch (\Throwable $e) {}

        // 5. Hububat ve Mandalina eksikse schema'dan tamamla
        try {
            $stmtMandalina = self::$instance->query("SELECT id FROM products WHERE slug = 'mandalina'");
            if ($stmtMandalina->rowCount() === 0) {
                $schemaFile = CONFIG_PATH . '/schema.sql';
                if (file_exists($schemaFile)) {
                    $sql = file_get_contents($schemaFile);
                    if ($sql !== false) {
                        self::$instance->exec($sql);
                    }
                }
            }
        } catch (\Throwable $e) {}
    }
}
