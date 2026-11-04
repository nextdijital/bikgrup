<?php
/**
 * BIKGRUP - Kurumsal Politikalar, KVKK ve Gizlilik Sayfasi
 */

declare(strict_types=1);

$currentLang = Lang::current();
$activeTab = $activeTab ?? 'gizlilik'; // gizlilik, kvkk, cerez
$siteLogo = Settings::get('site_logo', '');
$siteLogoText = Settings::get('site_logo_text', 'BİK GRUP');
?>

<?php
$hasHero = true;
$GLOBALS['hasHero'] = true;
?>

<!-- ========================================================================
     POLİTİKALAR - ÇİZGİSEL MİMARİ HERO GİRİŞ ALANI (ADLER STYLE)
     ======================================================================== -->
<section class="adler-hero-viewport">
    
    <!-- İkonik Beyaz Çizgisel Çerçeve (Ortalanmış Mimari) -->
    <div class="adler-contour-frame frame-centered">
        
        <!-- Çerçevenin İçiyle Sınırlandırılmış Doğa Arka Planı -->
        <img src="<?= BASE_URL ?>/public/images/nature_hero_bg.jpg" alt="BİKGRUP Kurumsal Politikalar ve Hukuk" class="adler-bg-poster" loading="eager">
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

        <!-- Çerçevenin Merkezi: KULLANICI TALEBİ ORTALANMIŞ BAŞLIK VE POLİTİKA SEKMELERİ -->
        <div class="adler-inner-center-hero">
            <h1 class="adler-inner-mega-title">
                <?= $currentLang === 'en' ? 'Transparency &<br>Trust.' : 'Şeffaflık &<br>Güven.' ?>
            </h1>
            <p class="adler-inner-desc">
                <?= $currentLang === 'en' 
                    ? 'Information regarding personal data protection, privacy terms, and cookies on the BİKGRUP platform.' 
                    : 'BİKGRUP olarak kişisel verilerinizin güvenliğine, gizlilik haklarınıza ve yasal mevzuata tam uyuma azami özen gösteriyoruz.' ?>
            </p>
            <div class="adler-inner-actions">
                <a href="<?= BASE_URL ?>/gizlilik" class="sarikaya-cat-pill <?= $activeTab === 'gizlilik' ? 'is-active' : '' ?>">
                    🔒 <?= $currentLang === 'en' ? 'Privacy Policy' : 'Gizlilik Politikası' ?>
                </a>
                <a href="<?= BASE_URL ?>/kvkk" class="sarikaya-cat-pill <?= $activeTab === 'kvkk' ? 'is-active' : '' ?>">
                    ⚖️ <?= $currentLang === 'en' ? 'GDPR / KVKK' : 'KVKK Metni' ?>
                </a>
                <a href="<?= BASE_URL ?>/cerez-politikasi" class="sarikaya-cat-pill <?= $activeTab === 'cerez' ? 'is-active' : '' ?>">
                    🍪 <?= $currentLang === 'en' ? 'Cookie Policy' : 'Çerez Politikası' ?>
                </a>
            </div>
        </div>

        <!-- Alt Denge Boşluğu -->
        <div style="height: 30px;"></div>

    </div>
</section>

<section class="section" style="padding-top: 40px; padding-bottom: 80px;">
    <div class="container" style="max-width: 960px;">
        <div style="background: #ffffff; border-radius: 24px; border: 1px solid #e2e8f0; padding: clamp(28px, 5vw, 55px); box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);">

            <?php
            // Veritabanından dinamik metinleri çek
            $secPriv = PageSection::get('policy_privacy');
            $secKvkk = PageSection::get('policy_kvkk');
            $secCookie = PageSection::get('policy_cookie');

            if (!function_exists('renderPolicyFormatted')) {
                function renderPolicyFormatted(?string $text): void {
                    if (empty($text)) return;
                    $paras = preg_split('/\n{2,}/', trim($text));
                    foreach ($paras as $p) {
                        $p = trim($p);
                        if ($p === '') continue;
                        if (preg_match('/^\d+\.\s+[^\n]+/u', $p, $m)) {
                            $lines = explode("\n", $p, 2);
                            echo '<h3 style="font-size: 19px; font-weight: 700; color: #0f172a; margin-top: 14px; margin-bottom: 8px;">' . htmlspecialchars($lines[0]) . '</h3>';
                            if (!empty($lines[1])) {
                                echo '<p>' . nl2br(htmlspecialchars(trim($lines[1]))) . '</p>';
                            }
                        } else {
                            echo '<p>' . nl2br(htmlspecialchars($p)) . '</p>';
                        }
                    }
                }
            }
            ?>

            <?php if ($activeTab === 'gizlilik'): ?>
                <!-- GİZLİLİK POLİTİKASI -->
                <h2 style="font-size: 26px; font-weight: 800; color: #0f172a; margin-bottom: 20px;">
                    <?= htmlspecialchars(($currentLang === 'en' && !empty($secPriv['title_en'])) ? $secPriv['title_en'] : ($secPriv['title_tr'] ?? 'Gizlilik Politikası')) ?>
                </h2>
                <p style="color: #64748b; font-size: 14px; margin-bottom: 24px;">
                    <em><?= $currentLang === 'en' ? 'Last Updated: October 2026' : 'Son Güncelleme: Ekim 2026' ?></em>
                </p>
                <div style="color: #334155; font-size: 15px; line-height: 1.8; display: flex; flex-direction: column; gap: 18px;">
                    <?php renderPolicyFormatted(($currentLang === 'en' && !empty($secPriv['body_en'])) ? $secPriv['body_en'] : ($secPriv['body_tr'] ?? '')) ?>
                </div>

            <?php elseif ($activeTab === 'kvkk'): ?>
                <!-- KVKK AYDINLATMA METNİ -->
                <h2 style="font-size: 26px; font-weight: 800; color: #0f172a; margin-bottom: 20px;">
                    <?= htmlspecialchars(($currentLang === 'en' && !empty($secKvkk['title_en'])) ? $secKvkk['title_en'] : ($secKvkk['title_tr'] ?? '6698 Sayılı KVKK Uyarınca Aydınlatma Metni')) ?>
                </h2>
                <p style="color: #64748b; font-size: 14px; margin-bottom: 24px;">
                    <em><?= $currentLang === 'en' ? 'Data Controller: BİK GRUP Tarım ve İhracat' : 'Veri Sorumlusu: BİK GRUP Tarım ve İhracat' ?></em>
                </p>
                <div style="color: #334155; font-size: 15px; line-height: 1.8; display: flex; flex-direction: column; gap: 18px;">
                    <?php renderPolicyFormatted(($currentLang === 'en' && !empty($secKvkk['body_en'])) ? $secKvkk['body_en'] : ($secKvkk['body_tr'] ?? '')) ?>
                </div>

            <?php else: ?>
                <!-- ÇEREZ POLİTİKASI -->
                <h2 style="font-size: 26px; font-weight: 800; color: #0f172a; margin-bottom: 20px;">
                    <?= htmlspecialchars(($currentLang === 'en' && !empty($secCookie['title_en'])) ? $secCookie['title_en'] : ($secCookie['title_tr'] ?? 'Çerez (Cookie) Politikası')) ?>
                </h2>
                <p style="color: #64748b; font-size: 14px; margin-bottom: 24px;">
                    <em><?= $currentLang === 'en' ? 'BİK GRUP Online Platforms' : 'BİK GRUP Çevrimiçi Hizmetleri' ?></em>
                </p>
                <div style="color: #334155; font-size: 15px; line-height: 1.8; display: flex; flex-direction: column; gap: 18px;">
                    <?php renderPolicyFormatted(($currentLang === 'en' && !empty($secCookie['body_en'])) ? $secCookie['body_en'] : ($secCookie['body_tr'] ?? '')) ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>
