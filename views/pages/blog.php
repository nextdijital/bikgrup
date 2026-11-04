<?php
/**
 * BİKGRUP - Blog ve Haberler Listesi
 * SEO Uyumlu Temiz URL'ler
 */

declare(strict_types=1);

$currentLang = Lang::current();
$blogHero = PageSection::get('blog_page_hero') ?? [];
$blogHeroExtra = $blogHero['extra'] ?? [];
$siteLogo = Settings::get('site_logo', '');
$siteLogoText = Settings::get('site_logo_text', 'BİK GRUP');
?>

<?php
$hasHero = true;
$GLOBALS['hasHero'] = true;
?>

<!-- ========================================================================
     BLOG & HABERLER - ÇİZGİSEL MİMARİ HERO GİRİŞ ALANI (ADLER STYLE)
     ======================================================================== -->
<section class="adler-hero-viewport">
    
    <!-- İkonik Beyaz Çizgisel Çerçeve (Ortalanmış Mimari) -->
    <div class="adler-contour-frame frame-centered">
        
        <!-- Çerçevenin İçiyle Sınırlandırılmış Taze Hasat & Modern Tarım Arka Planı -->
        <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($blogHeroExtra['bg_image'] ?? 'blog_hero_new.jpg') ?>" alt="BİKGRUP Blog ve Güncel Haberler" class="adler-bg-poster" loading="eager">
        <div class="adler-vignette"></div>
        
        <!-- Çerçevenin Üst Sınırı: Entegre Minimal Header -->
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
                <a href="<?= BASE_URL ?>/galeri" class="adler-nav-link"><?= $currentLang === 'en' ? 'Gallery' : 'Galeri' ?></a>
                <a href="<?= BASE_URL ?>/blog" class="adler-nav-link active"><?= __('nav_blog') ?></a>
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

        <!-- Çerçevenin Merkezi: KULLANICI TALEBİ YAN YANA BAŞLIK VE ORTALANMIŞ İÇERİK -->
        <div class="adler-inner-center-hero" style="max-width: 960px;">
            <h1 class="adler-inner-mega-title">
                <?= !empty($blogHero['title_' . $currentLang]) ? nl2br(htmlspecialchars($blogHero['title_' . $currentLang])) : (!empty($blogHero['title_tr']) ? nl2br(htmlspecialchars($blogHero['title_tr'])) : ($currentLang === 'en' ? 'Knowledge &amp; Vision.' : 'Bilgi &amp; Vizyon.')) ?>
            </h1>
            <p class="adler-inner-desc">
                <?= htmlspecialchars($blogHero['subtitle_' . $currentLang] ?? ($blogHero['subtitle_tr'] ?? ($currentLang === 'en' 
                    ? 'Fresh perspectives, insights, and global industry updates on fresh produce export, modern agricultural methods, and cold chain excellence.' 
                    : 'Taze meyve ve sebze ihracatı, sürdürülebilir tarım modelleri ve uluslararası kalite standartları hakkında güncel içerikler.'))) ?>
            </p>
            <div class="adler-inner-actions">
                <a href="#yazilar" class="about-pill-btn" style="border-color: #ffffff; color: #ffffff; background: rgba(255,255,255,0.15); backdrop-filter: blur(8px);">
                    <?= $currentLang === 'en' ? 'Read Articles' : 'Yazıları İncele' ?>
                </a>
            </div>
        </div>

        <!-- Alt Denge Boşluğu -->
        <div style="height: 30px;"></div>

    </div>
</section>

<section class="section" id="yazilar" style="padding-top: 40px; scroll-margin-top: 50px;">
    <div class="container">
        
        <div class="blog-grid">
            <?php foreach ($posts as $index => $post): ?>
                <article class="blog-card reveal reveal-delay-<?= ($index % 3) + 1 ?>">
                    <div class="blog-card-img">
                        <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($post['image'] ?: 'blog_cold_chain.png') ?>" alt="<?= htmlspecialchars($post['title_' . $currentLang] ?? $post['title_tr']) ?>" loading="lazy">
                    </div>
                    <div class="blog-card-body">
                        <div class="blog-meta">
                            <span>⏱ <?= htmlspecialchars($post['read_time']) ?></span>
                            <span>•</span>
                            <span><?= date('d.m.Y', strtotime($post['created_at'])) ?></span>
                            <span>•</span>
                            <span><?= htmlspecialchars($post['author']) ?></span>
                        </div>
                        <h2 class="blog-card-title"><?= htmlspecialchars($post['title_' . $currentLang] ?? $post['title_tr']) ?></h2>
                        <p class="blog-card-excerpt"><?= htmlspecialchars($post['excerpt_' . $currentLang] ?? $post['excerpt_tr']) ?></p>
                        <a href="<?= BASE_URL ?>/blog/<?= urlencode($post['slug']) ?>" class="blog-read-more">
                            <?= __('btn_read_more') ?>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

    </div>
</section>
