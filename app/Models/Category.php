<?php
/**
 * BİKGRUP - Kategori Modeli
 */

declare(strict_types=1);

require_once ROOT_PATH . '/config/database.php';

class Category
{
    public static function all(bool $onlyActive = true): array
    {
        $db = Database::getConnection();
        $sql = "SELECT * FROM categories";
        if ($onlyActive) {
            $sql .= " WHERE is_active = 1";
        }
        $sql .= " ORDER BY sort_order ASC, id ASC";
        return $db->query($sql)->fetchAll();
    }

    public static function findBySlug(string $slug): ?array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM categories WHERE slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function find(int $id): ?array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM categories WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO categories (slug, name_tr, name_en, description_tr, description_en, badge_color, image, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['slug'],
            $data['name_tr'],
            $data['name_en'] ?? $data['name_tr'],
            $data['description_tr'] ?? '',
            $data['description_en'] ?? '',
            $data['badge_color'] ?? '#15803d',
            $data['image'] ?? '',
            (int)($data['sort_order'] ?? 0),
            (int)($data['is_active'] ?? 1)
        ]);
    }

    public static function update(int $id, array $data): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE categories SET slug = ?, name_tr = ?, name_en = ?, description_tr = ?, description_en = ?, badge_color = ?, image = ?, sort_order = ?, is_active = ? WHERE id = ?");
        return $stmt->execute([
            $data['slug'],
            $data['name_tr'],
            $data['name_en'] ?? $data['name_tr'],
            $data['description_tr'] ?? '',
            $data['description_en'] ?? '',
            $data['badge_color'] ?? '#15803d',
            $data['image'] ?? '',
            (int)($data['sort_order'] ?? 0),
            (int)($data['is_active'] ?? 1),
            $id
        ]);
    }

    public static function delete(int $id): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
