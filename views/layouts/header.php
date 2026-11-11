<?php
/**
 * BİKGRUP - Genel Başlık ve Navigasyon Şablonu
 * Çizgisel Mimari & Akıllı Scrolled Navbar
 */

declare(strict_types=1);

$currentLang = Lang::current();
$siteName = Settings::get('site_name', 'BİKGRUP');
$siteLogo = Settings::get('site_logo', '');
$siteFavicon = Settings::get('site_favicon', '');
$categoriesMenu = Category::all(true);
$isHome = ($activeNav ?? '') === 'home';
$activeView = $currentView ?? ($GLOBALS['currentView'] ?? '');
$pagesWithHero = ['home', 'about', 'brands', 'brand_detail', 'products', 'blog', 'contact', 'policy', 'gallery'];
if (in_array($activeView, ['product_detail', 'blog_detail'], true)) {
    $hasHero = false;
} else {
    $hasHero = !empty($hasHero) || (!empty($activeView) ? in_array($activeView, $pagesWithHero, true) : in_array($activeNav ?? '', $pagesWithHero, true));
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($currentLang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? $siteName) ?></title>
    <meta name="description" content="<?= htmlspecialchars(Settings::getLocalized('site_description', 'BİKGRUP Taze Narenciye, Meyve ve Tarımsal İhracat')) ?>">
    
    <!-- SEO & Sosyal Paylaşım Meta Etiketleri -->
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle ?? $siteName) ?>">
    <meta property="og:description" content="<?= htmlspecialchars(Settings::getLocalized('site_description', 'Akdeniz ve Egenin en verimli topraklarında yetişen taze ürünler.')) ?>">
    <meta property="og:type" content="website">
    <meta property="og:image" content="<?= BASE_URL ?>/public/images/nature_hero_bg.jpg">

    <?php if (!empty($siteFavicon) && file_exists(UPLOADS_PATH . '/' . $siteFavicon)): ?>
        <link rel="icon" href="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($siteFavicon) ?>">
    <?php endif; ?>

    <!-- Dinamik Tema Değişkenleri (Arka Panelden Anında Değişen Canlı CSS) -->
    <?= Settings::renderCssVariables() ?>
    
    <!-- CSS Dosyaları -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css?v=<?= filemtime(PUBLIC_PATH . '/css/style.css') ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/animations.css?v=<?= filemtime(PUBLIC_PATH . '/css/animations.css') ?>">
</head>
<body>

    <!-- Yüzen Pill Navbar (Sayfa Aşağı Kaydırıldığında veya Adler Hero Olmayan Sayfalarda Görünür) -->
    <header class="header-wrapper <?= empty($hasHero) ? 'header-visible' : '' ?>">
        <nav class="navbar-pill">
            
            <a href="<?= BASE_URL ?>/" class="bik-logo">
                <?php if (!empty($siteLogo) && file_exists(UPLOADS_PATH . '/' . $siteLogo)): ?>
                    <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($siteLogo) ?>" alt="<?= htmlspecialchars($siteName) ?>" style="max-height: 48px; width: auto; object-fit: contain;">
                <?php else: ?>
                    <span class="logo-leaf-icon" style="width:24px; height:24px; font-size:12px;">🌿</span>
                    <span>BİK<span class="accent">GRUP</span></span>
                <?php endif; ?>
            </a>

            <ul class="nav-menu">
                <li><a href="<?= BASE_URL ?>/" class="nav-link <?= ($activeNav ?? '') === 'home' ? 'active' : '' ?>"><?= __('nav_home') ?></a></li>
                <li><a href="<?= BASE_URL ?>/kurumsal" class="nav-link <?= ($activeNav ?? '') === 'about' ? 'active' : '' ?>"><?= __('nav_about') ?></a></li>
                <li class="nav-item-dropdown">
                    <a href="<?= BASE_URL ?>/markalar" class="nav-link nav-link-dropdown <?= in_array($activeNav ?? '', ['brands', 'references'], true) ? 'active' : '' ?>">
                        <?= $currentLang === 'en' ? 'Brands & References' : 'Markalarımız & Referanslarımız' ?> <span style="font-size: 11px; margin-left: 2px;">▾</span>
                    </a>
                    <div class="nav-dropdown-content">
                        <a href="<?= BASE_URL ?>/markalar" class="<?= ($activeNav ?? '') === 'brands' ? 'active' : '' ?>">
                            <?= $currentLang === 'en' ? 'Our Brands' : 'Markalarımız' ?>
                        </a>
                        <a href="<?= BASE_URL ?>/referanslar" class="<?= ($activeNav ?? '') === 'references' ? 'active' : '' ?>">
                            <?= $currentLang === 'en' ? 'Our References' : 'Referanslarımız' ?>
                        </a>
                    </div>
                </li>
                <li><a href="<?= BASE_URL ?>/urunler" class="nav-link <?= ($activeNav ?? '') === 'products' ? 'active' : '' ?>"><?= __('nav_products') ?></a></li>
                <li><a href="<?= BASE_URL ?>/galeri" class="nav-link <?= ($activeNav ?? '') === 'gallery' ? 'active' : '' ?>"><?= $currentLang === 'en' ? 'Gallery' : 'Galeri' ?></a></li>
                <li><a href="<?= BASE_URL ?>/blog" class="nav-link <?= ($activeNav ?? '') === 'blog' ? 'active' : '' ?>"><?= __('nav_blog') ?></a></li>
                <li><a href="<?= BASE_URL ?>/iletisim" class="nav-link <?= ($activeNav ?? '') === 'contact' ? 'active' : '' ?>"><?= __('nav_contact') ?></a></li>
            </ul>

            <div class="nav-actions">
                <button type="button" class="header-quote-btn" onclick="openQuoteModal()" aria-label="Teklif Al">
                    <span class="quote-btn-text"><?= $currentLang === 'en' ? 'Get a Quote' : 'Teklif Al' ?></span>
                </button>

                <div class="lang-switcher">
                    <a href="<?= Lang::switchUrl('tr') ?>" class="lang-btn <?= $currentLang === 'tr' ? 'active' : '' ?>" title="Türkçe">TR</a>
                    <a href="<?= Lang::switchUrl('en') ?>" class="lang-btn <?= $currentLang === 'en' ? 'active' : '' ?>" title="English">EN</a>
                </div>

                <button class="mobile-toggle" style="color:var(--theme-text);" aria-label="Menü">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>

        </nav>
    </header>

    <!-- Mobil Tam Ekran Menü (Kullanıcı Talebi: Tam Ekran Mimari) -->
    <div class="mobile-menu-drawer" id="mobileMenuDrawer">
        <div class="mobile-menu-content">
            <!-- Üst Bar: Logo ve Kapat Butonu -->
            <div class="mobile-menu-header">
                <a href="<?= BASE_URL ?>/" class="bik-logo" onclick="closeMobileMenu();" style="display: inline-flex; align-items: center; gap: 8px;">
                    <?php if (!empty($siteLogo) && file_exists(UPLOADS_PATH . '/' . $siteLogo)): ?>
                        <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($siteLogo) ?>" alt="<?= htmlspecialchars($siteName) ?>" style="max-height: 48px; width: auto; object-fit: contain;">
                    <?php else: ?>
                        <span class="logo-leaf-icon" style="width:28px; height:28px; font-size:14px;">🌿</span>
                        <span style="font-size: 20px;">BİK<span class="accent">GRUP</span></span>
                    <?php endif; ?>
                </a>
                <button type="button" class="mobile-menu-close-btn" onclick="closeMobileMenu();" aria-label="Menüyü Kapat">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- Menü Bağlantıları -->
            <nav class="mobile-menu-links">
                <a href="<?= BASE_URL ?>/" class="<?= ($activeNav ?? '') === 'home' ? 'active' : '' ?>" onclick="closeMobileMenu();">
                    <span><?= __('nav_home') ?></span>
                </a>
                <a href="<?= BASE_URL ?>/kurumsal" class="<?= ($activeNav ?? '') === 'about' ? 'active' : '' ?>" onclick="closeMobileMenu();">
                    <span><?= __('nav_about') ?></span>
                </a>
                <a href="<?= BASE_URL ?>/markalar" class="<?= ($activeNav ?? '') === 'brands' ? 'active' : '' ?>" onclick="closeMobileMenu();">
                    <span><?= __('nav_brands', 'Markalarımız') ?></span>
                </a>
                <a href="<?= BASE_URL ?>/urunler" class="<?= ($activeNav ?? '') === 'products' ? 'active' : '' ?>" onclick="closeMobileMenu();">
                    <span><?= __('nav_products') ?></span>
                </a>
                <a href="<?= BASE_URL ?>/referanslar" class="<?= ($activeNav ?? '') === 'references' ? 'active' : '' ?>" onclick="closeMobileMenu();">
                    <span><?= $currentLang === 'en' ? 'References' : 'Referanslarımız' ?></span>
                </a>
                <a href="<?= BASE_URL ?>/galeri" class="<?= ($activeNav ?? '') === 'gallery' ? 'active' : '' ?>" onclick="closeMobileMenu();">
                    <span><?= $currentLang === 'en' ? 'Gallery' : 'Galeri' ?></span>
                </a>
                <a href="<?= BASE_URL ?>/blog" class="<?= ($activeNav ?? '') === 'blog' ? 'active' : '' ?>" onclick="closeMobileMenu();">
                    <span><?= __('nav_blog') ?></span>
                </a>
                <a href="<?= BASE_URL ?>/iletisim" class="<?= ($activeNav ?? '') === 'contact' ? 'active' : '' ?>" onclick="closeMobileMenu();">
                    <span><?= __('nav_contact') ?></span>
                </a>
            </nav>

            <!-- Alt Aksiyon & İletişim Alanı -->
            <div class="mobile-menu-bottom">
                <button type="button" class="header-quote-btn mobile-menu-quote-btn" onclick="closeMobileMenu(); openQuoteModal();" aria-label="Teklif Al">
                    <span class="quote-btn-text"><?= $currentLang === 'en' ? 'Get a Quote' : 'Teklif Al' ?></span>
                </button>

                <div class="mobile-menu-meta">
                    <div class="mobile-menu-lang">
                        <a href="<?= Lang::switchUrl('tr') ?>" class="mobile-lang-btn <?= $currentLang === 'tr' ? 'active' : '' ?>">TR</a>
                        <span style="color: #cbd5e1;">|</span>
                        <a href="<?= Lang::switchUrl('en') ?>" class="mobile-lang-btn <?= $currentLang === 'en' ? 'active' : '' ?>">EN</a>
                    </div>
                    <a href="tel:<?= preg_replace('/[^0-9+]/', '', Settings::get('site_phone', '05337989901')) ?>" class="mobile-menu-phone">
                        📞 <?= htmlspecialchars(Settings::get('site_phone', '0533 798 99 01')) ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
