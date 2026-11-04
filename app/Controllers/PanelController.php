<?php
/**
 * BİKGRUP - Gizli Yönetim Paneli (xpanel) Kontrolcüsü
 * PHP 8.2 / 8.3 / 8.4 Uyumlu
 */

declare(strict_types=1);

require_once ROOT_PATH . '/core/View.php';
require_once ROOT_PATH . '/core/Auth.php';
require_once ROOT_PATH . '/core/Security.php';
require_once ROOT_PATH . '/core/Settings.php';
require_once ROOT_PATH . '/app/Models/Product.php';
require_once ROOT_PATH . '/app/Models/Category.php';
require_once ROOT_PATH . '/app/Models/BlogPost.php';
require_once ROOT_PATH . '/app/Models/PageSection.php';
require_once ROOT_PATH . '/app/Models/Message.php';
require_once ROOT_PATH . '/app/Models/User.php';
require_once ROOT_PATH . '/app/Models/QuoteRequest.php';
require_once ROOT_PATH . '/core/ImageUploader.php';

class PanelController
{
    /**
     * Oturum kontrolü (Giriş yapılmamışsa login ekranına yönlendirir)
     */
    private function requireAuth(): void
    {
        if (!Auth::check()) {
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/login");
            exit;
        }
    }

    /**
     * Giriş Ekranı
     */
    public function login(): void
    {
        if (Auth::check()) {
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG);
            exit;
        }

        View::renderPanel('login', [
            'pageTitle' => 'BİK GRUP | Yönetim Paneli Girişi'
        ], false);
    }

    /**
     * Giriş İşlemi
     */
    public function doLogin(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/login");
            exit;
        }

        if (!Security::validateCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['panel_error'] = 'Güvenlik doğrulaması (CSRF) başarısız oldu!';
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/login");
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $result = Auth::login($email, $password);

        if ($result['success']) {
            $_SESSION['panel_success'] = 'Hoş geldiniz, ' . htmlspecialchars(Auth::user()['name']);
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG);
            exit;
        } else {
            $_SESSION['panel_error'] = $result['message'];
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/login");
            exit;
        }
    }

    /**
     * Çıkış Yap
     */
    public function logout(): void
    {
        Auth::logout();
        header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/login");
        exit;
    }

    /**
     * Dashboard (Ana Panel)
     */
    public function dashboard(): void
    {
        $this->requireAuth();

        $stats = [
            'product_count'   => count(Product::all(false)),
            'category_count'  => count(Category::all(false)),
            'blog_count'      => count(BlogPost::all(false)),
            'unread_messages' => Message::unreadCount(),
            'user_count'      => count(User::all()),
            'pending_quotes'  => class_exists('QuoteRequest') ? QuoteRequest::pendingCount() : 0,
            'total_quotes'    => class_exists('QuoteRequest') ? count(QuoteRequest::all()) : 0
        ];

        $recentMessages = array_slice(Message::all(), 0, 5);
        $recentProducts = array_slice(Product::all(false), 0, 5);
        $recentQuotes   = class_exists('QuoteRequest') ? array_slice(QuoteRequest::all(), 0, 5) : [];

        View::renderPanel('dashboard', [
            'pageTitle'      => 'BİK GRUP | Genel Bakış & Dashboard',
            'activeMenu'     => 'dashboard',
            'stats'          => $stats,
            'recentMessages' => $recentMessages,
            'recentProducts' => $recentProducts,
            'recentQuotes'   => $recentQuotes
        ]);
    }

    /**
     * Dinamik Tema ve Canlı Renk Yöneticisi
     */
    public function theme(): void
    {
        $this->requireAuth();

        $db = Database::getConnection();
        $themeSettings = $db->query("SELECT * FROM settings WHERE group_name = 'theme' ORDER BY id ASC")->fetchAll();

        View::renderPanel('theme', [
            'pageTitle'     => 'BİK GRUP | Dinamik Tema & Renk Yönetimi',
            'activeMenu'    => 'theme',
            'themeSettings' => $themeSettings
        ]);
    }

    /**
     * Tema Renklerini Kaydet
     */
    public function saveTheme(): void
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['panel_error'] = 'Güvenlik doğrulaması hatası!';
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/theme");
            exit;
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ? AND group_name = 'theme'");

        foreach ($_POST as $key => $value) {
            if (str_starts_with($key, 'theme_')) {
                $stmt->execute([trim($value), $key]);
            }
        }

        $_SESSION['panel_success'] = 'Site renkleri ve tema ayarları başarıyla güncellendi! Değişiklikler anında sitede aktif.';
        header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/theme");
        exit;
    }

    /**
     * Tema Renklerini Fabrika Varsayılanlarına Sıfırla
     */
    public function resetTheme(): void
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['panel_error'] = 'Güvenlik doğrulaması hatası!';
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/theme");
            exit;
        }

        $defaults = [
            'theme_primary'         => '#15803d',
            'theme_primary_hover'   => '#166534',
            'theme_secondary'       => '#ea580c',
            'theme_secondary_hover' => '#c2410c',
            'theme_accent'          => '#059669',
            'theme_bg'              => '#fcfdfd',
            'theme_card_bg'         => '#ffffff',
            'theme_text'            => '#0f172a',
            'theme_text_muted'      => '#64748b',
            'theme_navbar_bg'       => 'rgba(255, 255, 255, 0.94)',
            'theme_border_radius'   => '20px',
        ];

        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ? AND group_name = 'theme'");

        foreach ($defaults as $key => $val) {
            $stmt->execute([$val, $key]);
        }

        $_SESSION['panel_success'] = 'Tüm tema renkleri ve ölçüleri başarıyla sitenin orijinal varsayılan tonlarına sıfırlandı!';
        header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/theme");
        exit;
    }

    /**
     * Genel Site Ayarları (Logo metni, telefon, adres vb.)
     */
    public function settings(): void
    {
        $this->requireAuth();

        $db = Database::getConnection();
        $generalSettings = $db->query("SELECT * FROM settings WHERE group_name != 'theme' ORDER BY group_name ASC, id ASC")->fetchAll();

        View::renderPanel('settings', [
            'pageTitle'       => 'BİK GRUP | Genel Site Ayarları & İletişim',
            'activeMenu'      => 'settings',
            'generalSettings' => $generalSettings
        ]);
    }

    /**
     * Genel Ayarları Kaydet
     */
    public function saveSettings(): void
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['panel_error'] = 'Güvenlik doğrulaması hatası!';
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/settings");
            exit;
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value, group_name) 
                              VALUES (?, ?, 'general') 
                              ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");

        // 1. Logo Dosyası Yükleme
        if (!empty($_FILES['site_logo']['name']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
            $logoName = ImageUploader::uploadAndConvertToWebP($_FILES['site_logo'], UPLOADS_PATH, 'logo');
            if ($logoName) {
                $stmt->execute(['site_logo', $logoName]);
            }
        }

        // 2. Favicon Dosyası Yükleme
        if (!empty($_FILES['site_favicon']['name']) && $_FILES['site_favicon']['error'] === UPLOAD_ERR_OK) {
            $favName = ImageUploader::uploadAndConvertToWebP($_FILES['site_favicon'], UPLOADS_PATH, 'favicon');
            if ($favName) {
                $stmt->execute(['site_favicon', $favName]);
            }
        }

        // 3. Metin Alanlarını Kaydet
        foreach ($_POST as $key => $value) {
            if ($key !== 'csrf_token' && is_string($value)) {
                $stmt->execute([$key, trim($value)]);
            }
        }

        // 4. Otomatik İngilizce Çeviriler (Slogan & Adres)
        if (!empty($_POST['site_tagline_tr'])) {
            $taglineEn = AutoTranslator::translate($_POST['site_tagline_tr'], 'tr', 'en');
            $stmt->execute(['site_tagline_en', $taglineEn]);
        }
        if (!empty($_POST['site_address_tr'])) {
            $addressEn = AutoTranslator::translate($_POST['site_address_tr'], 'tr', 'en');
            $stmt->execute(['site_address_en', $addressEn]);
        }

        $_SESSION['panel_success'] = 'Genel site ayarları ve görseller başarıyla güncellendi.';
        header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/settings");
        exit;
    }

    /**
     * Canlı E-Posta / SMTP Testi
     */
    public function testEmail(): void
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['panel_error'] = 'Güvenlik doğrulaması hatası!';
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/settings");
            exit;
        }

        $testRecipient = trim($_POST['test_email'] ?? Settings::get('quote_notification_email', 'destek@bikgrup.com'));
        if (!filter_var($testRecipient, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['panel_error'] = 'Lütfen geçerli bir test e-posta adresi giriniz.';
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/settings");
            exit;
        }

        require_once ROOT_PATH . '/core/Mailer.php';
        $subject = 'BİK GRUP - Canlı E-Posta Test Bildirimi';
        $body = '<div style="font-family:sans-serif;padding:24px;background:#f8fafc;border-radius:14px;border:1px solid #e2e8f0;max-width:550px;margin:20px auto;">
            <div style="background:#15803d;color:#ffffff;padding:12px 18px;border-radius:10px;font-weight:700;font-size:18px;margin-bottom:16px;">
                🌿 BİK GRUP | E-Posta Sistemi Çalışıyor!
            </div>
            <p style="color:#0f172a;font-size:15px;line-height:1.6;">
                Tebrikler! Hostinger SMTP e-posta sunucusu bağlantınız başarıyla sağlandı ve web sitesi bildirim motoru kusursuz çalışıyor.
            </p>
            <div style="background:#ffffff;border:1px solid #e2e8f0;padding:12px 16px;border-radius:8px;font-size:13px;color:#64748b;margin:16px 0;">
                <div><strong>Giden Sunucu:</strong> ' . htmlspecialchars(Settings::get('smtp_host', 'smtp.hostinger.com')) . '</div>
                <div><strong>Port / Şifreleme:</strong> ' . htmlspecialchars(Settings::get('smtp_port', '465')) . ' (' . htmlspecialchars(Settings::get('smtp_secure', 'ssl')) . ')</div>
                <div><strong>Gönderici:</strong> ' . htmlspecialchars(Settings::get('smtp_user', 'destek@bikgrup.com')) . '</div>
                <div><strong>Test Tarihi:</strong> ' . date('d.m.Y H:i:s') . '</div>
            </div>
            <p style="color:#94a3b8;font-size:12px;margin-bottom:0;">Bu e-posta BİK GRUP Yönetim Paneli üzerinden tetiklenmiştir.</p>
        </div>';

        $res = Mailer::send($testRecipient, $subject, $body);

        if ($res['success']) {
            $_SESSION['panel_success'] = 'Harika! Test e-postası "' . htmlspecialchars($testRecipient) . '" adresine başarıyla gönderildi.';
        } else {
            $_SESSION['panel_error'] = 'E-Posta gönderilemedi: ' . htmlspecialchars($res['message']);
        }

        header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/settings");
        exit;
    }

    /**
     * Footer (Altbilgi) Yönetimi
     */
    public function footer(): void
    {
        $this->requireAuth();

        $footer = PageSection::get('footer_main') ?? [];
        $footerExtra = $footer['extra'] ?? (!empty($footer['extra_json']) ? json_decode($footer['extra_json'], true) : []);

        View::renderPanel('footer', [
            'pageTitle'   => 'BİK GRUP | Footer (Altbilgi) Yönetimi',
            'activeMenu'  => 'footer',
            'footer'      => $footer,
            'footerExtra' => $footerExtra
        ]);
    }

    /**
     * Footer Değişikliklerini Kaydet
     */
    public function saveFooter(): void
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['panel_error'] = 'Güvenlik hatası!';
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/footer");
            exit;
        }

        $qTitle = trim($_POST['quick_links_title'] ?? 'Hızlı Bağlantılar');
        $pTitle = trim($_POST['products_title'] ?? 'Ürünlerimiz');
        $polTitle = trim($_POST['policies_title'] ?? 'Politikalar');
        $cTitle = trim($_POST['contact_title'] ?? 'İletişim & Lokasyon');
        $crText = trim($_POST['copyright_text'] ?? 'Tüm hakları saklıdır.');

        $titleTr = trim($_POST['title_tr'] ?? 'BİK GRUP');
        $subtitleTr = trim($_POST['subtitle_tr'] ?? '');
        $bodyTr = trim($_POST['body_tr'] ?? '');

        $extra = [
            'quick_links_title'    => $qTitle,
            'quick_links_title_en' => AutoTranslator::translate($qTitle, 'tr', 'en'),
            'products_title'       => $pTitle,
            'products_title_en'    => AutoTranslator::translate($pTitle, 'tr', 'en'),
            'policies_title'       => $polTitle,
            'policies_title_en'    => AutoTranslator::translate($polTitle, 'tr', 'en'),
            'contact_title'        => $cTitle,
            'contact_title_en'     => AutoTranslator::translate($cTitle, 'tr', 'en'),
            'copyright_text'       => $crText,
            'copyright_text_en'    => AutoTranslator::translate($crText, 'tr', 'en')
        ];

        $data = [
            'title_tr'    => $titleTr,
            'title_en'    => AutoTranslator::translate($titleTr, 'tr', 'en'),
            'subtitle_tr' => $subtitleTr,
            'subtitle_en' => AutoTranslator::translate($subtitleTr, 'tr', 'en'),
            'body_tr'     => $bodyTr,
            'body_en'     => AutoTranslator::translate($bodyTr, 'tr', 'en'),
            'extra'       => $extra
        ];

        PageSection::set('footer', 'footer_main', $data);

        $_SESSION['panel_success'] = 'Footer (Altbilgi) içerikleri başarıyla güncellendi.';
        header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/footer");
        exit;
    }

    /**
     * Dinamik Sayfa Bölümleri Yönetimi (Hero, Hakkımızda vb.)
     */
    public function pages(): void
    {
        $this->requireAuth();

        $sections = PageSection::all();
        $categories = Category::all(true);
        $allProducts = Product::all(true);

        View::renderPanel('pages', [
            'pageTitle'   => 'BİK GRUP | Dinamik Sayfa ve Bölüm Yönetimi',
            'activeMenu'  => 'pages',
            'sections'    => $sections,
            'categories'  => $categories,
            'allProducts' => $allProducts,
            'posts'       => BlogPost::all(false)
        ]);
    }

    /**
     * Sayfa Bölümü Güncelle
     */
    public function savePageSection(): void
    {
        $this->requireAuth();

        $pageKey = trim($_POST['page_key'] ?? 'home');
        $sectionKey = trim($_POST['section_key'] ?? '');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['panel_error'] = 'Güvenlik hatası!';
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/pages&page=" . urlencode($pageKey));
            exit;
        }

        // Mevcut bölüm verisi
        $existing = PageSection::get($sectionKey);
        $existingExtra = $existing['extra'] ?? [];

        // Gelen ekstra alanları al
        $extra = $_POST['extra'] ?? [];
        if (!is_array($extra)) {
            $extra = [];
        }
        $extra = array_merge($existingExtra, $extra);

        // Dosya yüklemelerini kontrol et (bg_image, brand1_image, brand2_image, brand3_image, brand4_image vb.)
        if (!empty($_FILES)) {
            foreach ($_FILES as $fileKey => $fileData) {
                if (!empty($fileData['name']) && $fileData['error'] === UPLOAD_ERR_OK) {
                    $uploadedFile = ImageUploader::uploadAndConvertToWebP($fileData, UPLOADS_PATH, $fileKey);
                    if ($uploadedFile) {
                        $extra[$fileKey] = $uploadedFile;
                    }
                }
            }
        }

        // KULLANICI TALEBİ: Referans Logo Alanı Özel Yönetimi
        if ($sectionKey === 'home_references') {
            $items = $_POST['ref_items'] ?? ($existingExtra['items'] ?? []);
            if (!empty($_FILES)) {
                foreach ($_FILES as $fKey => $fData) {
                    if (str_starts_with($fKey, 'ref_logo_') && !empty($fData['name']) && $fData['error'] === UPLOAD_ERR_OK) {
                        $idx = (int)str_replace('ref_logo_', '', $fKey);
                        $uploaded = ImageUploader::uploadAndConvertToWebP($fData, UPLOADS_PATH . '/references', 'ref_' . $idx);
                        if ($uploaded && isset($items[$idx])) {
                            $items[$idx]['image'] = 'references/' . $uploaded;
                        }
                    }
                }
            }
            if (!empty($_POST['new_ref_name']) && !empty($_FILES['new_ref_logo']['name']) && $_FILES['new_ref_logo']['error'] === UPLOAD_ERR_OK) {
                $newLogo = ImageUploader::uploadAndConvertToWebP($_FILES['new_ref_logo'], UPLOADS_PATH . '/references', 'ref_custom_' . time());
                if ($newLogo) {
                    $items[] = [
                        'id' => count($items) + 1,
                        'name' => Security::clean($_POST['new_ref_name']),
                        'image' => 'references/' . $newLogo,
                        'link' => '#'
                    ];
                }
            }
            $extra['items'] = array_values($items);
        }

        // Extra alanlarındaki metinlerin de otomatik İngilizce karşılıklarını oluştur
        foreach ($extra as $exKey => $exVal) {
            if (is_string($exVal) && $exVal !== '' && !str_ends_with($exKey, '_en') && !str_ends_with($exKey, '_image') && !str_ends_with($exKey, '_link') && !str_starts_with($exKey, 'product_id_')) {
                if (str_contains($exKey, 'title') || str_contains($exKey, 'desc') || str_contains($exKey, 'btn') || str_contains($exKey, 'text') || str_starts_with($exKey, 'capsule') || str_contains($exKey, 'name')) {
                    $extra[$exKey . '_en'] = AutoTranslator::translate($exVal, 'tr', 'en');
                }
            }
        }

        $titleTr = trim($_POST['title_tr'] ?? ($existing['title_tr'] ?? ''));
        $subtitleTr = trim($_POST['subtitle_tr'] ?? ($existing['subtitle_tr'] ?? ''));
        $bodyTr = trim($_POST['body_tr'] ?? ($existing['body_tr'] ?? ''));

        $data = [
            'title_tr'    => $titleTr,
            'title_en'    => !empty($titleTr) ? AutoTranslator::translate($titleTr, 'tr', 'en') : '',
            'subtitle_tr' => $subtitleTr,
            'subtitle_en' => !empty($subtitleTr) ? AutoTranslator::translate($subtitleTr, 'tr', 'en') : '',
            'body_tr'     => $bodyTr,
            'body_en'     => !empty($bodyTr) ? AutoTranslator::translate($bodyTr, 'tr', 'en') : '',
            'extra'       => $extra
        ];

        PageSection::set($pageKey, $sectionKey, $data);

        $_SESSION['panel_success'] = 'Bölüm içeriği ve görseller başarıyla güncellendi.';
        $redirectTo = !empty($_POST['redirect_to']) ? trim($_POST['redirect_to']) : (PANEL_SLUG . "/pages&page=" . urlencode($pageKey));
        header("Location: " . BASE_URL . "/index.php?url=" . $redirectTo);
        exit;
    }

    /**
     * Ürünler Yönetimi (Liste ve Sayfa İçi Detay Düzenleme Ekranı)
     */
    public function products(): void
    {
        $this->requireAuth();

        $action = $_GET['action'] ?? 'list';
        $editId = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        $editingProduct = null;

        if ($action === 'edit' && $editId) {
            $editingProduct = Product::find($editId);
            if (!$editingProduct) {
                $_SESSION['panel_error'] = 'Düzenlenmek istenen ürün bulunamadı.';
                header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/products");
                exit;
            }
        }

        $products = Product::all(false);
        $categories = Category::all(false);

        $pageTitle = 'BİK GRUP | Ürün Yönetimi';
        if ($action === 'edit' && $editingProduct) {
            $pageTitle = 'BİK GRUP | ' . ($editingProduct['name_tr'] ?? 'Ürün') . ' Düzenle';
        } elseif ($action === 'new') {
            $pageTitle = 'BİK GRUP | Yeni Ürün Ekle';
        }

        View::renderPanel('products', [
            'pageTitle'      => $pageTitle,
            'activeMenu'     => 'products',
            'action'         => $action,
            'editingProduct' => $editingProduct,
            'products'       => $products,
            'categories'     => $categories
        ]);
    }

    /**
     * Ürün Kaydet (Ekle / Güncelle)
     */
    public function saveProduct(): void
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['panel_error'] = 'Güvenlik hatası!';
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/products");
            exit;
        }

        $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;

        $slug = trim($_POST['slug'] ?? '');
        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['name_tr'] ?? 'urun')));
        }

        // Manuel Görsel Yükleme ve Otomatik WebP Dönüştürme
        $imageName = trim($_POST['image'] ?? 'product_riored.png');
        if (!empty($_FILES['image_file']['tmp_name'])) {
            $uploadedWebp = ImageUploader::uploadAndConvertToWebP(
                $_FILES['image_file'],
                ROOT_PATH . '/public/images',
                'prod_' . $slug
            );
            if ($uploadedWebp) {
                $imageName = $uploadedWebp;
            }
        }

        // Kutu ve Ambalaj Görsellerini Yükle (Kutu 1'den 5'e)
        $extra = is_array($_POST['extra'] ?? null) ? $_POST['extra'] : [];
        for ($b = 1; $b <= 5; $b++) {
            $boxFileKey = 'box' . $b . '_image_file';
            if (!empty($_FILES[$boxFileKey]['tmp_name']) && $_FILES[$boxFileKey]['error'] === UPLOAD_ERR_OK) {
                $uploadedBox = ImageUploader::uploadAndConvertToWebP(
                    $_FILES[$boxFileKey],
                    ROOT_PATH . '/public/images',
                    'pkg_' . $slug . '_' . $b
                );
                if ($uploadedBox) {
                    $extra['box' . $b . '_image'] = $uploadedBox;
                }
            }
        }

        // Cinsler & Çeşitler (Varieties) Temizleme ve Formatlama
        if (!empty($extra['varieties']) && is_array($extra['varieties'])) {
            $cleanVarieties = [];
            foreach ($extra['varieties'] as $v) {
                $vName = trim($v['name'] ?? '');
                if ($vName !== '') {
                    $cleanVarieties[] = [
                        'name'      => $vName,
                        'desc'      => trim($v['desc'] ?? ''),
                        'months'    => trim($v['months'] ?? ''),
                        'char_note' => trim($v['char_note'] ?? ''),
                        'nutrition' => [
                            'kcal'    => trim($v['nutrition']['kcal'] ?? ''),
                            'fat'     => trim($v['nutrition']['fat'] ?? ''),
                            'carb'    => trim($v['nutrition']['carb'] ?? ''),
                            'protein' => trim($v['nutrition']['protein'] ?? '')
                        ],
                        'char'      => [
                            'et'       => trim($v['char']['et'] ?? ''),
                            'cekirdek' => trim($v['char']['cekirdek'] ?? ''),
                            'kabuk'    => trim($v['char']['kabuk'] ?? ''),
                            'doku'     => trim($v['char']['doku'] ?? ''),
                            'sekil'    => trim($v['char']['sekil'] ?? ''),
                            'tat'      => trim($v['char']['tat'] ?? '')
                        ]
                    ];
                }
            }
            $extra['varieties'] = $cleanVarieties;
        }

        $nameTr = trim($_POST['name_tr'] ?? '');
        $shortDescTr = trim($_POST['short_desc_tr'] ?? '');
        $contentTr = trim($_POST['content_tr'] ?? '');
        $originTr = trim($_POST['origin_tr'] ?? 'Akdeniz & Ege / Türkiye');
        $harvestSeasonTr = trim($_POST['harvest_season_tr'] ?? '');
        $packagingTr = trim($_POST['packaging_tr'] ?? '');

        $data = [
            'category_id'       => (int)($_POST['category_id'] ?? 1),
            'slug'              => $slug,
            'name_tr'           => $nameTr,
            'name_en'           => !empty($nameTr) ? AutoTranslator::translate($nameTr, 'tr', 'en') : '',
            'short_desc_tr'     => $shortDescTr,
            'short_desc_en'     => !empty($shortDescTr) ? AutoTranslator::translate($shortDescTr, 'tr', 'en') : '',
            'content_tr'        => $contentTr,
            'content_en'        => !empty($contentTr) ? AutoTranslator::translate($contentTr, 'tr', 'en') : '',
            'image'             => $imageName,
            'origin_tr'         => $originTr,
            'origin_en'         => !empty($originTr) ? AutoTranslator::translate($originTr, 'tr', 'en') : 'Mediterranean & Aegean / Turkey',
            'harvest_season_tr' => $harvestSeasonTr,
            'harvest_season_en' => !empty($harvestSeasonTr) ? AutoTranslator::translate($harvestSeasonTr, 'tr', 'en') : '',
            'packaging_tr'      => $packagingTr,
            'packaging_en'      => !empty($packagingTr) ? AutoTranslator::translate($packagingTr, 'tr', 'en') : '',
            'temperature'       => trim($_POST['temperature'] ?? '+4°C / +8°C'),
            'is_featured'       => isset($_POST['is_featured']) ? 1 : 0,
            'is_active'         => isset($_POST['is_active']) ? 1 : 0,
            'sort_order'        => (int)($_POST['sort_order'] ?? 0),
            'extra'             => $extra
        ];

        if ($id) {
            Product::update($id, $data);
            $_SESSION['panel_success'] = 'Ürün ve detay özellikleri başarıyla güncellendi.';
            $targetId = $id;
        } else {
            $targetId = Product::create($data);
            $_SESSION['panel_success'] = 'Yeni ürün başarıyla eklendi.';
        }

        $redirectTo = !empty($_POST['redirect_to']) ? trim($_POST['redirect_to']) : (PANEL_SLUG . "/products");
        if ($redirectTo === 'stay' && $targetId) {
            $redirectTo = PANEL_SLUG . "/products&action=edit&id=" . $targetId;
        }

        header("Location: " . BASE_URL . "/index.php?url=" . $redirectTo);
        exit;
    }

    /**
     * Ürün Sil
     */
    public function deleteProduct(): void
    {
        $this->requireAuth();
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            Product::delete($id);
            $_SESSION['panel_success'] = 'Ürün silindi.';
        }
        header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/products");
        exit;
    }

    /**
     * Kategori Kaydet (Ekle / Güncelle)
     */
    public function saveCategory(): void
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['panel_error'] = 'Güvenlik doğrulaması başarısız!';
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/products");
            exit;
        }

        $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
        // Cinsler & Çeşitler (Varieties) Temizleme ve Formatlama
        if (!empty($extra['varieties']) && is_array($extra['varieties'])) {
            $cleanVarieties = [];
            foreach ($extra['varieties'] as $v) {
                $vName = trim($v['name'] ?? '');
                if ($vName !== '') {
                    $cleanVarieties[] = [
                        'name'      => $vName,
                        'desc'      => trim($v['desc'] ?? ''),
                        'months'    => trim($v['months'] ?? ''),
                        'char_note' => trim($v['char_note'] ?? ''),
                        'nutrition' => [
                            'kcal'    => trim($v['nutrition']['kcal'] ?? ''),
                            'fat'     => trim($v['nutrition']['fat'] ?? ''),
                            'carb'    => trim($v['nutrition']['carb'] ?? ''),
                            'protein' => trim($v['nutrition']['protein'] ?? '')
                        ],
                        'char'      => [
                            'et'       => trim($v['char']['et'] ?? ''),
                            'cekirdek' => trim($v['char']['cekirdek'] ?? ''),
                            'kabuk'    => trim($v['char']['kabuk'] ?? ''),
                            'doku'     => trim($v['char']['doku'] ?? ''),
                            'sekil'    => trim($v['char']['sekil'] ?? ''),
                            'tat'      => trim($v['char']['tat'] ?? '')
                        ]
                    ];
                }
            }
            $extra['varieties'] = $cleanVarieties;
        }

        $nameTr = trim($_POST['name_tr'] ?? '');

        if (empty($nameTr)) {
            $_SESSION['panel_error'] = 'Kategori adı boş bırakılamaz!';
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/products");
            exit;
        }

        $slug = trim($_POST['slug'] ?? '');
        if (empty($slug)) {
            // Slug üretici
            $trMap = ['ç'=>'c', 'ğ'=>'g', 'ı'=>'i', 'ö'=>'o', 'ş'=>'s', 'ü'=>'u', 'Ç'=>'c', 'Ğ'=>'g', 'İ'=>'i', 'Ö'=>'o', 'Ş'=>'s', 'Ü'=>'u'];
            $cleanName = strtr($nameTr, $trMap);
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $cleanName), '-'));
        }

        $descTr = trim($_POST['description_tr'] ?? '');

        $data = [
            'name_tr'        => $nameTr,
            'name_en'        => AutoTranslator::translate($nameTr, 'tr', 'en'),
            'slug'           => $slug,
            'description_tr' => $descTr,
            'description_en' => !empty($descTr) ? AutoTranslator::translate($descTr, 'tr', 'en') : '',
            'badge_color'    => trim($_POST['badge_color'] ?? '#15803d'),
            'image'          => '',
            'sort_order'     => (int)($_POST['sort_order'] ?? 0),
            'is_active'      => isset($_POST['is_active']) ? 1 : 0
        ];

        if ($id) {
            Category::update($id, $data);
            $_SESSION['panel_success'] = 'Kategori ("' . $nameTr . '") başarıyla güncellendi.';
        } else {
            Category::create($data);
            $_SESSION['panel_success'] = 'Yeni kategori ("' . $nameTr . '") başarıyla oluşturuldu.';
        }

        header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/products");
        exit;
    }

    /**
     * Kategori Sil
     */
    public function deleteCategory(): void
    {
        $this->requireAuth();
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            // İlgili kategoride ürün olup olmadığını kontrol et
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
            $stmt->execute([$id]);
            $count = (int)$stmt->fetchColumn();

            if ($count > 0) {
                $_SESSION['panel_error'] = "Bu kategoriye ait {$count} adet ürün bulunuyor. Önce bu ürünleri başka bir kategoriye taşıyın veya silin.";
            } else {
                Category::delete($id);
                $_SESSION['panel_success'] = 'Kategori başarıyla silindi.';
            }
        }
        header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/products");
        exit;
    }

    /**
     * Blog Yönetimi
     */
    public function blog(): void
    {
        $this->requireAuth();

        $posts = BlogPost::all(false);

        View::renderPanel('blog', [
            'pageTitle'  => 'BİK GRUP | Blog & Haber Yönetimi',
            'activeMenu' => 'blog',
            'posts'      => $posts
        ]);
    }

    /**
     * Blog Kaydet
     */
    public function saveBlog(): void
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['panel_error'] = 'Güvenlik hatası!';
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/blog");
            exit;
        }

        $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
        $slug = trim($_POST['slug'] ?? '');
        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['title_tr'] ?? 'yazi')));
        }

        // Manuel Görsel Yükleme ve Otomatik WebP Dönüştürme
        $imageName = trim($_POST['image'] ?? 'facility_automation.jpg');
        if (!empty($_FILES['image_file']['tmp_name'])) {
            $uploadedWebp = ImageUploader::uploadAndConvertToWebP(
                $_FILES['image_file'],
                ROOT_PATH . '/public/images',
                'blog_' . $slug
            );
            if ($uploadedWebp) {
                $imageName = $uploadedWebp;
            }
        }

        $titleTr = trim($_POST['title_tr'] ?? '');
        $excerptTr = trim($_POST['excerpt_tr'] ?? '');
        $contentTr = trim($_POST['content_tr'] ?? '');

        $data = [
            'slug'         => $slug,
            'title_tr'     => $titleTr,
            'title_en'     => !empty($titleTr) ? AutoTranslator::translate($titleTr, 'tr', 'en') : '',
            'excerpt_tr'   => $excerptTr,
            'excerpt_en'   => !empty($excerptTr) ? AutoTranslator::translate($excerptTr, 'tr', 'en') : '',
            'content_tr'   => $contentTr,
            'content_en'   => !empty($contentTr) ? AutoTranslator::translate($contentTr, 'tr', 'en') : '',
            'image'        => $imageName,
            'author'       => trim($_POST['author'] ?? 'BİKGRUP'),
            'read_time'    => trim($_POST['read_time'] ?? '4 dk'),
            'is_published' => isset($_POST['is_published']) ? 1 : 0
        ];

        if ($id) {
            BlogPost::update($id, $data);
            $_SESSION['panel_success'] = 'Blog yazısı güncellendi.';
        } else {
            BlogPost::create($data);
            $_SESSION['panel_success'] = 'Yeni blog yazısı yayınlandı.';
        }

        $redirect = !empty($_POST['redirect_to']) ? trim($_POST['redirect_to']) : PANEL_SLUG . "/blog";
        header("Location: " . BASE_URL . "/index.php?url=" . $redirect);
        exit;
    }

    /**
     * Blog Sil
     */
    public function deleteBlog(): void
    {
        $this->requireAuth();
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            BlogPost::delete($id);
            $_SESSION['panel_success'] = 'Blog yazısı silindi.';
        }
        $redirect = !empty($_GET['redirect_to']) ? trim($_GET['redirect_to']) : PANEL_SLUG . "/blog";
        header("Location: " . BASE_URL . "/index.php?url=" . $redirect);
        exit;
    }

    /**
     * Gelen Mesajlar
     */
    public function messages(): void
    {
        $this->requireAuth();

        $messages = Message::all();

        View::renderPanel('messages', [
            'pageTitle'  => 'BİK GRUP | Gelen İletişim Mesajları',
            'activeMenu' => 'messages',
            'messages'   => $messages
        ]);
    }

    /**
     * Mesajı Okundu İşaretle
     */
    public function readMessage(): void
    {
        $this->requireAuth();
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            Message::markAsRead($id);
        }
        header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/messages");
        exit;
    }

    /**
     * Mesaj Sil
     */
    public function deleteMessage(): void
    {
        $this->requireAuth();
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            Message::delete($id);
            $_SESSION['panel_success'] = 'Mesaj silindi.';
        }
        header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/messages");
        exit;
    }

    /**
     * Yöneticiler (Dokunulmaz Süleyman Admin Koruma Katmanı)
     */
    public function users(): void
    {
        $this->requireAuth();

        $users = User::all();

        View::renderPanel('users', [
            'pageTitle'  => 'BİK GRUP | Yönetici Hesapları & Yetkiler',
            'activeMenu' => 'users',
            'users'      => $users
        ]);
    }

    /**
     * Yönetici Kaydet (Ekle / Düzenle)
     */
    public function saveUser(): void
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['panel_error'] = 'Güvenlik hatası!';
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/users");
            exit;
        }

        $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role = trim($_POST['role'] ?? 'admin');

        if (empty($name) || empty($email)) {
            $_SESSION['panel_error'] = 'Lütfen ad soyad ve geçerli bir e-posta adresi giriniz!';
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/users");
            exit;
        }

        // Süleyman Özer'i yalnızca kendisi düzenleyebilir
        if ($id) {
            $targetUser = User::find($id);
            if ($targetUser && ((int)$targetUser['is_immutable'] === 1 || strtolower($targetUser['email']) === 'suleymannozer0@gmail.com')) {
                if (Auth::id() !== $id) {
                    $_SESSION['panel_error'] = 'Süleyman Özer hesabını yalnızca Süleyman Özer düzenleyebilir!';
                    header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/users");
                    exit;
                }
            }
        }

        try {
            if ($id) {
                User::update($id, [
                    'name'     => $name,
                    'email'    => $email,
                    'password' => $password,
                    'role'     => $role
                ]);
                $_SESSION['panel_success'] = 'Yönetici hesabı güncellendi.';
            } else {
                if (empty($password)) {
                    throw new Exception("Yeni yönetici için şifre belirlemelisiniz!");
                }
                User::create([
                    'name'     => $name,
                    'email'    => $email,
                    'password' => $password,
                    'role'     => $role
                ]);
                $_SESSION['panel_success'] = 'Yeni yönetici hesabı başarıyla oluşturuldu.';
            }
        } catch (Exception $e) {
            $_SESSION['panel_error'] = $e->getMessage();
        }

        header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/users");
        exit;
    }

    /**
     * Yönetici Sil (Dokunulmazlık Korumalı)
     */
    public function deleteUser(): void
    {
        $this->requireAuth();
        $id = (int)($_GET['id'] ?? 0);

        try {
            User::delete($id);
            $_SESSION['panel_success'] = 'Yönetici hesabı başarıyla silindi.';
        } catch (Exception $e) {
            $_SESSION['panel_error'] = $e->getMessage();
        }

        header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/users");
        exit;
    }

    /**
     * KULLANICI TALEBİ: Hızlı Teklif Talepleri & Teklif Hazırlama Alanı
     */
    public function quotes(): void
    {
        $this->requireAuth();
        $status = $_GET['status'] ?? null;
        $quotes = QuoteRequest::all($status);
        $pendingCount = QuoteRequest::pendingCount();

        View::renderPanel('quotes', [
            'pageTitle'    => 'BİK GRUP | Hızlı Teklif Talepleri & Hazırlama',
            'activeMenu'   => 'quotes',
            'quotes'       => $quotes,
            'currentFilter'=> $status,
            'pendingCount' => $pendingCount
        ]);
    }

    /**
     * Teklif Durumunu Güncelle & Cevap / Not Ekle
     */
    public function updateQuote(): void
    {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/quotes");
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        $status = Security::clean($_POST['status'] ?? 'pending');
        $adminNotes = Security::clean($_POST['admin_notes'] ?? '');
        $quoteAmount = Security::clean($_POST['quote_amount'] ?? '');

        if ($id > 0) {
            QuoteRequest::updateStatus($id, $status, $adminNotes, $quoteAmount);
            $_SESSION['panel_success'] = "Teklif talebi (#{$id}) başarıyla güncellendi.";
        }

        header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/quotes");
        exit;
    }

    /**
     * Panelden Manuel Yeni Teklif Oluştur
     */
    public function createQuote(): void
    {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/quotes");
            exit;
        }

        $category = Security::clean($_POST['product_category'] ?? '');
        $quantity = Security::clean($_POST['quantity'] ?? '');
        $fullName = Security::clean($_POST['full_name'] ?? '');
        $phone    = Security::clean($_POST['phone'] ?? '');
        $email    = Security::clean($_POST['email'] ?? '');
        $notes    = Security::clean($_POST['notes'] ?? '');
        $amount   = Security::clean($_POST['quote_amount'] ?? '');

        if (!empty($category) && !empty($fullName) && !empty($email)) {
            $id = QuoteRequest::create([
                'product_category' => $category,
                'quantity'         => $quantity,
                'full_name'        => $fullName,
                'phone'            => $phone,
                'email'            => $email,
                'notes'            => $notes
            ]);
            if (!empty($amount)) {
                QuoteRequest::updateStatus($id, 'replied', 'Panelden doğrudan teklif oluşturuldu.', $amount);
            }
            $_SESSION['panel_success'] = "Yeni hızlı teklif (#{$id}) başarıyla oluşturuldu.";
        } else {
            $_SESSION['panel_error'] = "Lütfen müşteri adı, e-posta ve ürün alanlarını doldurunuz.";
        }

        header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/quotes");
        exit;
    }

    /**
     * Teklif Sil
     */
    public function deleteQuote(): void
    {
        $this->requireAuth();
        $id = (int)($_GET['id'] ?? 0);

        if ($id > 0) {
            QuoteRequest::delete($id);
            $_SESSION['panel_success'] = "Teklif kaydı silindi.";
        }

        header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/quotes");
        exit;
    }

    /**
     * Teklif Bildirim E-Posta Ayarlarını Kaydet
     */
    public function saveQuoteMailSettings(): void
    {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['panel_error'] = 'Güvenlik doğrulaması hatası!';
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/quotes");
            exit;
        }

        $email      = Security::clean($_POST['quote_notification_email'] ?? '');
        $smtpHost   = Security::clean($_POST['smtp_host'] ?? '');
        $smtpPort   = Security::clean($_POST['smtp_port'] ?? '587');
        $smtpUser   = Security::clean($_POST['smtp_user'] ?? '');
        $smtpPass   = trim($_POST['smtp_pass'] ?? '');
        $smtpSecure = Security::clean($_POST['smtp_secure'] ?? 'tls');

        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value, group_name, label_tr) 
                              VALUES (?, ?, 'quotes', ?) 
                              ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");

        $settingsMap = [
            'quote_notification_email' => [$email, 'Teklif Bildirim E-Postası'],
            'smtp_host'                => [$smtpHost, 'SMTP Sunucu Adresi'],
            'smtp_port'                => [$smtpPort, 'SMTP Port'],
            'smtp_user'                => [$smtpUser, 'SMTP Kullanıcı / E-Posta'],
            'smtp_secure'              => [$smtpSecure, 'SMTP Güvenlik Protokolü']
        ];

        foreach ($settingsMap as $key => [$val, $lbl]) {
            $stmt->execute([$key, $val, $lbl]);
        }

        // Şifre boş bırakılmamışsa güncelle, boş bırakılmışsa mevcut şifreyi koru
        if ($smtpPass !== '') {
            $stmt->execute(['smtp_pass', $smtpPass, 'SMTP Şifresi']);
        }

        $_SESSION['panel_success'] = 'Teklif bildirim e-posta entegrasyonu başarıyla kaydedildi!';
        header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/quotes");
        exit;
    }

    /**
     * Teklif Bildirim E-Postası Test Gönderimi
     */
    public function sendTestQuoteMail(): void
    {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::validateCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['panel_error'] = 'Güvenlik doğrulaması hatası!';
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/quotes");
            exit;
        }

        require_once ROOT_PATH . '/core/Mailer.php';

        $targetEmail = Settings::get('quote_notification_email');
        if (empty($targetEmail) || !filter_var($targetEmail, FILTER_VALIDATE_EMAIL)) {
            $targetEmail = Settings::get('site_email', 'destek@bikgrup.com');
        }

        if (empty($targetEmail) || !filter_var($targetEmail, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['panel_error'] = 'Lütfen geçerli bir bildirim e-posta adresi kaydediniz.';
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/quotes");
            exit;
        }

        $subject = "🔔 BİK GRUP - Teklif Entegrasyon Test E-Postası (" . date('d.m.Y H:i') . ")";
        $body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #fed7aa; border-radius: 14px; background: #ffffff;'>
            <div style='text-align: center; padding-bottom: 16px; border-bottom: 2px solid #ea580c;'>
                <h2 style='color: #ea580c; margin: 0; font-size: 22px;'>⚡ BİK GRUP Teklif Sistemi Test Bildirimi</h2>
            </div>
            <p style='color: #334155; font-size: 15px; margin-top: 20px;'>
                Tebrikler! Bu e-posta, <strong>BİK GRUP</strong> yönetim paneli teklif entegrasyon ayarlarınızın başarıyla çalıştığını teyit etmek amacıyla gönderilmiştir.
            </p>
            <div style='background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; margin: 20px 0;'>
                <p style='margin: 4px 0; color: #0f172a; font-size: 14px;'><strong>Alıcı E-Posta:</strong> {$targetEmail}</p>
                <p style='margin: 4px 0; color: #0f172a; font-size: 14px;'><strong>Tarih & Saat:</strong> " . date('d.m.Y H:i:s') . "</p>
                <p style='margin: 4px 0; color: #0f172a; font-size: 14px;'><strong>Durum:</strong> <span style='color: #16a34a; font-weight: bold;'>Aktif ve Hazır</span></p>
            </div>
            <p style='color: #64748b; font-size: 13px; line-height: 1.5;'>
                Artık web sitenizden veya teklif modalından gelen tüm müşteri teklif talepleri anında bu e-posta kutunuza düşecek ve aynı zamanda yönetim panelinizde listelenecektir.
            </p>
        </div>";

        $result = Mailer::send($targetEmail, $subject, $body);

        if ($result['success']) {
            $_SESSION['panel_success'] = "Test e-postası başarıyla gönderildi! Lütfen ({$targetEmail}) adresinin gelen kutusunu (ve gerekirse spam klasörünü) kontrol ediniz.";
        } else {
            $_SESSION['panel_error'] = "Test e-postası gönderilemedi: " . htmlspecialchars($result['message']) . " (Yerel XAMPP ortamında harici SMTP bilgisi girmeniz gerekebilir).";
        }

        header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/quotes");
        exit;
    }

    /**
     * KULLANICI TALEBİ: Referans Logo Silme İşlemi
     */
    public function deleteReference(): void
    {
        $this->requireAuth();
        $idx = isset($_GET['idx']) ? (int)$_GET['idx'] : -1;
        if ($idx >= 0) {
            $sec = PageSection::get('home_references') ?? [];
            $extra = !empty($sec['extra_json']) ? json_decode($sec['extra_json'], true) : ($sec['extra'] ?? []);
            $items = $extra['items'] ?? [];
            if (isset($items[$idx])) {
                $deletedName = $items[$idx]['name'] ?? 'Referans';
                unset($items[$idx]);
                $extra['items'] = array_values($items);
                PageSection::updateOrCreate('home_references', [
                    'extra_json' => json_encode($extra, JSON_UNESCAPED_UNICODE)
                ]);
                $_SESSION['panel_success'] = "{$deletedName} referans logosu başarıyla silindi.";
            }
        }
        header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/pages#section-home_references");
        exit;
    }

    /**
     * Medya & Galeri Yönetimi
     */
    public function gallery(): void
    {
        $this->requireAuth();
        require_once ROOT_PATH . '/app/Models/GalleryItem.php';
        $items = GalleryItem::all(false);

        View::renderPanel('gallery', [
            'pageTitle'  => 'BİK GRUP | Medya & Galeri Yönetimi',
            'activeMenu' => 'gallery',
            'items'      => $items
        ]);
    }

    /**
     * Galeri Öğesi Ekle / Düzenle
     */
    public function saveGalleryItem(): void
    {
        $this->requireAuth();
        require_once ROOT_PATH . '/app/Models/GalleryItem.php';

        $id = (int)($_POST['id'] ?? 0);
        $type = in_array($_POST['type'] ?? '', ['image', 'video'], true) ? $_POST['type'] : 'image';
        $titleTr = trim($_POST['title_tr'] ?? '');
        $category = trim($_POST['category'] ?? 'genel');
        $videoUrl = trim($_POST['video_url'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if (empty($titleTr)) {
            $_SESSION['panel_error'] = 'Lütfen başlık giriniz.';
            header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/gallery");
            exit;
        }

        $existing = $id > 0 ? GalleryItem::find($id) : null;
        $imagePath = $existing['image_path'] ?? '';
        $videoFile = $existing['video_file'] ?? '';

        // Görsel Yükleme (Otomatik WebP Dönüştürme)
        if (!empty($_FILES['image_file']['tmp_name'])) {
            $uploadedWebp = ImageUploader::uploadAndConvertToWebP(
                $_FILES['image_file'],
                UPLOADS_PATH,
                'gallery_' . ($type === 'video' ? 'poster_' : 'img_')
            );
            if ($uploadedWebp) {
                $imagePath = $uploadedWebp;
            }
        }

        // Video Dosyası Yükleme (mp4, webm)
        if (!empty($_FILES['video_file']['tmp_name'])) {
            $file = $_FILES['video_file'];
            $ext = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
            if (in_array($ext, ['mp4', 'webm', 'ogg'], true)) {
                $videoFileName = 'gallery_video_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
                if (move_uploaded_file($file['tmp_name'], UPLOADS_PATH . '/' . $videoFileName)) {
                    $videoFile = $videoFileName;
                }
            }
        }

        // Otomatik İngilizce Çeviri (Kullanıcı talebi: İngilizceyi sistem otomatik çevirecek)
        $titleEn = !empty($titleTr) ? AutoTranslator::translate($titleTr, 'tr', 'en') : '';

        $data = [
            'type'        => $type,
            'title_tr'    => $titleTr,
            'title_en'    => $titleEn,
            'category'    => $category,
            'image_path'  => $imagePath,
            'video_url'   => $videoUrl,
            'video_file'  => $videoFile,
            'sort_order'  => $sortOrder,
            'is_active'   => $isActive
        ];

        if ($id > 0) {
            GalleryItem::update($id, $data);
            $_SESSION['panel_success'] = 'Medya öğesi başarıyla güncellendi.';
        } else {
            GalleryItem::create($data);
            $_SESSION['panel_success'] = 'Yeni medya öğesi galeriye eklendi.';
        }

        header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/gallery");
        exit;
    }

    /**
     * Galeri Öğesi Sil
     */
    public function deleteGalleryItem(): void
    {
        $this->requireAuth();
        require_once ROOT_PATH . '/app/Models/GalleryItem.php';
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            GalleryItem::delete($id);
            $_SESSION['panel_success'] = 'Medya öğesi galeriden silindi.';
        }
        header("Location: " . BASE_URL . "/index.php?url=" . PANEL_SLUG . "/gallery");
        exit;
    }
}

