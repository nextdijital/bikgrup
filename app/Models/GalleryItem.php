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
}

