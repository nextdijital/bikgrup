<?php
/**
 * BİKGRUP - Kurumsal Konfigürasyon Dosyası
 * PHP 8.2 / 8.3 / 8.4 ve Canlı Sunucu (Production) Tam Uyumlu
 */

declare(strict_types=1);

// Klasör Yolları
define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('CORE_PATH', ROOT_PATH . '/core');
define('APP_PATH', ROOT_PATH . '/app');
define('VIEWS_PATH', ROOT_PATH . '/views');
define('LANG_PATH', ROOT_PATH . '/languages');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOADS_PATH', PUBLIC_PATH . '/images');

// .env Dosyası Desteği (Kök dizinde .env dosyası varsa otomatik yükler)
$envFile = ROOT_PATH . '/.env';
$env = [];
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines !== false) {
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) continue;
            if (str_contains($line, '=')) {
                [$k, $v] = explode('=', $line, 2);
                $k = trim($k);
                $v = trim($v, " \t\n\r\0\x0B\"'");
                $env[$k] = $v;
                if (getenv($k) === false) {
                    putenv("{$k}={$v}");
                }
            }
        }
    }
}

// Alan Adı ve Ortam Tespiti (Canlı vs Geliştirme)
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$hostOnly = strtolower(explode(':', $host)[0]);
$isLocal = in_array($hostOnly, ['localhost', '127.0.0.1', '::1'], true);

$appEnv = $env['APP_ENV'] ?? ($isLocal ? 'development' : 'production');
define('APP_ENV', $appEnv);

// Hata Raporlama (Canlıda kullanıcıya hata gizlenir, log dosyasına güvenle yazılır)
if ($appEnv === 'production') {
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    ini_set('log_errors', '1');
    $logDir = ROOT_PATH . '/storage/logs';
    if (is_dir($logDir) && is_writable($logDir)) {
        ini_set('error_log', $logDir . '/production_error.log');
    }
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
           (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

// Oturum güvenliği ayarları (Session başlatılmadan önce tanımlanmalı)
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_samesite', 'Lax');
    
    // HTTPS varsa çerezi SSL üzerinden şifrele
    if ($isHttps) {
        ini_set('session.cookie_secure', '1');
    }
    session_start();
}

// PHP 8.0+ Uyumluluk Polyfill'leri (Her sürümde sıfır hata garantisi)
if (!function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string $needle): bool {
        return $needle === '' || strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}
if (!function_exists('str_ends_with')) {
    function str_ends_with(string $haystack, string $needle): bool {
        return $needle === '' || substr($haystack, -strlen($needle)) === $needle;
    }
}
if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool {
        return $needle === '' || strpos($haystack, $needle) !== false;
    }
}

// Global URL Çözümleyici (Kategori linkleri, dış URL'ler ve iç rotalar için)
if (!function_exists('resolveUrl')) {
    function resolveUrl(?string $url, string $default = '/'): string {
        $u = trim($url ?? '');
        if ($u === '') $u = $default;
        if (str_starts_with($u, 'http://') || str_starts_with($u, 'https://') || str_starts_with($u, '//') || str_starts_with($u, '#')) {
            return $u;
        }
        return BASE_URL . '/' . ltrim($u, '/');
    }
}

// Veritabanı Bilgileri (.env veya doğrudan ayar)
define('DB_HOST', $env['DB_HOST'] ?? (getenv('DB_HOST') ?: 'localhost'));
define('DB_PORT', $env['DB_PORT'] ?? (getenv('DB_PORT') ?: '3306'));
define('DB_NAME', $env['DB_NAME'] ?? (getenv('DB_NAME') ?: 'u824589774_bikgrup'));

// Yerel (Localhost) ve Canlı (Production) Otomatik Tespiti
if ($isLocal) {
    // Localhost XAMPP varsayılanı (root / şifresiz) veya .env tanımı
    define('DB_USER', $env['DB_USER'] ?? (getenv('DB_USER') ?: 'root'));
    define('DB_PASS', $env['DB_PASS'] ?? (getenv('DB_PASS') !== false ? getenv('DB_PASS') : ''));
} else {
    // Canlı Sunucu (Production) Bilgileri - ASLA Zarar Verilmez
    define('DB_USER', $env['DB_USER'] ?? (getenv('DB_USER') ?: 'u824589774_bikgrup_user'));
    define('DB_PASS', $env['DB_PASS'] ?? (getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'Nextdijital0102.'));
}
define('DB_CHARSET', $env['DB_CHARSET'] ?? (getenv('DB_CHARSET') ?: 'utf8mb4'));

// Uygulama Sabitleri
define('APP_NAME', 'BİKGRUP');
define('PANEL_SLUG', 'xpanel');
define('DEFAULT_LANG', 'tr');
define('SUPPORTED_LANGS', ['tr', 'en']);

// Base URL Tespiti (Canlı Domain ve Yerel Alt Klasörlerle %100 Uyumlu)
if (!empty($env['APP_URL'])) {
    $baseUrl = rtrim($env['APP_URL'], '/');
} else {
    $protocol = $isHttps ? "https://" : "http://";
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $scriptDir = rtrim(dirname($scriptName), '/\\');

    // Eğer /bikgrup veya /bikgroup gibi bir alt dizindeyse
    if ($scriptDir !== '' && $scriptDir !== '/' && $scriptDir !== '.') {
        $baseUrl = $protocol . $host . $scriptDir;
    } else {
        // Canlı kök domain (örn: https://bikgrup.com)
        $baseUrl = $protocol . $host;
    }
}
define('BASE_URL', rtrim($baseUrl, '/'));
