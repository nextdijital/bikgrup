<?php
/**
 * BİKGRUP - İletişim Mesajları Modeli
 */

declare(strict_types=1);

require_once ROOT_PATH . '/config/database.php';

class Message
{
    public static function all(): array
    {
        $db = Database::getConnection();
        return $db->query("SELECT * FROM messages ORDER BY is_read ASC, created_at DESC")->fetchAll();
    }

    public static function unreadCount(): int
    {
        $db = Database::getConnection();
        return (int)$db->query("SELECT COUNT(*) FROM messages WHERE is_read = 0")->fetchColumn();
    }

    public static function create(array $data): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO messages (name, email, phone, subject, message, ip_address) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'] ?? '',
            $data['subject'] ?? 'Web Sitesi İletişim Talebi',
            $data['message'],
            $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
        ]);
    }

    public static function markAsRead(int $id): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE messages SET is_read = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function delete(int $id): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM messages WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
