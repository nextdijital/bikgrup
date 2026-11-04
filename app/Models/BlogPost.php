<?php
/**
 * BİKGRUP - Blog ve Haberler Modeli
 */

declare(strict_types=1);

require_once ROOT_PATH . '/config/database.php';

class BlogPost
{
    public static function all(bool $onlyPublished = true, ?int $limit = null): array
    {
        $db = Database::getConnection();
        $sql = "SELECT * FROM blog_posts";
        if ($onlyPublished) {
            $sql .= " WHERE is_published = 1";
        }
        $sql .= " ORDER BY created_at DESC, id DESC";
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
        }
        return $db->query($sql)->fetchAll();
    }

    public static function findBySlug(string $slug): ?array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM blog_posts WHERE slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        if ($row) {
            // Görüntülenme sayısını artır
            $upStmt = $db->prepare("UPDATE blog_posts SET views = views + 1 WHERE id = ?");
            $upStmt->execute([$row['id']]);
        }
        return $row ?: null;
    }

    public static function find(int $id): ?array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM blog_posts WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO blog_posts 
            (slug, title_tr, title_en, excerpt_tr, excerpt_en, content_tr, content_en, image, author, read_time, is_published) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        return $stmt->execute([
            $data['slug'],
            $data['title_tr'],
            $data['title_en'] ?? $data['title_tr'],
            $data['excerpt_tr'] ?? '',
            $data['excerpt_en'] ?? '',
            $data['content_tr'] ?? '',
            $data['content_en'] ?? '',
            $data['image'] ?? '',
            $data['author'] ?? 'BİKGRUP Basın',
            $data['read_time'] ?? '4 dk',
            (int)($data['is_published'] ?? 1)
        ]);
    }

    public static function update(int $id, array $data): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE blog_posts SET 
            slug = ?, title_tr = ?, title_en = ?, excerpt_tr = ?, excerpt_en = ?, 
            content_tr = ?, content_en = ?, image = ?, author = ?, read_time = ?, is_published = ? 
            WHERE id = ?");

        return $stmt->execute([
            $data['slug'] ?? ('post-' . $id),
            $data['title_tr'] ?? '',
            $data['title_en'] ?? ($data['title_tr'] ?? ''),
            $data['excerpt_tr'] ?? '',
            $data['excerpt_en'] ?? '',
            $data['content_tr'] ?? '',
            $data['content_en'] ?? '',
            $data['image'] ?? '',
            $data['author'] ?? 'BİKGRUP Basın',
            $data['read_time'] ?? '4 dk',
            (int)($data['is_published'] ?? 1),
            $id
        ]);
    }

    public static function delete(int $id): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM blog_posts WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
