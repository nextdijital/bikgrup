<?php
/**
 * BİKGRUP - Genel Grup Markalarımız Sayfası
 */

declare(strict_types=1);

$currentLang = Lang::current();
$siteLogo = Settings::get('site_logo', '');
$siteLogoText = Settings::get('site_logo_text', 'BİK GRUP');
$hasHero = true;
?>

<!-- ========================================================================
     GRUP MARKALARIMIZ - ADLER STYLE MİNİMAL HERO GİRİŞ ALANI
     ======================================================================== -->
<section class="adler-hero-viewport inner-hero-viewport">
    
    <div class="adler-contour-frame frame-centered inner-hero-frame">
        
        <img src="<?= BASE_URL ?>/public/images/nature_hero_bg.jpg" alt="BİKGRUP Markalarımız" class="adler-bg-poster" loading="eager">
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
                <a href="<?= BASE_URL ?>/markalar" class="adler-nav-link active"><?= __('nav_brands', 'Markalarımız') ?></a>
                <a href="<?= BASE_URL ?>/urunler" class="adler-nav-link"><?= __('nav_products') ?></a>
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

        <!-- Hero Ortası: Genel Markalar Başlığı -->
        <div class="adler-inner-center-hero" style="max-width: 900px; padding: 25px 20px 10px;">
            <h1 class="adler-inner-mega-title" style="margin-bottom: 10px;">
                <?= $currentLang === 'en' ? 'Our Group Brands' : 'Grup Markalarımız' ?>
            </h1>
            <p class="adler-inner-desc" style="max-width: 720px; margin: 0 auto 18px auto;">
                <?= $currentLang === 'en' 
                    ? 'Operating across distinct agricultural fields from production and supply to trade and marketing under BİK GRUP.' 
                    : 'BİK GRUP çatısı altında üretimden tedariğe, ticaretten pazarlamaya kadar tarımın farklı alanlarında uzmanlaşmış öncü markalarımız.' ?>
            </p>
        </div>

        <div class="inner-hero-bottom-spacer"></div>

    </div>
</section>

<!-- ========================================================================
     GENEL MARKALAR LİSTESİ (SADE & SADECE ORTALI BEYAZ MARKA İSİMLERİ)
     ======================================================================== -->
<section class="section brands-section-main">
    <div class="container" style="max-width: 1360px;">
        
        <div class="brands-grid-row reveal">
            <?php foreach ($brands as $b): ?>
                <a href="<?= BASE_URL ?>/markalar/<?= urlencode($b['slug']) ?>" class="clean-brand-card">
                    <!-- Arkada Görsel -->
                    <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($b['image']) ?>" 
                         alt="<?= htmlspecialchars($b['name']) ?>" 
                         class="clean-brand-bg-img"
                         loading="lazy">
                    <!-- Siyah Gölge / Karartma Katmanı -->
                    <div class="clean-brand-overlay"></div>
                    <!-- Beyaz Ortalı Marka İsmi -->
                    <h2 class="clean-brand-name">
                        <?= htmlspecialchars($b['name']) ?>
                    </h2>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<style>
.brands-section-main {
    padding: 60px 0 80px 0;
    background: #ffffff;
}
.brands-grid-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    width: 100%;
}
.clean-brand-card {
    position: relative;
    height: 280px;
    border-radius: 20px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s ease;
}
.clean-brand-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.18);
}
.clean-brand-bg-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}
.clean-brand-card:hover .clean-brand-bg-img {
    transform: scale(1.08);
}
.clean-brand-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0, 0, 0, 0.35) 0%, rgba(0, 0, 0, 0.65) 100%);
    transition: background 0.3s ease;
}
.clean-brand-card:hover .clean-brand-overlay {
    background: linear-gradient(180deg, rgba(0, 0, 0, 0.25) 0%, rgba(0, 0, 0, 0.55) 100%);
}
.clean-brand-name {
    position: relative;
    z-index: 2;
    color: #ffffff;
    font-family: var(--font-heading);
    font-size: 22px;
    font-weight: 800;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    text-align: center;
    padding: 12px 24px;
    text-shadow: 0 2px 14px rgba(0, 0, 0, 0.75);
    line-height: 1.3;
    margin: 0;
}

@media (min-width: 769px) {
    .inner-hero-viewport {
        min-height: 440px;
    }
    .inner-hero-frame {
        min-height: 420px;
    }
    .inner-hero-bottom-spacer {
        height: 25px;
    }
}

@media (max-width: 1024px) {
    .brands-grid-row {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
}

@media (max-width: 768px) {
    .inner-hero-viewport {
        min-height: 100vh !important;
        min-height: 100dvh !important;
        height: auto !important;
        padding: 12px !important;
        box-sizing: border-box !important;
    }
    .inner-hero-frame {
        min-height: calc(100vh - 24px) !important;
        min-height: calc(100dvh - 24px) !important;
        height: auto !important;
        border-radius: 26px !important;
        padding: 20px 16px !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: space-between !important;
        box-sizing: border-box !important;
    }
    .inner-hero-frame .adler-top-header {
        margin-bottom: 0 !important;
    }
    .inner-hero-frame .adler-logo img {
        max-height: 48px !important;
    }
    .inner-hero-frame .adler-inner-center-hero {
        flex: 1 !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
        align-items: center !important;
        padding: 20px 5px !important;
        margin: auto !important;
    }
    .inner-hero-frame .adler-inner-mega-title {
        font-size: clamp(26px, 7vw, 36px) !important;
        margin-bottom: 10px !important;
        line-height: 1.25 !important;
    }
    .inner-hero-frame .adler-inner-desc {
        font-size: 14px !important;
        line-height: 1.5 !important;
        margin: 0 auto !important;
        opacity: 0.95;
    }
    .inner-hero-bottom-spacer {
        height: 25px !important;
        display: block !important;
    }
    .brands-section-main {
        padding: 24px 0 60px 0 !important;
    }
    .brands-grid-row {
        grid-template-columns: 1fr !important;
        gap: 16px !important;
    }
    .clean-brand-card {
        height: 200px !important;
        border-radius: 16px !important;
    }
    .clean-brand-name {
        font-size: 20px !important;
        padding: 8px 16px !important;
    }
}
</style>

