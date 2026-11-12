<?php
/**
 * BİKGRUP - Ana Giriş ve SEO Uyumlu Rota Yönlendirici (Router)
 * PHP 8.2 / 8.3 / 8.4 Uyumlu
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Lang.php';
require_once __DIR__ . '/core/Settings.php';
require_once __DIR__ . '/core/Security.php';
require_once __DIR__ . '/core/Auth.php';
require_once __DIR__ . '/core/ImageUploader.php';
require_once __DIR__ . '/core/AutoTranslator.php';
require_once __DIR__ . '/core/Mailer.php';

// Temel Modellerin Global Yüklenmesi (Hiçbir sayfada Class Not Found hatası yaşanmaz)
require_once __DIR__ . '/app/Models/Category.php';
require_once __DIR__ . '/app/Models/Product.php';
require_once __DIR__ . '/app/Models/PageSection.php';
require_once __DIR__ . '/app/Models/BlogPost.php';
require_once __DIR__ . '/app/Models/Message.php';
require_once __DIR__ . '/app/Models/GalleryItem.php';

// Dil ve Ayar motorlarını başlat
Lang::init();
Settings::load();

// Rota parametresini al (SEO Dostu Temiz URL Desteği)
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$path = parse_url($requestUri, PHP_URL_PATH) ?? '';

// /bikgroup veya /bikgrup önekini temizle
$baseDir = parse_url(BASE_URL, PHP_URL_PATH) ?? '';
if (!empty($baseDir) && str_starts_with($path, $baseDir)) {
    $path = substr($path, strlen($baseDir));
} elseif (str_starts_with($path, '/bikgroup')) {
    $path = substr($path, 9);
} elseif (str_starts_with($path, '/bikgrup')) {
    $path = substr($path, 8);
}

$url = trim($path, '/');

// index.php dosya adını temizle
if (str_starts_with($url, 'index.php')) {
    $url = trim(substr($url, 9), '/');
}

// Eğer doğrudan index.php?url=... şeklinde GET isteği yapılmışsa temiz SEO URL'ye yönlendir
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET' && isset($_GET['url']) && str_contains($_SERVER['REQUEST_URI'] ?? '', 'index.php')) {
    $cleanPath = ltrim((string)$_GET['url'], '/');
    $query = $_GET;
    unset($query['url']);
    $qs = !empty($query) ? '?' . http_build_query($query) : '';
    header("Location: " . BASE_URL . "/" . $cleanPath . $qs, true, 301);
    exit;
}

// Eğer temizlenmiş $url boşsa ve $_GET['url'] parametresi gönderilmişse onu kullan
if (empty($url) && isset($_GET['url'])) {
    $url = trim((string)$_GET['url'], '/');
}

$parts = !empty($url) ? explode('/', $url) : [];
$route = strtolower($parts[0] ?? '');
$subRoute = strtolower($parts[1] ?? '');
$param = $parts[2] ?? '';

// SEO Dostu Türkçe ve Uluslararası Rotaların Eşleştirilmesi
switch ($route) {
    case '':
    case 'anasayfa':
    case 'home':
        require_once APP_PATH . '/Controllers/HomeController.php';
        (new HomeController())->index();
        break;

    // Kurumsal / Hakkımızda
    case 'kurumsal':
    case 'hakkimizda':
    case 'about':
        require_once APP_PATH . '/Controllers/AboutController.php';
        (new AboutController())->index();
        break;

    // Ürünler
    case 'urunler':
    case 'products':
        require_once APP_PATH . '/Controllers/ProductsController.php';
        $controller = new ProductsController();
        if (!empty($subRoute)) {
            $controller->detail($subRoute);
        } else {
            $controller->index();
        }
        break;

    // Blog & Haberler
    case 'blog':
    case 'haberler':
        require_once APP_PATH . '/Controllers/BlogController.php';
        $controller = new BlogController();
        if (!empty($subRoute)) {
            $controller->detail($subRoute);
        } else {
            $controller->index();
        }
        break;

    // KULLANICI TALEBİ: Grup Markaları Sayfaları (Genel Sayfa & Tekil Marka)
    case 'markalar':
    case 'marka':
    case 'brands':
    case 'brand':
        require_once APP_PATH . '/Controllers/BrandController.php';
        $controller = new BrandController();
        if (!empty($subRoute)) {
            $controller->detail($subRoute);
        } else {
            $controller->index();
        }
        break;

    // İletişim
    case 'iletisim':
    case 'contact':
        require_once APP_PATH . '/Controllers/ContactController.php';
        $controller = new ContactController();
        if ($subRoute === 'submit' || $subRoute === 'gonder') {
            $controller->submit();
        } else {
            $controller->index();
        }
        break;

    // KULLANICI TALEBİ: Hızlı Teklif Al / Gönder
    case 'teklif-al':
    case 'teklif-gonder':
    case 'api-quote':
        require_once APP_PATH . '/Controllers/QuoteController.php';
        (new QuoteController())->submit();
        break;

    // KULLANICI TALEBİ: Politikalar (Gizlilik, KVKK, Çerez Politikası)
    case 'politikalar':
    case 'gizlilik':
    case 'privacy':
    case 'kvkk':
    case 'gdpr':
    case 'cerez-politikasi':
    case 'cookies':
        $activeTab = 'gizlilik';
        if ($route === 'kvkk' || $route === 'gdpr') {
            $activeTab = 'kvkk';
        } elseif ($route === 'cerez-politikasi' || $route === 'cookies') {
            $activeTab = 'cerez';
        }
        $siteTitle = Settings::getLocalized('site_title', 'BİKGRUP');
        $siteDesc = Settings::getLocalized('site_description', '');
        $isEn = Lang::current() === 'en';
        $policyName = $activeTab === 'kvkk' 
            ? ($isEn ? 'GDPR / KVKK Clarification' : 'KVKK Aydınlatma Metni') 
            : ($activeTab === 'cerez' 
                ? ($isEn ? 'Cookie Policy' : 'Çerez Politikası') 
                : ($isEn ? 'Privacy Policy' : 'Gizlilik Politikası'));
        $pageTitle = $policyName . ' - ' . $siteTitle;
        require_once ROOT_PATH . '/core/View.php';
        View::render('policy', [
            'activeTab' => $activeTab,
            'pageTitle' => $pageTitle,
            'siteTitle' => $siteTitle,
            'siteDesc'  => $siteDesc
        ]);
        break;

    // KULLANICI TALEBİ: Medya & Galeri Sayfası (Fotoğraf & Video Galerisi)
    case 'galeri':
    case 'gallery':
        $siteTitle = Settings::getLocalized('site_title', 'BİKGRUP');
        $siteDesc = Settings::getLocalized('site_description', '');
        $pageTitle = (Lang::current() === 'en' ? 'Photo & Video Gallery' : 'Fotoğraf & Video Galerisi') . ' - ' . $siteTitle;
        $items = GalleryItem::all(true);
        require_once ROOT_PATH . '/core/View.php';
        View::render('gallery', [
            'pageTitle' => $pageTitle,
            'siteTitle' => $siteTitle,
            'siteDesc'  => $siteDesc,
            'items'     => $items,
            'activeNav' => 'gallery'
        ]);
        break;

    // KULLANICI TALEBİ: Referanslar Sayfası (Kare Kutularda Düz Sayfa - 3'erli Logolar)
    case 'referanslar':
    case 'references':
        $siteTitle = Settings::getLocalized('site_title', 'BİKGRUP');
        $siteDesc = Settings::getLocalized('site_description', '');
        $pageTitle = (Lang::current() === 'en' ? 'References' : 'Referanslarımız') . ' - ' . $siteTitle;
        require_once ROOT_PATH . '/core/View.php';
        View::render('references', [
            'pageTitle' => $pageTitle,
            'siteTitle' => $siteTitle,
            'siteDesc'  => $siteDesc,
            'activeNav' => 'references'
        ]);
        break;

    // GİZLİ YÖNETİM PANELİ (xpanel, panel, admin)
    case PANEL_SLUG:
    case 'panel':
    case 'admin':
        require_once APP_PATH . '/Controllers/PanelController.php';
        $panel = new PanelController();

        switch ($subRoute) {
            case 'login':
                $panel->login();
                break;
            case 'do-login':
                $panel->doLogin();
                break;
            case 'logout':
                $panel->logout();
                break;
            case 'theme':
                $panel->theme();
                break;
            case 'save-theme':
                $panel->saveTheme();
                break;
            case 'reset-theme':
                $panel->resetTheme();
                break;
            case 'settings':
                $panel->settings();
                break;
            case 'save-settings':
                $panel->saveSettings();
                break;
            case 'test-email':
                $panel->testEmail();
                break;
            case 'footer':
                $panel->footer();
                break;
            case 'save-footer':
                $panel->saveFooter();
                break;
            case 'pages':
                $panel->pages();
                break;
            case 'save-page':
                $panel->savePageSection();
                break;
            case 'products':
                $panel->products();
                break;
            case 'save-product':
                $panel->saveProduct();
                break;
            case 'delete-product':
                $panel->deleteProduct();
                break;
            case 'save-category':
                $panel->saveCategory();
                break;
            case 'delete-category':
                $panel->deleteCategory();
                break;
            case 'blog':
                $panel->blog();
                break;
            case 'save-blog':
                $panel->saveBlog();
                break;
            case 'delete-blog':
                $panel->deleteBlog();
                break;
            // Medya & Galeri Yönetimi
            case 'gallery':
                $panel->gallery();
                break;
            case 'save-gallery':
                $panel->saveGalleryItem();
                break;
            case 'delete-gallery':
                $panel->deleteGalleryItem();
                break;
            case 'save-gallery-category':
                $panel->saveGalleryCategory();
                break;
            case 'delete-gallery-category':
                $panel->deleteGalleryCategory();
                break;
            case 'messages':
            case 'read-message':
            case 'delete-message':
                header("Location: " . BASE_URL . "/" . PANEL_SLUG . "/quotes");
                exit;
            case 'delete-reference':
                $panel->deleteReference();
                break;
            // KULLANICI TALEBİ: Hızlı Teklif Talepleri & Gelen Talepler
            case 'quotes':
                $panel->quotes();
                break;
            case 'quote-mail-settings':
                $panel->saveQuoteMailSettings();
                break;
            case 'quote-mail-test':
                $panel->sendTestQuoteMail();
                break;
            case 'quote-update':
                $panel->updateQuote();
                break;
            case 'quote-create':
                $panel->createQuote();
                break;
            case 'quote-delete':
                $panel->deleteQuote();
                break;
            case 'users':
                $panel->users();
                break;
            case 'save-user':
                $panel->saveUser();
                break;
            case 'delete-user':
                $panel->deleteUser();
                break;
            default:
                $panel->dashboard();
                break;
        }
        break;

    default:
        // Bilinmeyen rotada anasayfaya veya 404
        require_once APP_PATH . '/Controllers/HomeController.php';
        (new HomeController())->index();
        break;
}
