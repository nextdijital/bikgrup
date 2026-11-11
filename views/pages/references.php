<?php
/**
 * BİKGRUP - Referanslar Sayfası (Kare Kutularda Toplu Logo Görünümü)
 * PHP 8.2 / Strict Types
 */

declare(strict_types=1);

$currentLang = Lang::current();
$siteLogo = Settings::get('site_logo', '');
$siteLogoText = Settings::get('site_logo_text', 'BİK GRUP');
$hasHero = true;

// Referans Logoları Listesi (Optimizeli WebP Logolar)
$defaultLogos = [
    ['name' => 'Beşler Yem', 'image' => 'references/besler-yem.webp'],
    ['name' => 'Elita Gıda', 'image' => 'references/elita-gida.webp'],
    ['name' => 'Erpiliç', 'image' => 'references/erpilinc.webp'],
    ['name' => 'Mersin Yem', 'image' => 'references/mersin-yem.webp'],
    ['name' => 'Migros', 'image' => 'references/migros.webp'],
    ['name' => 'Omnia Nişasta', 'image' => 'references/omnia.webp'],
    ['name' => 'Ova Un', 'image' => 'references/ova-un.webp'],
    ['name' => 'Proyem', 'image' => 'references/proyem.webp'],
    ['name' => 'Semolina', 'image' => 'references/semolina.webp'],
    ['name' => 'Sunar Mısır', 'image' => 'references/sunar-misir.webp'],
    ['name' => 'Sunar Un', 'image' => 'references/sunar-un.webp'],
    ['name' => 'Tarım Kredi Kooperatifleri', 'image' => 'references/tarim-kredi.webp'],
    ['name' => 'Tat Nişasta', 'image' => 'references/tat-nisasta.webp'],
    ['name' => 'Tekbaş Un', 'image' => 'references/tekbas-un.webp'],
    ['name' => 'Tezkim Tarımsal Kimya', 'image' => 'references/tezkim.webp'],
    ['name' => 'Timagro', 'image' => 'references/timagro.webp'],
];

$refSec = PageSection::get('home_references') ?? [];
$refExtra = $refSec['extra'] ?? (!empty($refSec['extra_json']) ? json_decode($refSec['extra_json'], true) : []);
$logos = !empty($refExtra['items']) ? $refExtra['items'] : $defaultLogos;

$heroBg = 'nature_hero_bg.jpg';
$heroBadge = $currentLang === 'en' ? 'GLOBAL TRADE NETWORK & REFERENCES' : 'KÜRESEL İŞ ORTAKLARI & GÜVENİLİR REFERANSLAR';
$heroTitle = $currentLang === 'en' ? 'Our References' : 'Referanslarımız';
$heroDesc = $currentLang === 'en' 
    ? 'Domestic and international commercial partners, retail chains and trusted brands with whom we conduct wholesale export.' 
    : 'Toptan üretim, paketleme ve ihracat ortaklığı yürüttüğümüz ulusal ve küresel iş ortaklarımız.';
?>

<!-- ========================================================================
     REFERANSLAR - ADLER STYLE MİNİMAL HERO GİRİŞ ALANI
     ======================================================================== -->
<section class="adler-hero-viewport inner-hero-viewport">
    
    <div class="adler-contour-frame frame-centered inner-hero-frame">
        
        <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($heroBg) ?>" alt="BİKGRUP Referanslar" class="adler-bg-poster" loading="eager">
        <div class="adler-vignette"></div>
        
        <!-- Üst Header -->
        <div class="adler-top-header">
            <a href="<?= BASE_URL ?>/" class="adler-logo">
                <?php if (!empty($siteLogo) && file_exists(UPLOADS_PATH . '/' . $siteLogo)): ?>
                    <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($siteLogo) ?>" alt="<?= htmlspecialchars($siteLogoText) ?>" style="max-height: 64px; width: auto; object-fit: contain;">
                <?php else: ?>
                    <span><?= htmlspecialchars($siteLogoText) ?></span>
                <?php endif; ?>
            </a>

            <nav class="adler-nav-menu">
                <a href="<?= BASE_URL ?>/" class="adler-nav-link"><?= __('nav_home') ?></a>
                <a href="<?= BASE_URL ?>/kurumsal" class="adler-nav-link"><?= __('nav_about') ?></a>
                <a href="<?= BASE_URL ?>/markalar" class="adler-nav-link"><?= __('nav_brands', 'Markalarımız') ?></a>
                <a href="<?= BASE_URL ?>/urunler" class="adler-nav-link"><?= __('nav_products') ?></a>
                <a href="<?= BASE_URL ?>/referanslar" class="adler-nav-link active"><?= $currentLang === 'en' ? 'References' : 'Referanslar' ?></a>
                <a href="<?= BASE_URL ?>/galeri" class="adler-nav-link"><?= $currentLang === 'en' ? 'Gallery' : 'Galeri' ?></a>
                <a href="<?= BASE_URL ?>/blog" class="adler-nav-link"><?= __('nav_blog') ?></a>
                <a href="<?= BASE_URL ?>/iletisim" class="adler-nav-link"><?= __('nav_contact') ?></a>
            </nav>

            <div class="adler-actions-group">
                <button type="button" class="header-quote-btn header-quote-btn-hero" onclick="openQuoteModal()" aria-label="Teklif Al">
                    <span class="quote-btn-text"><?= $currentLang === 'en' ? 'Get a Quote' : 'Teklif Al' ?></span>
                </button>

                <div class="adler-lang-pill">
                    <a href="<?= Lang::switchUrl('tr') ?>" class="adler-lang-item <?= $currentLang === 'tr' ? 'active' : '' ?>">TR</a>
                    <a href="<?= Lang::switchUrl('en') ?>" class="adler-lang-item <?= $currentLang === 'en' ? 'active' : '' ?>">EN</a>
                </div>
            </div>

            <button class="mobile-toggle" aria-label="Menü">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>

        <!-- Hero Ortası: Başlık & Açıklama -->
        <div class="adler-inner-center-hero" style="max-width: 900px; padding: 25px 20px 10px;">
            <span class="adler-hero-badge" style="display: inline-block; margin-bottom: 12px; background: rgba(255,255,255,0.18); backdrop-filter: blur(8px); padding: 5px 16px; border-radius: 20px; font-size: 12px; font-weight: 700; letter-spacing: 1px; color: #fff; text-transform: uppercase; border: 1px solid rgba(255,255,255,0.25);">
                <?= htmlspecialchars($heroBadge) ?>
            </span>
            <h1 class="adler-inner-mega-title" style="margin-bottom: 12px;">
                <?= htmlspecialchars($heroTitle) ?>
            </h1>
            <p class="adler-inner-desc" style="max-width: 740px; margin: 0 auto 18px auto;">
                <?= htmlspecialchars($heroDesc) ?>
            </p>
        </div>

        <div class="inner-hero-bottom-spacer"></div>

    </div>
</section>

<!-- ========================================================================
     KULLANICI TALEBİ: KARE KUTULARDA DÜZ SAYFA - 3'ERLİ REFERANS LOGOLARI
     ======================================================================== -->
<section class="section references-section-main" style="padding: 70px 0 110px 0; background: #ffffff;">
    <div class="container" style="max-width: 1240px;">
        
        <div class="references-grid">
            <?php foreach ($logos as $ref): ?>
                <?php 
                $refImg = $ref['image'] ?? '';
                if (!str_starts_with($refImg, 'http')) {
                    $refImgSrc = BASE_URL . '/public/images/' . ltrim($refImg, '/');
                } else {
                    $refImgSrc = $refImg;
                }
                $refName = $ref['name'] ?? 'Referans';
                ?>
                <div class="reference-square-card" title="<?= htmlspecialchars($refName) ?>">
                    <div class="reference-square-inner">
                        <img src="<?= htmlspecialchars($refImgSrc) ?>" 
                             alt="<?= htmlspecialchars($refName) ?>" 
                             class="reference-logo-img" 
                             loading="lazy">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<style>
/* 3'erli Kare Kutu Grid Düzeni */
.references-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 32px;
}

.reference-square-card {
    position: relative;
    width: 100%;
    aspect-ratio: 1 / 1;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 22px;
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 36px;
    transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.28s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.28s ease;
    overflow: hidden;
}

.reference-square-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 36px rgba(15, 23, 42, 0.09);
    border-color: #cbd5e1;
}

.reference-square-inner {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.reference-logo-img {
    max-width: 82%;
    max-height: 82%;
    width: auto;
    height: auto;
    object-fit: contain;
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    display: block;
}

.reference-square-card:hover .reference-logo-img {
    transform: scale(1.06);
}

/* Responsive */
@media (max-width: 992px) {
    .references-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 22px;
    }
    .reference-square-card {
        padding: 26px;
        border-radius: 18px;
    }
}

@media (max-width: 640px) {
    .references-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    .reference-square-card {
        padding: 20px;
        border-radius: 16px;
    }
    .references-section-main {
        padding: 40px 0 70px 0 !important;
    }
}
</style>

