<?php
/**
 * BİKGRUP - Teklif Talepleri Modeli
 */

declare(strict_types=1);

require_once ROOT_PATH . '/config/database.php';

class QuoteRequest
{
    public static function all(?string $status = null): array
    {
        $db = Database::getConnection();
        if ($status && in_array($status, ['pending', 'replied', 'completed'], true)) {
            $stmt = $db->prepare("SELECT * FROM quote_requests WHERE status = ? ORDER BY created_at DESC");
            $stmt->execute([$status]);
            return $stmt->fetchAll();
        }
        return $db->query("SELECT * FROM quote_requests ORDER BY (status = 'pending') DESC, created_at DESC")->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM quote_requests WHERE id = ?");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function pendingCount(): int
    {
        $db = Database::getConnection();
        return (int)$db->query("SELECT COUNT(*) FROM quote_requests WHERE status = 'pending'")->fetchColumn();
    }

    public static function create(array $data): int
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO quote_requests (product_category, quantity, full_name, phone, email, notes, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())
        ");
        $stmt->execute([
            $data['product_category'],
            $data['quantity'],
            $data['full_name'],
            $data['phone'],
            $data['email'],
            $data['notes'] ?? ''
        ]);
        return (int)$db->lastInsertId();
    }

    public static function updateStatus(int $id, string $status, ?string $adminNotes = null, ?string $quoteAmount = null): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            UPDATE quote_requests 
            SET status = ?, admin_notes = COALESCE(?, admin_notes), quote_amount = COALESCE(?, quote_amount), updated_at = NOW() 
            WHERE id = ?
        ");
        return $stmt->execute([$status, $adminNotes, $quoteAmount, $id]);
    }

    public static function delete(int $id): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM quote_requests WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

