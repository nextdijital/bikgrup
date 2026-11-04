<?php
/**
 * BİKGRUP - Kimlik Doğrulama ve Dokunulmaz Süper Admin Yönetimi
 * PHP 8.2 / 8.3 / 8.4 Uyumlu
 */

declare(strict_types=1);

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/core/Security.php';

class Auth
{
    /**
     * Oturum açmış kullanıcı bilgisi
     */
    public static function user(): ?array
    {
        return $_SESSION['auth_user'] ?? null;
    }

    /**
     * Oturum açılmış mı?
     */
    public static function check(): bool
    {
        return !empty($_SESSION['auth_user']);
    }

    /**
     * Giriş yapan kullanıcının ID'si
     */
    public static function id(): ?int
    {
        return $_SESSION['auth_user']['id'] ?? null;
    }

    /**
     * Süper Admin mi?
     */
    public static function isSuperAdmin(): bool
    {
        return isset($_SESSION['auth_user']['role']) && $_SESSION['auth_user']['role'] === 'superadmin';
    }

    /**
     * Dokunulmaz Admin mi?
     */
    public static function isImmutable(): bool
    {
        return !empty($_SESSION['auth_user']['is_immutable']);
    }

    /**
     * Yönetici Girişi
     */
    public static function login(string $email, string $password): array
    {
        $email = trim($email);

        // Rate limit kontrolü (5 başarısız denemede 5 dakika kilit)
        if (!Security::checkRateLimit('admin_login', 5, 300)) {
            return [
                'success' => false,
                'message' => 'Çok fazla başarısız giriş denemesi yapıldı. Lütfen 5 dakika sonra tekrar deneyin.'
            ];
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !Security::verifyPassword($password, $user['password'])) {
            Security::recordFailedAttempt('admin_login');
            return [
                'success' => false,
                'message' => 'Geçersiz e-posta adresi veya şifre!'
            ];
        }

        // Başarılı giriş
        Security::resetRateLimit('admin_login');

        // Oturum yenileme (Session Fixation Koruması)
        if (!headers_sent()) {
            session_regenerate_id(true);
        }

        $_SESSION['auth_user'] = [
            'id'           => (int)$user['id'],
            'name'         => $user['name'],
            'email'        => $user['email'],
            'role'         => $user['role'],
            'is_immutable' => (bool)$user['is_immutable']
        ];

        // Son giriş tarihini güncelle
        $updateStmt = $db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $updateStmt->execute([$user['id']]);

        return [
            'success' => true,
            'message' => 'Giriş başarılı. Yönlendiriliyorsunuz...'
        ];
    }

    /**
     * Oturumu Sonlandır
     */
    public static function logout(): void
    {
        unset($_SESSION['auth_user']);
        if (!headers_sent()) {
            session_regenerate_id(true);
        }
    }

    /**
     * Hedef kullanıcı silinebilir mi veya yetkisi değiştirilebilir mi?
     * Kural: is_immutable = 1 olan veya Süleyman Admin dokunulmazdır!
     */
    public static function canModifyUser(int $targetUserId): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT is_immutable, email FROM users WHERE id = ?");
        $stmt->execute([$targetUserId]);
        $target = $stmt->fetch();

        if (!$target) {
            return false;
        }

        // Dokunulmaz hesaba müdahale edilemez!
        if ((int)$target['is_immutable'] === 1 || strtolower($target['email']) === 'suleymannozer0@gmail.com') {
            // Sadece kendisi kendi şifresini değiştirebilir, başka kimse dokunamaz
            return self::id() === $targetUserId;
        }

        return true;
    }

    /**
     * Hedef kullanıcı silinebilir mi?
     * Dokunulmaz admin ve süper admin ASLA silinemez!
     */
    public static function canDeleteUser(int $targetUserId): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT is_immutable, email FROM users WHERE id = ?");
        $stmt->execute([$targetUserId]);
        $target = $stmt->fetch();

        if (!$target) {
            return false;
        }

        // Asla silinemez!
        if ((int)$target['is_immutable'] === 1 || strtolower($target['email']) === 'suleymannozer0@gmail.com') {
            return false;
        }

        // Kişi kendini de silemez
        if (self::id() === $targetUserId) {
            return false;
        }

        return true;
    }
}
