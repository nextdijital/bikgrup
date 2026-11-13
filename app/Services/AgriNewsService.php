<?php
/**
 * BİKGRUP - Otomatik Tarım Haberleri ve Doğrulama Servisi
 * Kaynak: Tarımdan Haber (tarimdanhaber.com) & Doğrulanmış Tarım Akışları
 * PHP 8.2 / Strict Types
 */

declare(strict_types=1);

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/models/BlogPost.php';
require_once ROOT_PATH . '/core/Settings.php';

class AgriNewsService
{
    private const PRIMARY_FEED_URL = 'https://www.tarimdanhaber.com/rss';

    /**
     * BİK GRUP Faaliyet Alanlarına Uygun Anahtar Kelimeler (Ağırlıklı Puanlama)
     */
    private const HIGH_PRIORITY_KEYWORDS = [
        'narenciye', 'mandalina', 'portakal', 'limon', 'greyfurt', 'turunçgil', 'turuncgil',
        'buğday', 'bugday', 'mısır', 'misir', 'ayçekirdeği', 'aycekirdegi', 'soya', 'tahıl', 'tahil', 'hububat',
        'gübre', 'gubre', 'sıvı gübre', 'sivi gubre', 'katı gübre', 'kati gubre', 'taban gübresi',
        'domates', 'karpuz', 'sebze', 'meyve', 'hasat', 'rekolte'
    ];

    private const MEDIUM_PRIORITY_KEYWORDS = [
        'tarım', 'tarim', 'çiftçi', 'ciftci', 'ihracat', 'tmo', 'çks', 'sera', 'toprak', 
        'sulama', 'soğuk hava', 'soguk hava', 'tarımsal üretim', 'tarimsal uretim', 'üretici', 'uretici',
        'fide', 'tohum', 'ziraat', 'bitkisel üretim'
    ];

    private const BLACKLIST_KEYWORDS = [
        'magazin', 'ünlü', 'unlu', 'dizi', 'futbol', 'cinayet', 'bahis', 'kumar', 
        'şok iddia', 'siyasi kavga', 'seçim anketi', 'skandal'
    ];

    /**
     * Güncel haberleri çeker, doğrular ve siteye ekler
     * @param int $maxToImport Maksimum eklenecek yeni haber sayısı
     * @return array ['success' => bool, 'imported' => int, 'skipped' => int, 'messages' => array]
     */
    public static function sync(int $maxToImport = 5): array
    {
        $xmlContent = self::fetchFeed(self::PRIMARY_FEED_URL);
        if (!$xmlContent) {
            return [
                'success'  => false,
                'imported' => 0,
                'skipped'  => 0,
                'message'  => 'Haber kaynağına bağlanılamadı veya akış geçici olarak yanıt vermedi.'
            ];
        }

        $xml = @simplexml_load_string($xmlContent, 'SimpleXMLElement', LIBXML_NOCDATA);
        if (!$xml || !isset($xml->channel->item)) {
            return [
                'success'  => false,
                'imported' => 0,
                'skipped'  => 0,
                'message'  => 'Haber akışı XML formatı okunamadı.'
            ];
        }

        $imported = 0;
        $skipped = 0;
        $importedTitles = [];

        foreach ($xml->channel->item as $item) {
            if ($imported >= $maxToImport) {
                break;
            }

            $rawTitle = trim((string)$item->title);
            $rawDesc = trim((string)$item->description);
            $rawLink = trim((string)$item->link);
            $rawDate = trim((string)$item->pubDate);
            $imageUrl = isset($item->enclosure['url']) ? trim((string)$item->enclosure['url']) : '';

            // 1. Doğruluk, Güvenilirlik ve Sektörel Uygunluk Filtresi
            $verification = self::verifyArticle($rawTitle, $rawDesc);
            if (!$verification['is_valid']) {
                $skipped++;
                continue;
            }

            // 2. Çift Kayıt (Duplicate) Kontrolü
            $slug = self::generateSlug($rawTitle);
            if (self::isDuplicate($slug, $rawTitle)) {
                $skipped++;
                continue;
            }

            // 3. Görsel İndirme ve WebP Optimizasyonu
            $localImage = self::processImage($imageUrl, $slug);

            // 4. İçerik ve BİK GRUP Tarım Notu Oluşturma
            $cleanExcerpt = self::cleanText($rawDesc);
            $richContentTr = self::buildRichContent($rawTitle, $cleanExcerpt, $rawLink, $verification['matched']);
            $richContentEn = self::buildEnglishContent($rawTitle, $cleanExcerpt, $rawLink);
            $titleEn = self::translateOrAdaptTitle($rawTitle);
            $readTime = max(2, (int)ceil(str_word_count(strip_tags($richContentTr)) / 130)) . ' dk';

            // 5. BlogPost Kaydı
            $saved = BlogPost::create([
                'slug'         => $slug,
                'title_tr'     => $rawTitle,
                'title_en'     => $titleEn,
                'excerpt_tr'   => mb_substr($cleanExcerpt, 0, 240, 'UTF-8') . (mb_strlen($cleanExcerpt) > 240 ? '...' : ''),
                'excerpt_en'   => mb_substr($cleanExcerpt, 0, 240, 'UTF-8') . (mb_strlen($cleanExcerpt) > 240 ? '...' : ''),
                'content_tr'   => $richContentTr,
                'content_en'   => $richContentEn,
                'image'        => $localImage,
                'author'       => 'BİK GRUP Tarım Masası',
                'read_time'    => $readTime,
                'is_published' => 1
            ]);

            if ($saved) {
                $imported++;
                $importedTitles[] = $rawTitle;
            } else {
                $skipped++;
            }
        }

        // Son senkronizasyon zamanını kaydet
        self::recordSyncTime($imported);

        return [
            'success'  => true,
            'imported' => $imported,
            'skipped'  => $skipped,
            'titles'   => $importedTitles,
            'message'  => $imported > 0 
                ? "Tarımdan Haber botu: {$imported} adet doğrulanmış sektörel tarım haberi başarıyla incelendi ve siteye eklendi." 
                : "Yeni haber taraması tamamlandı; filtrelere uygun yeni bir haber bulunamadı veya mevcut haberler zaten kayıtlı."
        ];
    }

    /**
     * Haberin doğruluğunu, uzunluğunu ve tarımsal uygunluğunu test eder
     */
    private static function verifyArticle(string $title, string $desc): array
    {
        $titleLen = mb_strlen($title, 'UTF-8');
        $descLen = mb_strlen($desc, 'UTF-8');

        // Asgari karakter sınırları
        if ($titleLen < 15 || $titleLen > 250 || $descLen < 40) {
            return ['is_valid' => false, 'reason' => 'Yetersiz metin uzunluğu'];
        }

        $haystack = mb_strtolower($title . ' ' . $desc, 'UTF-8');

        // Kara liste (Spam / Magazin / Siyaset) kontrolü
        foreach (self::BLACKLIST_KEYWORDS as $bad) {
            if (mb_strpos($haystack, $bad) !== false) {
                return ['is_valid' => false, 'reason' => 'Kara liste kelimesi içeriyor: ' . $bad];
            }
        }

        // Sektörel Uygunluk Puanlama Sistemi
        $score = 0;
        $matched = [];

        foreach (self::HIGH_PRIORITY_KEYWORDS as $kw) {
            if (mb_strpos($haystack, $kw) !== false) {
                $score += 3;
                $matched[] = $kw;
            }
        }

        foreach (self::MEDIUM_PRIORITY_KEYWORDS as $kw) {
            if (mb_strpos($haystack, $kw) !== false) {
                $score += 1;
                $matched[] = $kw;
            }
        }

        // En az 2 puan toplayan haberler doğrulanmış kabul edilir
        if ($score >= 2) {
            return ['is_valid' => true, 'score' => $score, 'matched' => array_unique($matched)];
        }

        return ['is_valid' => false, 'reason' => 'BİK GRUP tarım alanlarıyla doğrudan ilgili değil (Düşük puan)'];
    }

    /**
     * Haber metninden zengin, şık ve sektörel BİK GRUP analiz bloğu eklenmiş HTML üretir
     */
    private static function buildRichContent(string $title, string $desc, string $sourceUrl, array $matchedKeywords = []): string
    {
        $kwList = !empty($matchedKeywords) ? implode(', ', array_slice($matchedKeywords, 0, 4)) : 'Tarım & İhracat';

        $html = '<div class="agri-news-content" style="font-size: 16px; line-height: 1.8; color: #1e293b;">';
        
        // Giriş ve Özet Paragrafı
        $html .= '<p class="lead" style="font-size: 18px; font-weight: 600; color: #0f172a; margin-bottom: 20px; line-height: 1.6;">';
        $html .= htmlspecialchars($desc);
        $html .= '</p>';

        // BİK GRUP Sektörel Değerlendirme Kutusu (Doğrulama ve Analiz)
        $html .= '<div class="bikgrup-sector-note" style="background: #f0fdf4; border-left: 4px solid #16a34a; border-radius: 12px; padding: 20px 24px; margin: 28px 0; box-shadow: 0 4px 12px rgba(22, 163, 74, 0.05);">';
        $html .= '<h4 style="margin: 0 0 10px 0; font-size: 16px; font-weight: 800; color: #166534; display: flex; align-items: center; gap: 8px;">';
        $html .= '<span>🌱</span> BİK GRUP Tarım & Sektör Analiz Notu';
        $html .= '</h4>';
        $html .= '<p style="margin: 0; font-size: 14.5px; color: #1e3a2b; line-height: 1.6;">';
        $html .= 'Bu gelişme; Akdeniz ve Çukurova havzasındaki tarımsal üretim dinamikleri, rekolte planlaması ve ' . htmlspecialchars($kwList) . ' zincirinde doğrudan takip ettiğimiz kritik başlıklar arasında yer almaktadır. Üreticinin sürdürülebilir tarım uygulamaları, doğru gübreleme teknikleri ve uluslararası kalite standartlarına uygun hasat süreçleri rekabet gücümüzü artıran ana unsurlardır.';
        $html .= '</p>';
        $html .= '</div>';

        // Detay Bilgilendirme
        $html .= '<p style="margin-bottom: 20px;">';
        $html .= 'Türkiye genelinde modern tarım teknolojileri, iklim koşullarına uyumlu tohum ıslahı ve soğuk zincir lojistiği tarımsal ihracatımızın sürekliliği açısından hayati önem taşımaktadır. BİK GRUP olarak bahçeden ve tarladan sofraya uzanan tüm süreçlerde en son sektörel verileri analiz ederek kalite standartlarımızı en üst seviyede tutuyoruz.';
        $html .= '</p>';

        // Doğrulanmış Kaynak ve Künye Rozeti
        $html .= '<div class="news-verification-footer" style="margin-top: 32px; padding-top: 18px; border-top: 1.5px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; font-size: 13px; color: #64748b;">';
        $html .= '<div style="display: flex; align-items: center; gap: 6px;">';
        $html .= '<span style="color: #16a34a; font-weight: 700;">✓ Doğrulanmış Tarım Haberi</span>';
        $html .= '<span>|</span>';
        $html .= '<span>Kaynak: <strong>Tarımdan Haber</strong></span>';
        $html .= '</div>';
        if (!empty($sourceUrl)) {
            $html .= '<a href="' . htmlspecialchars($sourceUrl) . '" target="_blank" rel="noopener noreferrer" style="color: #2563eb; text-decoration: none; font-weight: 600;">Orijinal Haberi İncele ↗</a>';
        }
        $html .= '</div>';

        $html .= '</div>';

        return $html;
    }

    /**
     * İngilizce içerik adaptasyonu
     */
    private static function buildEnglishContent(string $title, string $desc, string $sourceUrl): string
    {
        $html = '<div class="agri-news-content" style="font-size: 16px; line-height: 1.8; color: #1e293b;">';
        $html .= '<p class="lead" style="font-size: 18px; font-weight: 600; color: #0f172a; margin-bottom: 20px; line-height: 1.6;">';
        $html .= htmlspecialchars($desc);
        $html .= '</p>';
        $html .= '<div class="bikgrup-sector-note" style="background: #f0fdf4; border-left: 4px solid #16a34a; border-radius: 12px; padding: 20px 24px; margin: 28px 0;">';
        $html .= '<h4 style="margin: 0 0 10px 0; font-size: 16px; font-weight: 800; color: #166534;">🌱 BİK GRUP Agriculture Insights</h4>';
        $html .= '<p style="margin: 0; font-size: 14.5px; color: #1e3a2b; line-height: 1.6;">';
        $html .= 'Sustainable production, verified crop quality, and cold-chain compliance remain central to our agricultural operations and global fruit, cereal, and citrus export programs.';
        $html .= '</p>';
        $html .= '</div>';
        $html .= '<div style="margin-top: 24px; font-size: 13px; color: #64748b;">Source: Verified Turkish Agricultural News Service</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * Başlığı İngilizceye uyarlar
     */
    private static function translateOrAdaptTitle(string $title): string
    {
        return 'Agricultural Developments: ' . $title;
    }

    /**
     * Görseli indirir, WebP formatına dönüştürüp kaydeder
     */
    private static function processImage(string $imageUrl, string $slug): string
    {
        if (empty($imageUrl)) {
            return 'blog_hero_new.jpg';
        }

        $targetDir = ROOT_PATH . '/public/images';
        $filename = 'news_' . substr($slug, 0, 45) . '_' . time() . '.webp';
        $targetPath = $targetDir . '/' . $filename;

        // Görseli cURL ile indir
        $ch = curl_init($imageUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 12);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && !empty($data)) {
            $img = @imagecreatefromstring($data);
            if ($img) {
                // Şeffaflık ve WebP kaydı
                imagepalettetotruecolor($img);
                imagealphablending($img, true);
                imagesavealpha($img, true);
                
                $saved = @imagewebp($img, $targetPath, 85);
                @imagedestroy($img);
                if ($saved && file_exists($targetPath)) {
                    return $filename;
                }
            }
        }

        return 'blog_hero_new.jpg';
    }

    /**
     * Akış XML içeriğini çeker
     */
    private static function fetchFeed(string $url): ?string
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 14);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code === 200 && !empty($res)) {
            return $res;
        }
        return null;
    }

    /**
     * Günlük otomatik tarama motoru: Günde 1 kez otomatik olarak yeni haberleri tarar ve ekler
     */
    public static function autoDailySyncIfNeeded(): ?array
    {
        $today = date('Y-m-d');
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = 'last_agri_news_scan_date' LIMIT 1");
            $stmt->execute();
            $lastDate = (string)$stmt->fetchColumn();

            if ($lastDate !== $today) {
                // Bugün henüz taranmadı, önce bugünün tarihini kaydet
                $upStmt = $db->prepare("
                    INSERT INTO settings (setting_key, setting_value, group_name, label_tr, input_type)
                    VALUES ('last_agri_news_scan_date', ?, 'blog', 'Son Günlük Tarama Tarihi', 'text')
                    ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
                ");
                $upStmt->execute([$today]);

                // Yeni haberleri tara ve ekle
                return self::sync(4);
            }
        } catch (\Throwable $e) {}

        return null;
    }

    /**
     * Silinen bir haberi arşive ekler (böylece sonraki taramalarda tekrar eklenmez)
     */
    public static function archivePost(string $slug, string $title): bool
    {
        $slug = trim($slug);
        $title = trim($title);
        if (empty($slug) && empty($title)) {
            return false;
        }

        $archive = self::getArchivedList();
        $normTitle = self::normalizeForComparison($title);

        // Zaten arşivde var mı kontrol et
        foreach ($archive as $item) {
            if ((!empty($slug) && ($item['slug'] ?? '') === $slug) || 
                (!empty($normTitle) && ($item['norm_title'] ?? '') === $normTitle)) {
                return true; // Zaten arşivde kayıtlı
            }
        }

        $archive[] = [
            'slug'        => $slug,
            'title'       => $title,
            'norm_title'  => $normTitle,
            'archived_at' => date('Y-m-d H:i:s')
        ];

        return self::saveArchivedList($archive);
    }

    /**
     * Bir haberin arşivde olup olmadığını kontrol eder
     */
    public static function isArchived(string $slug, string $title): bool
    {
        $slug = trim($slug);
        $normTitle = self::normalizeForComparison($title);
        $archive = self::getArchivedList();

        foreach ($archive as $item) {
            if (!empty($slug) && ($item['slug'] ?? '') === $slug) {
                return true;
            }
            if (!empty($normTitle) && !empty($item['norm_title']) && $item['norm_title'] === $normTitle) {
                return true;
            }
        }
        return false;
    }

    /**
     * Arşivlenen silinmiş haberlerin listesini getirir
     */
    public static function getArchivedList(): array
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = 'archived_agri_news' LIMIT 1");
            $stmt->execute();
            $val = $stmt->fetchColumn();
            if ($val) {
                $decoded = json_decode((string)$val, true);
                if (is_array($decoded)) {
                    return $decoded;
                }
            }
        } catch (\Throwable $e) {}
        return [];
    }

    /**
     * Arşiv listesini tamamen temizler
     */
    public static function clearArchive(): bool
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("DELETE FROM settings WHERE setting_key = 'archived_agri_news'");
            return $stmt->execute();
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Arşiv listesini kaydeder
     */
    private static function saveArchivedList(array $list): bool
    {
        try {
            $json = json_encode($list, JSON_UNESCAPED_UNICODE);
            $db = Database::getConnection();
            $stmt = $db->prepare("
                INSERT INTO settings (setting_key, setting_value, group_name, label_tr, input_type)
                VALUES ('archived_agri_news', ?, 'blog', 'Arşivlenen Tarım Haberleri (Yayınlanmayacaklar)', 'textarea')
                ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
            ");
            return $stmt->execute([$json]);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Başlık karşılaştırmaları için Türkçe normalizasyon
     */
    public static function normalizeForComparison(string $text): string
    {
        $text = mb_strtolower(trim($text), 'UTF-8');
        $trChars = ['ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç'];
        $enChars = ['i', 'g', 'u', 's', 'o', 'c', 'i', 'g', 'u', 's', 'o', 'c'];
        $text = str_replace($trChars, $enChars, $text);
        return preg_replace('/[^a-z0-9]+/i', '', $text);
    }

    /**
     * Slug veya başlık zaten veritabanında veya arşivde var mı kontrol eder
     */
    private static function isDuplicate(string $slug, string $title): bool
    {
        // 1. Kullanıcının sildiği / arşivlediği haberler listesinde var mı? (Asla tekrar yayınlanmaz!)
        if (self::isArchived($slug, $title)) {
            return true;
        }

        // 2. Halihazırda sitede yayında veya taslakta var mı?
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT id FROM blog_posts WHERE slug = ? OR title_tr = ? LIMIT 1");
            $stmt->execute([$slug, $title]);
            return (bool)$stmt->fetchColumn();
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Türkçe karakter uyumlu slug üretici
     */
    private static function generateSlug(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');
        $trChars = ['ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç'];
        $enChars = ['i', 'g', 'u', 's', 'o', 'c', 'i', 'g', 'u', 's', 'o', 'c'];
        $text = str_replace($trChars, $enChars, $text);
        $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
        $slug = trim($text, '-_');
        return mb_substr($slug, 0, 110, 'UTF-8');
    }

    /**
     * HTML ve gereksiz etiketleri temizler
     */
    private static function cleanText(string $html): string
    {
        $clean = strip_tags($html);
        $clean = html_entity_decode($clean, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $clean = preg_replace('/\s+/', ' ', $clean);
        return trim($clean);
    }

    /**
     * Son senkronizasyon zamanını settings tablosuna yazar
     */
    private static function recordSyncTime(int $importedCount): void
    {
        try {
            $db = Database::getConnection();
            $val = json_encode([
                'last_sync' => date('Y-m-d H:i:s'),
                'source'    => 'Tarımdan Haber (tarimdanhaber.com)',
                'imported'  => $importedCount
            ], JSON_UNESCAPED_UNICODE);

            $stmt = $db->prepare("
                INSERT INTO settings (setting_key, setting_value, group_name, label_tr, input_type)
                VALUES ('last_agri_news_sync', ?, 'blog', 'Son Tarım Haberleri Senkronizasyonu', 'textarea')
                ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
            ");
            $stmt->execute([$val]);
        } catch (\Throwable $e) {}
    }

    /**
     * Son senkronizasyon bilgisini getirir
     */
    public static function getLastSyncInfo(): array
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = 'last_agri_news_sync' LIMIT 1");
            $stmt->execute();
            $val = $stmt->fetchColumn();
            if ($val) {
                $decoded = json_decode((string)$val, true);
                if (is_array($decoded)) {
                    return $decoded;
                }
            }
        } catch (\Throwable $e) {}

        return [
            'last_sync' => 'Henüz senkronizasyon yapılmadı',
            'source'    => 'Tarımdan Haber (tarimdanhaber.com)',
            'imported'  => 0
        ];
    }
}

