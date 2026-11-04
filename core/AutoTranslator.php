<?php
/**
 * BİKGRUP - Otomatik Çoklu Dil ve Çeviri Servisi (AutoTranslator)
 * Google Çeviri API (Free GTX) & Akıllı Tarımsal Sözlük Hibrit Motoru
 * PHP 8.2 / 8.3 / 8.4 Uyumlu
 */

declare(strict_types=1);

class AutoTranslator
{
    private static array $cache = [];

    /**
     * Sık kullanılan tarımsal, kurumsal ve site terimleri sözlüğü (Hızlı yerel çeviri ve offline yedek)
     */
    private static array $localGlossary = [
        'Anasayfa'                => 'Home',
        'Kurumsal'                => 'Corporate',
        'Hakkımızda'              => 'About Us',
        'Markalarımız'            => 'Our Brands',
        'Ürünler'                 => 'Products',
        'Tüm Ürünler'             => 'All Products',
        'Tüm Ürünleri İncele'     => 'Explore All Products',
        'Blog & Haberler'         => 'Blog & News',
        'İletişim'                => 'Contact',
        'Bize Ulaşın'             => 'Contact Us',
        'Teklif Al'               => 'Get a Quote',
        'Keşfet'                  => 'Discover',
        'İncele'                  => 'View',
        'Görüntüle'               => 'Details',
        'Kurumsal Keşfet'         => 'Discover Corporate',
        'Tarihçemiz'              => 'Our History',
        'Misyon & Vizyon'         => 'Mission & Vision',
        'Tesislerimiz'            => 'Our Facilities',
        'Kalite & Tesis'          => 'Quality & Facilities',
        'Narenciye'               => 'Citrus',
        'Meyveler'                => 'Fruits',
        'Sebzeler'                => 'Vegetables',
        'Hububat'                 => 'Grains & Cereals',
        'Buğday'                  => 'Wheat',
        'Mısır'                   => 'Corn',
        'Ayçekirdeği'             => 'Sunflower Seeds',
        'Soya Fasulyesi'          => 'Soybeans',
        'Limon'                   => 'Lemon',
        'Portakal'                => 'Orange',
        'Mandalina'               => 'Mandarin',
        'Greyfurt'                => 'Grapefruit',
        'Karpuz'                  => 'Watermelon',
        'Domates'                 => 'Tomato',
        'Ekim - Nisan'            => 'October - April',
        'Mayıs - Eylül'           => 'May - September',
        'Tüm Yıl'                 => 'All Year',
        'Sezonsal'                => 'Seasonal',
        'Akdeniz & Ege / Türkiye' => 'Mediterranean & Aegean / Turkey',
        'Teleskopik Kutu & Plastik Kasa' => 'Telescopic Box & Plastic Crate',
        'Hızlı Bağlantılar'       => 'Quick Links',
        'Ürünlerimiz'             => 'Our Products',
        'Politikalar'             => 'Policies',
        'İletişim & Lokasyon'     => 'Contact & Location',
        'Tüm hakları saklıdır.'   => 'All rights reserved.'
    ];

    /**
     * Türkçe metni otomatik olarak İngilizce'ye çevirir.
     */
    public static function translate(string $text, string $from = 'tr', string $to = 'en'): string
    {
        $text = trim($text);
        if ($text === '') {
            return '';
        }

        // Salt sayı, sembol veya URL ise çeviri yapma
        if (is_numeric($text) || preg_match('#^https?://#i', $text) || preg_match('#^[0-9\s\+\-\(\)/]+$#', $text)) {
            return $text;
        }

        // 1. Yerel sözlük kontrolü
        if ($from === 'tr' && $to === 'en' && isset(self::$localGlossary[$text])) {
            return self::$localGlossary[$text];
        }

        // 2. Bellek içi önbellek kontrolü
        $cacheKey = "{$from}_{$to}_" . md5($text);
        if (isset(self::$cache[$cacheKey])) {
            return self::$cache[$cacheKey];
        }

        // 3. Google Translate Ücretsiz GTX Servisi
        $url = 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=' . urlencode($from) . '&tl=' . urlencode($to) . '&dt=t&q=' . rawurlencode($text);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 6);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($response !== false && empty($err)) {
            $data = json_decode($response, true);
            $result = '';
            if (is_array($data) && isset($data[0]) && is_array($data[0])) {
                foreach ($data[0] as $part) {
                    if (isset($part[0]) && is_string($part[0])) {
                        $result .= $part[0];
                    }
                }
            }

            $translated = trim($result);
            if ($translated !== '') {
                self::$cache[$cacheKey] = $translated;
                return $translated;
            }
        }

        // Çeviri servisine ulaşılamazsa orijinal metni koru
        return $text;
    }

    /**
     * Dizi içerisindeki metinleri topluca çevirir
     */
    public static function translateArray(array $items, string $from = 'tr', string $to = 'en'): array
    {
        $results = [];
        foreach ($items as $k => $v) {
            if (is_string($v)) {
                $results[$k] = self::translate($v, $from, $to);
            } elseif (is_array($v)) {
                $results[$k] = self::translateArray($v, $from, $to);
            } else {
                $results[$k] = $v;
            }
        }
        return $results;
    }

    /**
     * Dil dosyalarına (languages/tr.php ve languages/en.php) otomatik anahtar ve çeviri ekler
     */
    public static function appendToLanguageFiles(string $key, string $turkishText): void
    {
        $key = trim($key);
        $turkishText = trim($turkishText);
        if ($key === '' || $turkishText === '') {
            return;
        }

        $trPath = defined('LANG_PATH') ? LANG_PATH . '/tr.php' : __DIR__ . '/../languages/tr.php';
        $enPath = defined('LANG_PATH') ? LANG_PATH . '/en.php' : __DIR__ . '/../languages/en.php';

        $trData = file_exists($trPath) ? (include $trPath) : [];
        $enData = file_exists($enPath) ? (include $enPath) : [];

        if (!is_array($trData)) $trData = [];
        if (!is_array($enData)) $enData = [];

        $changed = false;

        if (!isset($trData[$key]) || $trData[$key] !== $turkishText) {
            $trData[$key] = $turkishText;
            $changed = true;
            self::saveDictionaryFile($trPath, $trData, 'Türkçe Dil Sözlüğü (tr.php)');
        }

        if (!isset($enData[$key]) || $changed) {
            $englishText = self::translate($turkishText, 'tr', 'en');
            $enData[$key] = $englishText;
            self::saveDictionaryFile($enPath, $enData, 'English Language Dictionary (en.php)');
        }
    }

    /**
     * PHP Sözlük Dosyasını Güvenle Kaydeder
     */
    private static function saveDictionaryFile(string $path, array $data, string $comment = ''): bool
    {
        $content = "<?php\n/**\n * BİKGRUP - {$comment}\n * Otomatik Güncellenme Tarihi: " . date('Y-m-d H:i:s') . "\n */\n\nreturn [\n";
        foreach ($data as $k => $v) {
            $escapedKey = addcslashes((string)$k, "'\\");
            $escapedVal = addcslashes((string)$v, "'\\");
            $content .= "    '{$escapedKey}' => '{$escapedVal}',\n";
        }
        $content .= "];\n";

        return (bool)file_put_contents($path, $content, LOCK_EX);
    }
}

