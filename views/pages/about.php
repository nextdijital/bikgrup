<?php
/**
 * BİKGRUP - Kurumsal & Hakkımızda Sayfası
 * SEO Uyumlu Temiz Tasarım
 */

declare(strict_types=1);

$currentLang = Lang::current();

$aboutHero = PageSection::get('about_hero') ?? [];
$aboutHeroExtra = $aboutHero['extra'] ?? [];
$story = PageSection::get('about_story') ?? [];
$mission = PageSection::get('mission_vision') ?? [];
$mvExtra = $mission['extra'] ?? [];
$aboutCerts = PageSection::get('about_certs') ?? [];
$certsExtra = $aboutCerts['extra'] ?? [];
$siteLogo = Settings::get('site_logo', '');
$siteLogoText = Settings::get('site_logo_text', 'BİK GRUP');
?>

<?php
$hasHero = true;
$GLOBALS['hasHero'] = true;
?>

<!-- ========================================================================
     KURUMSAL - ÇİZGİSEL MİMARİ HERO GİRİŞ ALANI (ORTALANMIŞ & SOL KARTSIZ)
     ======================================================================== -->
<section class="adler-hero-viewport">
    
    <!-- İkonik Beyaz Çizgisel Çerçeve (Ortalanmış Mimari) -->
    <div class="adler-contour-frame frame-centered">
        
        <!-- Çerçevenin İçiyle Sınırlandırılmış Modern Tesis & Teknoloji Arka Planı -->
        <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($aboutHeroExtra['bg_image'] ?? 'facility_automation.jpg') ?>" alt="BİKGRUP Modern Tesis ve İhracat" class="adler-bg-poster" loading="eager">
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

        <!-- Çerçevenin Merkezi: KULLANICI TALEBİ ORTALANMIŞ YAZILAR (SOL KARTLAR KALDIRILDI) -->
        <div class="adler-inner-center-hero">
            <h1 class="adler-inner-mega-title">
                <?= $currentLang === 'en' ? 'Our Roots in the Soil, Our Vision Worldwide.' : (!empty($aboutHero['title_tr']) ? nl2br(htmlspecialchars($aboutHero['title_tr'])) : 'Köklerimiz Toprakta,<br>Hedefimiz Dünyada.') ?>
            </h1>
            <p class="adler-inner-desc">
                <?= $currentLang === 'en' 
                    ? 'BİK GRUP | Trusting the Future from the Soil.' 
                    : htmlspecialchars($aboutHero['subtitle_tr'] ?? 'BİK GRUP | Topraktan Geleceğe Güvenle.') ?>
            </p>
            <div class="adler-inner-actions">
                <a href="<?= htmlspecialchars($aboutHeroExtra['btn_link'] ?? '#tarihce') ?>" class="about-pill-btn" style="border-color: #ffffff; color: #ffffff; background: rgba(255,255,255,0.15); backdrop-filter: blur(8px);">
                    <?= $currentLang === 'en' ? 'Our History' : htmlspecialchars($aboutHeroExtra['btn_text'] ?? 'Tarihçemiz') ?>
                </a>
            </div>
        </div>

        <!-- Alt Denge Boşluğu -->
        <div style="height: 30px;"></div>

    </div>
</section>

<!-- ========================================================================
     KULLANICI REFERANSI: BÜYÜK DÜZ METİN VE MİSYON & VİZYON ALANI
     ======================================================================== -->
<section class="about-statement-section" id="tarihce">
    <div class="about-statement-container reveal">
        
        <!-- Üst Küçük Soru/Etiket -->
        <div class="about-statement-tag">
            <?= htmlspecialchars($story['subtitle_tr'] ?? ($currentLang === 'en' ? 'Our History' : 'Tarihçemiz')) ?>
        </div>

        <!-- Birebir Görseldeki Büyük Kalın Düz Metin (Renkli Vurgular) -->
        <h2 class="about-statement-lead">
            <?= !empty($story['title_tr']) ? nl2br(htmlspecialchars($story['title_tr'])) : ($currentLang === 'en' 
                ? 'Founded in 2018, BİK GRUP began its agricultural journey as a producer with Zeliha Sultan Farm.' 
                : 'Temelleri 2018 yılında atılan BİK GRUP, tarım sektöründeki yolculuğuna <span class="text-green">Zeliha Sultan Çiftliği</span> ile üretici olarak başlamıştır.') ?>
        </h2>

        <!-- KULLANICI TALEBİ: HİKAYEMİZİ ANLATAN NORMAL FORMATTA DÜZ PARAGRAF -->
        <div class="about-statement-story" style="display: flex; flex-direction: column; gap: 18px; font-size: 16.5px; line-height: 1.8; color: #334155;">
            <?php if (!empty($story['body_tr'])): ?>
                <p style="margin: 0;"><?= nl2br(htmlspecialchars($story['body_tr'])) ?></p>
            <?php else: ?>
                <p style="margin: 0;">
                    <?= $currentLang === 'en' 
                        ? 'Our operations, which started with citrus and grain production, expanded in 2021 by venturing into the supply and trade of agricultural raw materials.' 
                        : 'Narenciye ve tahıl üretimiyle başlayan faaliyetlerimiz, 2021 yılında tarımsal ham madde tedariği ve ticaretine uzanarak büyümeye devam etmiştir.' ?>
                </p>
                <p style="margin: 0;">
                    <?= $currentLang === 'en' 
                        ? 'Today, under the BİK GRUP umbrella, we operate in various fields of agriculture from production to supply, trade, and marketing with our brands Köylü Tarım, Zeliha Sultan Çiftliği, İnci Fresh, and Lara Citrus.' 
                        : 'Bugün BİK GRUP çatısı altında <strong>Köylü Tarım</strong>, <strong>Zeliha Sultan Çiftliği</strong>, <strong>İnci Fresh</strong> ve <strong>Lara Citrus</strong> markalarımızla üretimden tedariğe, ticaretten pazarlamaya kadar tarımın farklı alanlarında faaliyet göstermekteyiz.' ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- İKİ AYRI RENKLİ KUTUCUK, İÇİNDE ÇİZGİSEL KARE VE SADECE YAZI (İKONSUZ) -->
        <div class="mv-boxes-grid reveal reveal-delay-1" style="margin-top: 45px;">
            
            <!-- Misyon Kutusu -->
            <div class="mv-color-box mv-box-mission">
                <div class="mv-box-inner-frame">
                    <h3 class="mv-box-clean-title">
                        <?= htmlspecialchars($mvExtra['mission_title'] ?? ($currentLang === 'en' ? 'OUR MISSION' : 'MİSYONUMUZ')) ?>
                    </h3>
                    <p class="mv-box-desc">
                        <?= htmlspecialchars($mvExtra['mission_desc'] ?? ($currentLang === 'en' 
                            ? 'Connecting the abundance of the soil with technology, quality, and trust to create a sustainable value chain extending from producer to consumer.' 
                            : 'Toprağın bereketini teknoloji, kalite ve güvenle buluşturarak, üreticiden tüketiciye uzanan sürdürülebilir bir değer zinciri oluşturmak.')) ?>
                    </p>
                </div>
            </div>

            <!-- Vizyon Kutusu -->
            <div class="mv-color-box mv-box-vision">
                <div class="mv-box-inner-frame">
                    <h3 class="mv-box-clean-title">
                        <?= htmlspecialchars($mvExtra['vision_title'] ?? ($currentLang === 'en' ? 'OUR VISION' : 'VİZYONUMUZ')) ?>
                    </h3>
                    <p class="mv-box-desc">
                        <?= htmlspecialchars($mvExtra['vision_desc'] ?? ($currentLang === 'en' 
                            ? 'Combining the strength of agriculture with modern trade understanding to become a reliable, strong, and pioneering group company in national and international markets.' 
                            : 'Tarımın gücünü modern ticaret anlayışıyla birleştirerek, ulusal ve uluslararası pazarlarda güvenilir, güçlü ve öncü bir grup şirketi olmak.')) ?>
                    </p>
                </div>
            </div>

        </div>

    </div>
</section>

<?php 
$brandsSec = PageSection::get('home_brands') ?? [];
$brandsExtra = $brandsSec['extra'] ?? [];
?>
<!-- GRUP MARKALARIMIZ (ANASAYFA İLE BİREBİR AYNI VİTRİN VE TEK YERDEN YÖNETİM) -->
<section class="section brands-dark-showcase" style="background: #f8fafc; padding: 70px 0 !important; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
    <div class="brands-wide-container">
        
        <!-- Giriş Ekranındaki İkonik Beyaz Çizgisel Çerçeve Mimarisi (Ekranı Genişçe Saran) -->
        <div class="brands-contour-frame reveal">
            <div class="brands-frame-header">
                <span class="brands-frame-badge">
                    <?= htmlspecialchars($brandsSec['subtitle_' . $currentLang] ?? ($brandsSec['subtitle_tr'] ?? ($currentLang === 'en' ? 'OUR COMPANIES & BRANDS' : 'GRUP ŞİRKETLERİMİZ & MARKALARIMIZ'))) ?>
                </span>
                <h2 class="brands-frame-title">
                    <?= htmlspecialchars($brandsSec['title_' . $currentLang] ?? ($brandsSec['title_tr'] ?? ($currentLang === 'en' ? 'BİK GRUP Brands' : 'Grup Markalarımız'))) ?>
                </h2>
                <p class="brands-frame-subtext">
                    <?= htmlspecialchars($brandsSec['body_' . $currentLang] ?? ($brandsSec['body_tr'] ?? ($currentLang === 'en' ? 'Operating across distinct agricultural fields from production and supply to trade and marketing under BİK GRUP.' : 'BİK GRUP çatısı altında üretimden tedariğe, ticaretten pazarlamaya kadar tarımın her aşamasında faaliyet gösteren markalarımız.'))) ?>
                </p>
            </div>

            <!-- Yan Yana 4 Marka (Sade, Arkada Görsel, Siyah Gölge, Beyaz Ortalı Başlık) -->
            <div class="brands-dark-row">
                <!-- 1. Köylü Tarım -->
                <a href="<?= htmlspecialchars(resolveUrl($brandsExtra['brand1_link'] ?? '/markalar/koylu-tarim')) ?>" class="home-brand-full-card">
                    <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($brandsExtra['brand1_image'] ?? 'fresh_vegetables.jpg') ?>" alt="<?= htmlspecialchars($brandsExtra['brand1_name'] ?? 'Köylü Tarım') ?>" class="home-brand-bg" loading="lazy">
                    <div class="home-brand-overlay"></div>
                    <span class="home-brand-name"><?= htmlspecialchars($brandsExtra['brand1_name'] ?? 'KÖYLÜ TARIM') ?></span>
                </a>

                <!-- 2. Zeliha Sultan Çiftliği -->
                <a href="<?= htmlspecialchars(resolveUrl($brandsExtra['brand2_link'] ?? '/markalar/zeliha-sultan')) ?>" class="home-brand-full-card">
                    <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($brandsExtra['brand2_image'] ?? 'blog_agriculture_hero.jpg') ?>" alt="<?= htmlspecialchars($brandsExtra['brand2_name'] ?? 'Zeliha Sultan') ?>" class="home-brand-bg" loading="lazy">
                    <div class="home-brand-overlay"></div>
                    <span class="home-brand-name"><?= htmlspecialchars($brandsExtra['brand2_name'] ?? 'ZELİHA SULTAN') ?></span>
                </a>

                <!-- 3. İnci Fresh -->
                <a href="<?= htmlspecialchars(resolveUrl($brandsExtra['brand3_link'] ?? '/markalar/inci-fresh')) ?>" class="home-brand-full-card">
                    <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($brandsExtra['brand3_image'] ?? 'fresh_fruits.jpg') ?>" alt="<?= htmlspecialchars($brandsExtra['brand3_name'] ?? 'İnci Fresh') ?>" class="home-brand-bg" loading="lazy">
                    <div class="home-brand-overlay"></div>
                    <span class="home-brand-name"><?= htmlspecialchars($brandsExtra['brand3_name'] ?? 'İNCİ FRESH') ?></span>
                </a>

                <!-- 4. Lara Citrus -->
                <a href="<?= htmlspecialchars(resolveUrl($brandsExtra['brand4_link'] ?? '/markalar/lara-citrus')) ?>" class="home-brand-full-card">
                    <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($brandsExtra['brand4_image'] ?? 'citrus_showcase.jpg') ?>" alt="<?= htmlspecialchars($brandsExtra['brand4_name'] ?? 'Lara Citrus') ?>" class="home-brand-bg" loading="lazy">
                    <div class="home-brand-overlay"></div>
                    <span class="home-brand-name"><?= htmlspecialchars($brandsExtra['brand4_name'] ?? 'LARA CITRUS') ?></span>
                </a>
            </div>
        </div>

    </div>
</section>

<!-- Kalite ve Sertifikalar Şeridi -->
<section class="section" id="sertifikalar" style="background: #fbfdf9; border-top: 1px solid #eef2f6;">
    <div class="container" style="text-align: center;">
        <h2 style="font-family: var(--font-heading); font-size: 32px; font-weight: 800; margin-bottom: 12px; color: var(--theme-text);">
            <?= htmlspecialchars($aboutCerts['title_tr'] ?? __('sec_certifications', 'Uluslararası Kalite & Gıda Güvenliği Sertifikalarımız')) ?>
        </h2>
        <p style="color: #64748b; font-size: 15px; max-width: 620px; margin: 0 auto 35px auto;">
            <?= htmlspecialchars($aboutCerts['body_tr'] ?? 'Uluslararası gıda güvenliği, izlenebilirlik ve sürdürülebilir tarım standartlarına tam uyumla belgelendirilmiş üretim tesislerimiz.') ?>
        </p>

        <!-- DEMO RESMİ BELGE GÖRSELLERİ -->
        <div class="cert-docs-grid reveal">
            <!-- 1. GlobalGAP -->
            <div class="cert-doc-card">
                <div class="cert-doc-preview">
                    <div class="cert-paper-mockup">
                        <div class="cert-paper-seal">🛡️</div>
                        <div class="cert-paper-title"><?= htmlspecialchars($certsExtra['cert1_title'] ?? 'GLOBALG.A.P.') ?></div>
                        <div class="cert-paper-lines">
                            <div class="cert-paper-line" style="width: 85%;"></div>
                            <div class="cert-paper-line" style="width: 70%;"></div>
                            <div class="cert-paper-line" style="width: 90%;"></div>
                            <div class="cert-paper-line" style="width: 60%;"></div>
                        </div>
                        <div class="cert-paper-stamp"><?= htmlspecialchars($certsExtra['cert1_stamp'] ?? '✓ VERIFIED') ?></div>
                    </div>
                </div>
                <div class="cert-doc-body">
                    <h3 class="cert-doc-name"><?= htmlspecialchars($certsExtra['cert1_name'] ?? 'GlobalG.A.P. IFA') ?></h3>
                    <p class="cert-doc-standard"><?= htmlspecialchars($certsExtra['cert1_standard'] ?? 'İyi Tarım Uygulamaları ve Uluslararası Çiftlik Güvenliği Standardı') ?></p>
                    <div class="cert-doc-status"><?= htmlspecialchars($certsExtra['cert1_status'] ?? '🛡️ Akredite İhracat Sertifikalı') ?></div>
                </div>
            </div>

            <!-- 2. BRCGS -->
            <div class="cert-doc-card">
                <div class="cert-doc-preview">
                    <div class="cert-paper-mockup">
                        <div class="cert-paper-seal">🛡️</div>
                        <div class="cert-paper-title"><?= htmlspecialchars($certsExtra['cert2_title'] ?? 'BRCGS FOOD') ?></div>
                        <div class="cert-paper-lines">
                            <div class="cert-paper-line" style="width: 90%;"></div>
                            <div class="cert-paper-line" style="width: 75%;"></div>
                            <div class="cert-paper-line" style="width: 80%;"></div>
                            <div class="cert-paper-line" style="width: 65%;"></div>
                        </div>
                        <div class="cert-paper-stamp"><?= htmlspecialchars($certsExtra['cert2_stamp'] ?? 'GRADE AA') ?></div>
                    </div>
                </div>
                <div class="cert-doc-body">
                    <h3 class="cert-doc-name"><?= htmlspecialchars($certsExtra['cert2_name'] ?? 'BRCGS Food Safety') ?></h3>
                    <p class="cert-doc-standard"><?= htmlspecialchars($certsExtra['cert2_standard'] ?? 'Küresel Gıda Güvenliği Girişimi (GFSI) Onaylı Paketleme & Hijyen') ?></p>
                    <div class="cert-doc-status"><?= htmlspecialchars($certsExtra['cert2_status'] ?? '🛡️ A Sınıfı Tesis Akreditasyonu') ?></div>
                </div>
            </div>

            <!-- 3. IFS Food -->
            <div class="cert-doc-card">
                <div class="cert-doc-preview">
                    <div class="cert-paper-mockup">
                        <div class="cert-paper-seal">🛡️</div>
                        <div class="cert-paper-title"><?= htmlspecialchars($certsExtra['cert3_title'] ?? 'IFS FOOD v7') ?></div>
                        <div class="cert-paper-lines">
                            <div class="cert-paper-line" style="width: 80%;"></div>
                            <div class="cert-paper-line" style="width: 85%;"></div>
                            <div class="cert-paper-line" style="width: 60%;"></div>
                            <div class="cert-paper-line" style="width: 75%;"></div>
                        </div>
                        <div class="cert-paper-stamp"><?= htmlspecialchars($certsExtra['cert3_stamp'] ?? 'HIGHER LEVEL') ?></div>
                    </div>
                </div>
                <div class="cert-doc-body">
                    <h3 class="cert-doc-name"><?= htmlspecialchars($certsExtra['cert3_name'] ?? 'IFS Food Standard') ?></h3>
                    <p class="cert-doc-standard"><?= htmlspecialchars($certsExtra['cert3_standard'] ?? 'Avrupa Perakende Birliği Yüksek Seviye Kalite ve İzlenebilirlik') ?></p>
                    <div class="cert-doc-status"><?= htmlspecialchars($certsExtra['cert3_status'] ?? '🛡️ Süpermarket Onaylı Tedarikçi') ?></div>
                </div>
            </div>

            <!-- 4. ISO 22000 -->
            <div class="cert-doc-card">
                <div class="cert-doc-preview">
                    <div class="cert-paper-mockup">
                        <div class="cert-paper-seal">🛡️</div>
                        <div class="cert-paper-title"><?= htmlspecialchars($certsExtra['cert4_title'] ?? 'ISO 22000') ?></div>
                        <div class="cert-paper-lines">
                            <div class="cert-paper-line" style="width: 75%;"></div>
                            <div class="cert-paper-line" style="width: 90%;"></div>
                            <div class="cert-paper-line" style="width: 80%;"></div>
                            <div class="cert-paper-line" style="width: 70%;"></div>
                        </div>
                        <div class="cert-paper-stamp"><?= htmlspecialchars($certsExtra['cert4_stamp'] ?? 'ISO 22000:2018') ?></div>
                    </div>
                </div>
                <div class="cert-doc-body">
                    <h3 class="cert-doc-name"><?= htmlspecialchars($certsExtra['cert4_name'] ?? 'ISO 22000:2018') ?></h3>
                    <p class="cert-doc-standard"><?= htmlspecialchars($certsExtra['cert4_standard'] ?? 'Tarladan Tüketime Gıda Güvenliği Yönetim Sistemi Standardı') ?></p>
                    <div class="cert-doc-status"><?= htmlspecialchars($certsExtra['cert4_status'] ?? '🛡️ Entegre Kalite Yönetimi') ?></div>
                </div>
            </div>
        </div>
    </div>
</section>
