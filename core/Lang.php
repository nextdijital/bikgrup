<?php
/**
 * BİKGRUP - Çoklu Dil (i18n) Motoru
 * PHP 8.2 / 8.3 / 8.4 Uyumlu
 */

declare(strict_types=1);

class Lang
{
    private static string $currentLang = DEFAULT_LANG;
    private static array $translations = [];
    private static bool $initialized = false;

    /**
     * Dil motorunu başlatır
     */
    public static function init(): void
    {
        if (self::$initialized) {
            return;
        }

        // 1. URL parametresinden dil değişimi kontrolü
        if (isset($_GET['lang']) && in_array($_GET['lang'], SUPPORTED_LANGS, true)) {
            self::$currentLang = $_GET['lang'];
            $_SESSION['app_lang'] = self::$currentLang;
            setcookie('app_lang', self::$currentLang, time() + (86400 * 30), '/');
        } 
        // 2. Oturumdan dil kontrolü
        elseif (!empty($_SESSION['app_lang']) && in_array($_SESSION['app_lang'], SUPPORTED_LANGS, true)) {
            self::$currentLang = $_SESSION['app_lang'];
        } 
        // 3. Çerezden dil kontrolü
        elseif (!empty($_COOKIE['app_lang']) && in_array($_COOKIE['app_lang'], SUPPORTED_LANGS, true)) {
            self::$currentLang = $_COOKIE['app_lang'];
        }

        // Dil dosyasını yükle
        self::loadDictionary(self::$currentLang);
        self::$initialized = true;
    }

    /**
     * Dil dosyasını belleğe yükler
     */
    private static function loadDictionary(string $lang): void
    {
        $filePath = LANG_PATH . '/' . $lang . '.php';
        if (file_exists($filePath)) {
            self::$translations = require $filePath;
        } else {
            self::$translations = [];
        }
    }

    /**
     * Çeviri metnini getirir
     */
    public static function get(string $key, ?string $default = null): string
    {
        if (!self::$initialized) {
            self::init();
        }

        if (isset(self::$translations[$key])) {
            return self::$translations[$key];
        }

        // İngilizce aktifken anahtar bulunamadıysa ve Türkçe varsayılan metin varsa otomatik çevir ve dil dosyasına kaydet
        if (self::$currentLang === 'en' && !empty($default) && class_exists('AutoTranslator')) {
            $translated = AutoTranslator::translate($default, 'tr', 'en');
            self::$translations[$key] = $translated;
            AutoTranslator::appendToLanguageFiles($key, $default);
            return $translated;
        }

        return $default ?? $key;
    }

    /**
     * Aktif dil kodunu döner (tr, en)
     */
    public static function current(): string
    {
        if (!self::$initialized) {
            self::init();
        }
        return self::$currentLang;
    }

    /**
     * Belirli bir dil için link oluşturur
     */
    public static function switchUrl(string $targetLang): string
    {
        $query = $_GET;
        $query['lang'] = $targetLang;
        return '?' . http_build_query($query);
    }
}

/**
 * Global Çeviri Kısayol Fonksiyonu
 */
function __(string $key, ?string $default = null): string
{
    return Lang::get($key, $default);
}
