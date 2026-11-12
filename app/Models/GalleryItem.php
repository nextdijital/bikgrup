<?php
/**
 * BİKGRUP - Fotoğraf ve Video Galeri Modeli
 * PHP 8.2 / Strict Types
 */

declare(strict_types=1);

require_once ROOT_PATH . '/config/database.php';

class GalleryItem
{
    public static function all(bool $onlyActive = true, ?string $type = null): array
    {
        $db = Database::getConnection();
        $sql = "SELECT * FROM gallery_items";
        $clauses = [];
        $params = [];

        if ($onlyActive) {
            $clauses[] = "is_active = 1";
        }
        if (!empty($type)) {
            $clauses[] = "type = ?";
            $params[] = $type;
        }

        if (!empty($clauses)) {
            $sql .= " WHERE " . implode(" AND ", $clauses);
        }

        $sql .= " ORDER BY sort_order ASC, id DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM gallery_items WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO gallery_items (type, title_tr, title_en, category, image_path, video_url, video_file, sort_order, is_active)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['type'] ?? 'image',
            $data['title_tr'] ?? '',
            $data['title_en'] ?? '',
            $data['category'] ?? 'genel',
            $data['image_path'] ?? '',
            $data['video_url'] ?? '',
            $data['video_file'] ?? '',
            (int)($data['sort_order'] ?? 0),
            (int)($data['is_active'] ?? 1)
        ]);
    }

    public static function update(int $id, array $data): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            UPDATE gallery_items 
            SET type = ?, title_tr = ?, title_en = ?, category = ?, image_path = ?, video_url = ?, video_file = ?, sort_order = ?, is_active = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['type'] ?? 'image',
            $data['title_tr'] ?? '',
            $data['title_en'] ?? '',
            $data['category'] ?? 'genel',
            $data['image_path'] ?? '',
            $data['video_url'] ?? '',
            $data['video_file'] ?? '',
            (int)($data['sort_order'] ?? 0),
            (int)($data['is_active'] ?? 1),
            $id
        ]);
    }

    public static function delete(int $id): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM gallery_items WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Türkçe karakter uyumlu slugify yardımcısı
     */
    public static function slugify(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');
        $trChars = ['ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç'];
        $enChars = ['i', 'g', 'u', 's', 'o', 'c', 'i', 'g', 'u', 's', 'o', 'c'];
        $text = str_replace($trChars, $enChars, $text);
        $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
        return trim($text, '-_');
    }

    /**
     * Tüm galeri kategorilerini (Varsayılan + Özel eklenenler) getirir
     */
    public static function getCategories(): array
    {
        $defaultCategories = [
            'hasat'       => 'Hasat & Bahçelerimiz',
            'tesisler'    => 'Tesisler & Teknoloji',
            'paketleme'   => 'Paketleme & Tasarım',
            'urunler'     => 'Ürünlerimiz',
            'lojistik'    => 'Soğuk Zincir & Lojistik',
            'tanitim'     => 'Kurumsal Tanıtım',
            'genel'       => 'Genel'
        ];

        $custom = self::getCustomCategories();
        $categories = array_merge($defaultCategories, $custom);

        // Galeri tablosunda kayıtlı olup listede olmayan kategorileri de ekle
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT DISTINCT category FROM gallery_items WHERE category IS NOT NULL AND category != ''");
            while ($row = $stmt->fetch()) {
                $cat = trim((string)$row['category']);
                if (!empty($cat) && !isset($categories[$cat])) {
                    $categories[$cat] = mb_convert_case($cat, MB_CASE_TITLE, 'UTF-8');
                }
            }
        } catch (\Throwable $e) {}

        return $categories;
    }

    /**
     * Sadece kullanıcı tarafından sonradan eklenen özel kategorileri getirir
     */
    public static function getCustomCategories(): array
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = 'gallery_categories' LIMIT 1");
            $stmt->execute();
            $val = $stmt->fetchColumn();
            if ($val) {
                $decoded = json_decode((string)$val, true);
                if (is_array($decoded)) {
                    return $decoded;
                }
            }
        } catch (\Throwable $e) {}
        return [];
    }

    /**
     * Yeni galeri kategorisi ekler veya günceller
     */
    public static function saveCategory(string $label, ?string $key = null): bool
    {
        $label = trim($label);
        if (empty($label)) {
            return false;
        }

        if (empty($key)) {
            $key = self::slugify($label);
        } else {
            $key = self::slugify($key);
        }

        if (empty($key)) {
            $key = 'kat_' . substr(md5($label), 0, 6);
        }

        $custom = self::getCustomCategories();
        $custom[$key] = $label;

        return self::saveCategoriesSetting($custom);
    }

    /**
     * Özel galeri kategorisini siler
     */
    public static function deleteCategory(string $key): bool
    {
        $key = trim($key);
        $custom = self::getCustomCategories();
        if (isset($custom[$key])) {
            unset($custom[$key]);
            return self::saveCategoriesSetting($custom);
        }
        return false;
    }

    /**
     * Özel kategorileri settings tablosuna JSON formatında kaydeder
     */
    private static function saveCategoriesSetting(array $custom): bool
    {
        $json = json_encode($custom, JSON_UNESCAPED_UNICODE);
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO settings (setting_key, setting_value, group_name, label_tr, input_type)
            VALUES ('gallery_categories', ?, 'gallery', 'Galeri Kategorileri (Özel)', 'textarea')
            ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
        ");
        return $stmt->execute([$json]);
    }
}

