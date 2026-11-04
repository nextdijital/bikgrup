<?php
/**
 * BİKGRUP - Dinamik Ayarlar ve Canlı Tema Motoru
 * PHP 8.2 / 8.3 / 8.4 Uyumlu
 */

declare(strict_types=1);

require_once ROOT_PATH . '/config/database.php';

class Settings
{
    private static array $cache = [];
    private static bool $loaded = false;

    /**
     * Tüm ayarları veritabanından yükler
     */
    public static function load(): void
    {
        if (self::$loaded) {
            return;
        }

        try {
            $db = Database::getConnection();
            $rows = $db->query("SELECT setting_key, setting_value, group_name FROM settings")->fetchAll();
            foreach ($rows as $row) {
                self::$cache[$row['setting_key']] = $row['setting_value'];
            }
            self::$loaded = true;
        } catch (Exception $e) {
            self::$cache = [];
        }
    }

    /**
     * Tekil ayar değerini getirir
     */
    public static function get(string $key, ?string $default = null): string
    {
        if (!self::$loaded) {
            self::load();
        }

        return self::$cache[$key] ?? $default ?? '';
    }

    /**
     * Dile duyarlı ayar getirir (Örn: site_tagline_tr / site_tagline_en)
     */
    public static function getLocalized(string $baseKey, ?string $default = null): string
    {
        $lang = Lang::current();
        $localizedKey = $baseKey . '_' . $lang;

        $val = self::get($localizedKey);
        if (!empty($val)) {
            return $val;
        }

        // Fallback varsayılan Türkçe veya düz anahtar
        $fallback = self::get($baseKey . '_tr');
        if (!empty($fallback)) {
            return $fallback;
        }

        return self::get($baseKey, $default);
    }

    /**
     * Dinamik Tema CSS Değişkenlerini Render Eder
     * Sitenin tüm renkleri, arka planı, yazı renkleri tek tıkla canlı olarak değişir!
     */
    public static function renderCssVariables(): string
    {
        $primary        = self::get('theme_primary', '#15803d');
        $primaryHover   = self::get('theme_primary_hover', '#166534');
        $secondary      = self::get('theme_secondary', '#ea580c');
        $secondaryHover = self::get('theme_secondary_hover', '#c2410c');
        $accent         = self::get('theme_accent', '#059669');
        $bg             = self::get('theme_bg', '#fcfdfd');
        $cardBg         = self::get('theme_card_bg', '#ffffff');
        $text           = self::get('theme_text', '#0f172a');
        $textMuted      = self::get('theme_text_muted', '#64748b');
        $navbarBg       = self::get('theme_navbar_bg', 'rgba(255, 255, 255, 0.94)');
        $radius         = self::get('theme_border_radius', '20px');

        return "
        <style id='bikgrup-dynamic-theme'>
            :root {
                --theme-primary: {$primary};
                --theme-primary-hover: {$primaryHover};
                --theme-secondary: {$secondary};
                --theme-secondary-hover: {$secondaryHover};
                --theme-accent: {$accent};
                --theme-bg: {$bg};
                --theme-card-bg: {$cardBg};
                --theme-text: {$text};
                --theme-text-muted: {$textMuted};
                --theme-navbar-bg: {$navbarBg};
                --theme-radius: {$radius};
                --theme-primary-rgb: " . self::hexToRgb($primary) . ";
                --theme-secondary-rgb: " . self::hexToRgb($secondary) . ";
            }
        </style>";
    }

    /**
     * Hex rengi RGB formatına çevirir (rgba transparanlıkları için)
     */
    public static function hexToRgb(string $hex): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        if (strlen($hex) !== 6) {
            return '21, 128, 61'; // fallback yeşil
        }
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        return "{$r}, {$g}, {$b}";
    }
}
