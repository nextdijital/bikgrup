<?php
/**
 * BİKGRUP - Grup Markası Detay Sayfası
 */

declare(strict_types=1);

$currentLang = Lang::current();
$siteLogo = Settings::get('site_logo', '');
$siteLogoText = Settings::get('site_logo_text', 'BİK GRUP');
?>

<!-- ========================================================================
     MARKA DETAY - ADLER STYLE MİNİMAL HERO GİRİŞ ALANI
     ======================================================================== -->
<section class="adler-hero-viewport inner-hero-viewport">
    
    <div class="adler-contour-frame frame-centered inner-hero-frame">
        
        <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($brand['image']) ?>" alt="<?= htmlspecialchars($brand['name']) ?>" class="adler-bg-poster" loading="eager">
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
                <a href="<?= BASE_URL ?>/kurumsal" class="adler-nav-link active"><?= __('nav_about') ?></a>
                <a href="<?= BASE_URL ?>/markalar" class="adler-nav-link"><?= __('nav_brands', 'Markalarımız') ?></a>
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

        <!-- Hero Ortası: Marka Başlığı -->
        <div class="adler-inner-center-hero" style="max-width: 860px; padding: 20px 20px 10px;">
            <h1 class="adler-inner-mega-title" style="margin-bottom: 8px;">
                <?= htmlspecialchars($brand['name']) ?>
            </h1>
            <p class="adler-inner-desc" style="max-width: 680px; margin: 0 auto 16px auto;">
                <?= htmlspecialchars($brand['subtitle_' . $currentLang] ?? $brand['subtitle_tr']) ?>
            </p>
        </div>

        <div class="inner-hero-bottom-spacer"></div>

    </div>
</section>

<!-- ========================================================================
     MARKA DETAYLI AÇIKLAMA VE FAALİYET ALANI
     ======================================================================== -->
<section class="section" style="padding: 70px 0 60px 0; background: #ffffff;">
    <div class="container" style="max-width: 1100px;">
        
        <!-- Breadcrumb -->
        <div class="detail-breadcrumb" style="margin-bottom: 35px;">
            <a href="<?= BASE_URL ?>/"><?= __('nav_home') ?></a>
            <span>/</span>
            <a href="<?= BASE_URL ?>/kurumsal"><?= __('nav_about') ?></a>
            <span>/</span>
            <span class="active-crumb"><?= htmlspecialchars($brand['name']) ?></span>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1.15fr; gap: 50px; align-items: center;" class="brand-detail-split">
            
            <!-- Sol: Büyük Marka Görseli (Estetik Çerçeveli) -->
            <div class="reveal" style="position: relative;">
                <div style="border-radius: 24px; overflow: hidden; box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.15); border: 1.5px solid #e2e8f0; aspect-ratio: 4/3;">
                    <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($brand['image']) ?>" 
                         alt="<?= htmlspecialchars($brand['name']) ?>" 
                         style="width: 100%; height: 100%; object-fit: cover; display: block;">
                </div>
                <!-- Marka Rozeti -->
                <div style="position: absolute; bottom: -16px; right: 20px; background: #15803d; color: #ffffff; padding: 10px 22px; border-radius: 9999px; font-weight: 800; font-size: 13px; box-shadow: 0 8px 20px rgba(21, 128, 61, 0.35); letter-spacing: 0.5px;">
                    <?= $currentLang === 'en' ? 'BİK GRUP BRAND ASSURANCE' : 'BİK GRUP MARKA GÜVENCESİ' ?>
                </div>
            </div>

            <!-- Sağ: Orta Uzunlukta Açıklama Metni ve Öne Çıkan Özellikler -->
            <div class="reveal reveal-delay-1">
                <span style="font-size: 13px; font-weight: 800; color: #15803d; letter-spacing: 1.5px; text-transform: uppercase;">
                    <?= $currentLang === 'en' ? 'CORPORATE PROFILE & VISION' : 'KURUMSAL PROFİL & VİZYON' ?>
                </span>
                <h2 style="font-family: var(--font-heading); font-size: 34px; font-weight: 800; color: #0f172a; margin: 8px 0 18px 0; line-height: 1.25;">
                    <?= htmlspecialchars($brand['subtitle_' . $currentLang] ?? $brand['subtitle_tr']) ?>
                </h2>
                
                <div style="font-size: 15.5px; color: #475569; line-height: 1.75; margin-bottom: 26px;">
                    <?= nl2br(htmlspecialchars($brand['desc_' . $currentLang] ?? $brand['desc_tr'])) ?>
                </div>

                <!-- 4 Maddelik Nitelikler Listesi -->
                <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 30px;">
                    <?php 
                    $feats = $brand['features_' . $currentLang] ?? $brand['features_tr'];
                    foreach ($feats as $feat): 
                    ?>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 24px; height: 24px; background: #dcfce7; color: #15803d; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 900; flex-shrink: 0;">
                                ✓
                            </div>
                            <span style="font-size: 14.5px; font-weight: 700; color: #1e293b;">
                                <?= htmlspecialchars($feat) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Aksiyon Butonları -->
                <div style="display: flex; gap: 14px; flex-wrap: wrap;">
                    <button type="button" onclick="openQuoteModal('<?= htmlspecialchars(addslashes($brand['name'])) ?>')" class="btn btn-primary" style="padding: 14px 34px; font-weight: 700; border-radius: 9999px;">
                        <?= $currentLang === 'en' ? 'Get a Quote' : 'Hemen Teklif Al' ?>
                    </button>
                    <a href="<?= BASE_URL ?>/iletisim?konu=<?= urlencode($brand['name'] . ' İletişim') ?>" class="btn btn-outline" style="padding: 14px 30px; font-weight: 700; border-radius: 9999px;">
                        <?= __('btn_contact_us') ?>
                    </a>
                </div>

            </div>

        </div>

    </div>
</section>

<!-- ========================================================================
     DİĞER GRUP MARKALARIMIZ (YÖNLENDİRME BAĞLANTILI KARTLAR)
     KULLANICI TALEBİ: DİĞER MARKA YAZILARINA YÖNLENDİRME BAĞLANTILI ALAN
     ======================================================================== -->
<section class="section" style="padding: 70px 0; background: #f8fafc; border-top: 1px solid #e2e8f0;">
    <div class="container" style="max-width: 1150px;">
        
        <div style="text-align: center; max-width: 650px; margin: 0 auto 50px auto;" class="reveal">
            <span style="font-size: 13px; font-weight: 800; color: #15803d; letter-spacing: 1.5px; text-transform: uppercase;">
                <?= $currentLang === 'en' ? 'OUR SISTER BRANDS' : 'DİĞER GRUP MARKALARIMIZ' ?>
            </span>
            <h2 style="font-family: var(--font-heading); font-size: 32px; font-weight: 800; color: #0f172a; margin-top: 6px;">
                <?= $currentLang === 'en' ? 'Explore Other BİK GRUP Companies' : 'BİK GRUP Bünyesindeki Diğer Markalarımız' ?>
            </h2>
            <p style="color: #64748b; font-size: 15.5px; margin-top: 8px;">
                <?= $currentLang === 'en' 
                    ? 'Discover our specialized operations from field farming to packaging and global cold chain trade.' 
                    : 'Tarladan sofraya her aşamada uzmanlaşmış diğer grup şirketlerimizi inceleyin.' ?>
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;" class="reveal">
            <?php foreach ($otherBrands as $ob): ?>
                <a href="<?= BASE_URL ?>/markalar/<?= urlencode($ob['slug']) ?>" class="clean-brand-card" style="height: 240px;">
                    <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($ob['image']) ?>" 
                         alt="<?= htmlspecialchars($ob['name']) ?>" 
                         class="clean-brand-bg-img"
                         loading="lazy">
                    <div class="clean-brand-overlay"></div>
                    <h3 class="clean-brand-name" style="font-size: 20px;">
                        <?= htmlspecialchars($ob['name']) ?>
                    </h3>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<style>
.clean-brand-card {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s ease;
}
.clean-brand-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 18px 38px rgba(0, 0, 0, 0.18);
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
    font-size: 20px;
    font-weight: 800;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    text-align: center;
    padding: 12px 20px;
    text-shadow: 0 2px 14px rgba(0, 0, 0, 0.75);
    line-height: 1.3;
    margin: 0;
}
@media (max-width: 860px) {
    .brand-detail-split {
        grid-template-columns: 1fr !important;
        gap: 35px !important;
    }
}
@media (max-width: 768px) {
    .clean-brand-card {
        height: 200px !important;
    }
}
</style>

