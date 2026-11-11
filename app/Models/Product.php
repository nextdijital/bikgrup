<?php
/**
 * BİKGRUP - Ürün Modeli
 */

declare(strict_types=1);

require_once ROOT_PATH . '/config/database.php';

class Product
{
    public static function all(bool $onlyActive = true, ?int $categoryId = null, ?int $limit = null): array
    {
        $db = Database::getConnection();
        $sql = "SELECT p.*, c.name_tr AS category_name_tr, c.name_en AS category_name_en, c.badge_color, c.slug AS category_slug 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE 1=1";

        $params = [];

        if ($onlyActive) {
            $sql .= " AND p.is_active = 1";
        }
        if ($categoryId !== null) {
            $sql .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }

        $sql .= " ORDER BY p.sort_order ASC, p.id DESC";

        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();
        foreach ($rows as &$r) {
            $r['extra'] = !empty($r['extra_json']) ? json_decode($r['extra_json'], true) : [];
        }
        return $rows;
    }

    public static function featured(int $limit = 6): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT p.*, c.name_tr AS category_name_tr, c.name_en AS category_name_en, c.badge_color, c.slug AS category_slug 
                              FROM products p 
                              LEFT JOIN categories c ON p.category_id = c.id 
                              WHERE p.is_active = 1 AND p.is_featured = 1 
                              ORDER BY p.sort_order ASC, p.id DESC LIMIT ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        foreach ($rows as &$r) {
            $r['extra'] = !empty($r['extra_json']) ? json_decode($r['extra_json'], true) : [];
        }
        return $rows;
    }

    public static function findBySlug(string $slug): ?array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT p.*, c.name_tr AS category_name_tr, c.name_en AS category_name_en, c.badge_color, c.slug AS category_slug 
                              FROM products p 
                              LEFT JOIN categories c ON p.category_id = c.id 
                              WHERE p.slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        if ($row) {
            $row['extra'] = !empty($row['extra_json']) ? json_decode($row['extra_json'], true) : [];
            return $row;
        }
        return null;
    }

    public static function find(int $id): ?array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT p.*, c.name_tr AS category_name_tr, c.name_en AS category_name_en, c.badge_color, c.slug AS category_slug 
                              FROM products p 
                              LEFT JOIN categories c ON p.category_id = c.id 
                              WHERE p.id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row) {
            $row['extra'] = !empty($row['extra_json']) ? json_decode($row['extra_json'], true) : [];
            return $row;
        }
        return null;
    }

    public static function create(array $data): int|bool
    {
        $db = Database::getConnection();
        $extraJson = isset($data['extra']) ? json_encode($data['extra'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null;
        $stmt = $db->prepare("INSERT INTO products 
            (category_id, slug, name_tr, name_en, short_desc_tr, short_desc_en, content_tr, content_en, image, origin_tr, origin_en, harvest_season_tr, harvest_season_en, packaging_tr, packaging_en, extra_json, temperature, is_featured, is_active, sort_order) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $ok = $stmt->execute([
            (int)$data['category_id'],
            $data['slug'],
            $data['name_tr'],
            $data['name_en'] ?? $data['name_tr'],
            $data['short_desc_tr'] ?? '',
            $data['short_desc_en'] ?? '',
            $data['content_tr'] ?? '',
            $data['content_en'] ?? '',
            $data['image'] ?? '',
            $data['origin_tr'] ?? 'Akdeniz & Ege / Türkiye',
            $data['origin_en'] ?? 'Mediterranean & Aegean / Turkey',
            $data['harvest_season_tr'] ?? 'Ekim - Mayıs',
            $data['harvest_season_en'] ?? 'October - May',
            $data['packaging_tr'] ?? 'Koli & Kasa',
            $data['packaging_en'] ?? 'Boxes & Crates',
            $extraJson,
            $data['temperature'] ?? '+4°C / +8°C',
            (int)($data['is_featured'] ?? 1),
            (int)($data['is_active'] ?? 1),
            (int)($data['sort_order'] ?? 0)
        ]);

        return $ok ? (int)$db->lastInsertId() : false;
    }

    public static function update(int $id, array $data): bool
    {
        $db = Database::getConnection();
        $extraJson = isset($data['extra']) ? json_encode($data['extra'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null;
        $stmt = $db->prepare("UPDATE products SET 
            category_id = ?, slug = ?, name_tr = ?, name_en = ?, short_desc_tr = ?, short_desc_en = ?, 
            content_tr = ?, content_en = ?, image = ?, origin_tr = ?, origin_en = ?, harvest_season_tr = ?, 
            harvest_season_en = ?, packaging_tr = ?, packaging_en = ?, extra_json = ?, temperature = ?, is_featured = ?, 
            is_active = ?, sort_order = ? 
            WHERE id = ?");

        return $stmt->execute([
            (int)($data['category_id'] ?? 1),
            $data['slug'] ?? ('urun-' . $id),
            $data['name_tr'] ?? '',
            $data['name_en'] ?? ($data['name_tr'] ?? ''),
            $data['short_desc_tr'] ?? '',
            $data['short_desc_en'] ?? '',
            $data['content_tr'] ?? '',
            $data['content_en'] ?? '',
            $data['image'] ?? '',
            $data['origin_tr'] ?? '',
            $data['origin_en'] ?? '',
            $data['harvest_season_tr'] ?? '',
            $data['harvest_season_en'] ?? '',
            $data['packaging_tr'] ?? '',
            $data['packaging_en'] ?? '',
            $extraJson,
            $data['temperature'] ?? '',
            (int)($data['is_featured'] ?? 1),
            (int)($data['is_active'] ?? 1),
            (int)($data['sort_order'] ?? 0),
            $id
        ]);
    }

    public static function delete(int $id): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
