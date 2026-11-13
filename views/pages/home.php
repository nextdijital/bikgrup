<?php
/**
 * BİKGRUP - Anasayfa Şablonu
 * Çizgisel Çerçeve & Global Dünya İhracat Haritası
 */

declare(strict_types=1);

$currentLang = Lang::current();

$heroSec = PageSection::get('home_hero') ?? [];
$heroExtra = $heroSec['extra'] ?? [];

$featSec = PageSection::get('home_featured') ?? [];
$featExtra = $featSec['extra'] ?? [];

$mapSec = PageSection::get('home_map') ?? [];
$mapExtra = $mapSec['extra'] ?? [];

$brandsSec = PageSection::get('home_brands') ?? [];
$brandsExtra = $brandsSec['extra'] ?? [];

$facSec = PageSection::get('home_facility') ?? [];
$facExtra = $facSec['extra'] ?? [];

$blogSec = PageSection::get('home_blog') ?? [];
$blogExtra = $blogSec['extra'] ?? [];

$refSec = PageSection::get('home_references') ?? [];
$refExtra = $refSec['extra'] ?? [];
$referenceLogos = !empty($refExtra['items']) ? $refExtra['items'] : [
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

// KULLANICI TALEBİ: Kesintisiz sonsuz döngü garantisi (2 logo bile kalsa bantta asla boşluk olmayacak, logoları akıcı döndürecek)
$loopLogos = $referenceLogos;
if (!empty($loopLogos)) {
    while (count($loopLogos) < 12) {
        $loopLogos = array_merge($loopLogos, $referenceLogos);
    }
}

$siteLogo = Settings::get('site_logo', '');
$siteLogoText = Settings::get('site_logo_text', 'BİK GRUP');

if (!function_exists('resolveUrl')) {
    function resolveUrl(?string $url, string $default = '/'): string {
        $u = trim($url ?? '');
        if ($u === '') $u = $default;
        if (str_starts_with($u, 'http://') || str_starts_with($u, 'https://') || str_starts_with($u, '//') || str_starts_with($u, '#')) {
            return $u;
        }
        return BASE_URL . '/' . ltrim($u, '/');
    }
}
?>

<!-- ========================================================================
     GÖRSELDEKİ BİREBİR ÇİZGİSEL MİMARİ HERO GİRİŞ ALANI (ADLER STYLE)
     ======================================================================== -->
<section class="adler-hero-viewport">
    
    <!-- İkonik Beyaz Çizgisel Çerçeve -->
    <div class="adler-contour-frame">
        
        <!-- Çerçevenin İçiyle Sınırlandırılmış Doğa & Teras Manzarası Afişi -->
        <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($heroExtra['bg_image'] ?? 'nature_hero_bg.jpg') ?>" alt="BİKGRUP Doğa ve Tarım Arazileri" class="adler-bg-poster" loading="eager">
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
                    <span class="quote-btn-text"><?= $currentLang === 'en' ? 'Get a Quote' : ($featExtra['quote_btn_text'] ?? 'Teklif Al') ?></span>
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

        <!-- Çerçevenin Orta/Sol Alanı: Dikey 3 Stratejik Kart (Üretim, Tedarik & Ticaret, Global İhracat) -->
        <div class="adler-middle-section">
            <div class="adler-columns-group">
                
                <!-- Kart 1: Seçili / Beyaz Özel Kart (Üretim) -->
                <a href="<?= htmlspecialchars(resolveUrl($heroExtra['card1_link'] ?? '/kurumsal')) ?>" class="adler-white-card">
                    <div class="adler-card-title"><?= htmlspecialchars($currentLang === 'en' ? ($heroExtra['card1_title_en'] ?? 'Production') : ($heroExtra['card1_title'] ?? 'Üretim')) ?></div>
                    <p class="adler-card-desc">
                        <?= htmlspecialchars($currentLang === 'en' 
                            ? ($heroExtra['card1_desc_en'] ?? 'High-quality citrus and grain production using modern agricultural practices across our own farms.') 
                            : ($heroExtra['card1_desc'] ?? 'Kendi çiftliklerimizde modern tarım teknikleriyle yüksek kaliteli narenciye ve tahıl üretimi.')) ?>
                    </p>
                    <div class="adler-card-divider"></div>
                    <div class="adler-card-btn">
                        <span><?= htmlspecialchars($currentLang === 'en' ? ($heroExtra['card1_btn_en'] ?? 'DISCOVER') : ($heroExtra['card1_btn'] ?? 'KEŞFET')) ?></span>
                        <span class="arrow">→</span>
                    </div>
                </a>

                <!-- Kart 2: Şeffaf Çizgisel Sütun (Tedarik & Ticaret) -->
                <a href="<?= htmlspecialchars(resolveUrl($heroExtra['card2_link'] ?? '/kurumsal')) ?>" class="adler-glass-col">
                    <div class="adler-card-title"><?= htmlspecialchars($currentLang === 'en' ? ($heroExtra['card2_title_en'] ?? 'Supply & Trade') : ($heroExtra['card2_title'] ?? 'Tedarik & Ticaret')) ?></div>
                    <p class="adler-card-desc">
                        <?= htmlspecialchars($currentLang === 'en' 
                            ? ($heroExtra['card2_desc_en'] ?? 'Robust raw material sourcing and reliable logistics networks spanning from producer to industry and market.') 
                            : ($heroExtra['card2_desc'] ?? 'Üreticiden sanayiye ve pazara uzanan güçlü hammadde tedariği ve güvenilir lojistik ağı.')) ?>
                    </p>
                    <div class="adler-card-divider"></div>
                    <div class="adler-card-btn">
                        <span><?= htmlspecialchars($currentLang === 'en' ? ($heroExtra['card2_btn_en'] ?? 'EXPLORE') : ($heroExtra['card2_btn'] ?? 'İNCELE')) ?></span>
                        <span class="arrow">→</span>
                    </div>
                </a>

                <!-- Kart 3: Şeffaf Çizgisel Sütun (Global İhracat) -->
                <a href="<?= htmlspecialchars(resolveUrl($heroExtra['card3_link'] ?? '/urunler')) ?>" class="adler-glass-col">
                    <div class="adler-card-title"><?= htmlspecialchars($currentLang === 'en' ? ($heroExtra['card3_title_en'] ?? 'Global Export') : ($heroExtra['card3_title'] ?? 'Global İhracat')) ?></div>
                    <p class="adler-card-desc">
                        <?= htmlspecialchars($currentLang === 'en' 
                            ? ($heroExtra['card3_desc_en'] ?? 'A vision delivering the abundance of our land to international markets under global quality standards.') 
                            : ($heroExtra['card3_desc'] ?? 'Toprağın bereketini uluslararası kalite standartlarıyla dünya pazarlarına ulaştıran vizyon.')) ?>
                    </p>
                    <div class="adler-card-divider"></div>
                    <div class="adler-card-btn">
                        <span><?= htmlspecialchars($currentLang === 'en' ? ($heroExtra['card3_btn_en'] ?? 'EXPLORE') : ($heroExtra['card3_btn'] ?? 'İNCELE')) ?></span>
                        <span class="arrow">→</span>
                    </div>
                </a>

            </div>
        </div>

        <!-- Çerçevenin Alt Sınırı: Devasa Tipografi (Kullanıcı İsteği: Scroll Down ve Sağdaki 2 Cam Buton Kaldırıldı) -->
        <div class="adler-bottom-section" style="justify-content: flex-end;">
            
            <div class="adler-title-group">
                <h1 class="adler-mega-title">
                    <?php $heroTitleField = 'title_' . $currentLang; ?>
                    <?= !empty($heroSec[$heroTitleField]) ? nl2br(htmlspecialchars($heroSec[$heroTitleField])) : (!empty($heroSec['title_tr']) ? nl2br(htmlspecialchars($heroSec['title_tr'])) : ($currentLang === 'en' ? 'Our Roots in the Soil,<br>Our Vision Worldwide.' : 'Köklerimiz Toprakta,<br>Hedefimiz Dünyada.')) ?>
                </h1>

                <div class="adler-sub-group">
                    <div class="adler-sub-heading">
                        <?php $heroSubField = 'subtitle_' . $currentLang; ?>
                        <?= htmlspecialchars($heroSec[$heroSubField] ?? ($heroSec['subtitle_tr'] ?? 'BİK GRUP')) ?>
                    </div>
                    <div class="adler-sub-desc">
                        <?php $heroBodyField = 'body_' . $currentLang; ?>
                        <?= htmlspecialchars($heroSec[$heroBodyField] ?? ($heroSec['body_tr'] ?? ($currentLang === 'en' ? 'Founded in 2018, BİK GRUP operates across diverse agricultural sectors from production to supply, trade, and marketing with sustainable excellence.' : 'Temelleri 2018 yılında atılan BİK GRUP, üretimden tedariğe, ticaretten pazarlamaya kadar tarımın farklı alanlarında güvenle faaliyet göstermektedir.'))) ?>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>



<!-- ========================================================================
     YENİ: GÖRSELDEKİ BİREBİR KÜRESEL DÜNYA İHRACAT HARİTASI (COUNTERS ALANI)
     ======================================================================== -->
<!-- ========================================================================
     1. ÖNE ÇIKAN ÜRÜNLER VİTRİNİ (KULLANICI TALEBİ: GİRİŞTEN HEMEN SONRA)
     4 ADET, TASARIM BOZULMADAN AYNI ORANDA BÜYÜTÜLMÜŞ & DİKKAT ÇEKİCİ
     ======================================================================== -->
<section class="section home-featured-section" style="background: rgba(15, 23, 42, 0.02); padding-top: 60px; padding-bottom: 70px;">
    <div class="container">
        
        <div style="text-align: center; max-width: 640px; margin: 0 auto 35px auto;" class="reveal">
            <span style="font-size: 13px; font-weight: 800; color: var(--theme-primary); letter-spacing: 1.5px; text-transform: uppercase;">
                <?= htmlspecialchars($featSec['subtitle_' . $currentLang] ?? ($featSec['subtitle_tr'] ?? ($currentLang === 'en' ? 'Fresh Produce & Grains' : 'Taze Mahsuller & Hububat'))) ?>
            </span>
            <h2 style="font-family: var(--font-heading); font-size: 38px; font-weight: 800; color: var(--theme-text); margin-top: 6px;">
                <?= htmlspecialchars($featSec['title_' . $currentLang] ?? ($featSec['title_tr'] ?? __('sec_featured_products'))) ?>
            </h2>
            <p style="color: var(--theme-text-muted); font-size: 16px;">
                <?= htmlspecialchars($featSec['body_' . $currentLang] ?? ($featSec['body_tr'] ?? ($currentLang === 'en' ? 'Our highest-graded fresh produce and prime grains most sought after in global markets.' : 'Dünya pazarlarında en çok talep gören yüksek standartlı sofralık ürünlerimiz ve sertifikalı hububat mahsullerimiz.'))) ?>
            </p>
        </div>

        <div class="popout-products-grid home-featured-grid" id="homeFeaturedGrid">
            <?php 
            // Eğer panelden özel seçilmiş 4 ürün varsa onları sırasıyla getir
            $selectedProdIds = array_filter([
                !empty($featExtra['product_id_1']) ? (int)$featExtra['product_id_1'] : null,
                !empty($featExtra['product_id_2']) ? (int)$featExtra['product_id_2'] : null,
                !empty($featExtra['product_id_3']) ? (int)$featExtra['product_id_3'] : null,
                !empty($featExtra['product_id_4']) ? (int)$featExtra['product_id_4'] : null,
            ]);

            if (count($selectedProdIds) >= 1) {
                $topFeatured = [];
                foreach ($selectedProdIds as $pId) {
                    $found = Product::find($pId);
                    if ($found && !empty($found['is_active'])) {
                        $topFeatured[] = $found;
                    }
                }
                // Eğer 4'ten az ürün seçilmişse diğer öne çıkanlarla 4'e tamamla
                if (count($topFeatured) < 4) {
                    $existingIds = array_column($topFeatured, 'id');
                    foreach ($featuredProducts as $fp) {
                        if (!in_array($fp['id'], $existingIds, true)) {
                            $topFeatured[] = $fp;
                            if (count($topFeatured) === 4) break;
                        }
                    }
                }
            } else {
                $topFeatured = array_slice($featuredProducts, 0, 4);
            }

            foreach ($topFeatured as $index => $prod): 
                $prodUrl = BASE_URL . '/urunler/' . urlencode($prod['slug']);
            ?>
                <div class="popout-product-card home-featured-card card-color-<?= ($index % 4) + 1 ?> reveal reveal-delay-<?= ($index % 4) + 1 ?>" 
                     onclick="window.location.href='<?= $prodUrl ?>';"
                     style="display: flex; cursor: pointer;">
                    <!-- Karttan Dışarı Taşan 3 Boyutlu Ürün Görseli (Orantılı Büyütülmüş) -->
                    <a href="<?= $prodUrl ?>" class="popout-img-wrapper" onclick="event.stopPropagation();">
                        <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($prod['image'] ?: 'product_orange.png') ?>" 
                             alt="<?= htmlspecialchars($prod['name_' . $currentLang] ?? $prod['name_tr']) ?>" 
                             class="popout-floating-img" 
                             loading="lazy">
                    </a>

                    <!-- Kart Alt İçeriği: Ürün İsimleri & Sağ Altta Çapraz Ok Butonu -->
                    <div class="popout-card-bottom">
                        <div class="popout-card-text">
                            <?php 
                            $prodExtra = !empty($prod['extra_json']) ? json_decode($prod['extra_json'], true) : [];
                            $customPrefix = $prodExtra['badge_prefix'] ?? null;
                            $defaultPrefix = $prod['category_name_' . $currentLang] ?? $prod['category_name_tr'] ?? ($currentLang === 'en' ? 'Fresh & Premium' : 'Taze & Doğal');
                            ?>
                            <span class="popout-card-prefix"><?= htmlspecialchars($customPrefix ?: $defaultPrefix) ?></span>
                            <h3 class="popout-card-title">
                                <a href="<?= $prodUrl ?>">
                                    <?= htmlspecialchars($prod['name_' . $currentLang] ?? $prod['name_tr']) ?>
                                </a>
                            </h3>
                        </div>

                        <!-- Sayı yerine çapraz ok işareti olan dairesel buton -->
                        <a href="<?= $prodUrl ?>" class="popout-circle-btn" aria-label="<?= htmlspecialchars($prod['name_' . $currentLang] ?? $prod['name_tr']) . ' ' . ($currentLang === 'en' ? 'Inspect' : 'İncele') ?>">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="7" y1="17" x2="17" y2="7"></line>
                                <polyline points="7 7 17 7 17 17"></polyline>
                            </svg>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="text-align: center; margin-top: 55px;" class="reveal">
            <?php
            $rawBtnLink = !empty($featExtra['all_products_link']) ? trim($featExtra['all_products_link']) : (!empty($featExtra['btn_link']) ? trim($featExtra['btn_link']) : '/urunler');
            $finalBtnUrl = resolveUrl($rawBtnLink, '/urunler');
            ?>
            <a href="<?= htmlspecialchars($finalBtnUrl) ?>" class="btn btn-outline" style="padding: 14px 36px; font-weight: 700;">
                <?= htmlspecialchars($currentLang === 'en' ? ($featExtra['all_products_text_en'] ?? ($featExtra['btn_text_en'] ?? 'View All Products')) : ($featExtra['all_products_text'] ?? ($featExtra['btn_text'] ?? 'Tüm Ürünleri İncele'))) ?>
            </a>
        </div>

    </div>
</section>



<!-- ========================================================================
     2. GÖRSELDEKİ KÜRESEL DÜNYA İHRACAT HARİTASI (YEŞİL & TURUNCU TONLARI)
     ======================================================================== -->
<section class="global-map-section section" style="padding-top: 70px;">
    <div class="container">
        
        <!-- Üst Başlık (Yeşil Vurgu - Kullanıcı Talebi) -->
        <div class="map-header-block reveal">
            <h2 class="map-heading">
                <?= !empty($mapSec['title_' . $currentLang]) ? nl2br(htmlspecialchars($mapSec['title_' . $currentLang])) : (!empty($mapSec['title_tr']) ? nl2br(htmlspecialchars($mapSec['title_tr'])) : ($currentLang === 'en' ? 'Seamless Export Network from the Mediterranean to the World' : 'Akdeniz\'den Dünyaya Ulaşan <span class="highlight-green">Kesintisiz İhracat Ağı</span>')) ?>
            </h2>
            <p class="map-subtext">
                <?= htmlspecialchars($mapSec['body_' . $currentLang] ?? ($mapSec['body_tr'] ?? ($currentLang === 'en' ? 'From Turkey\'s most fertile gardens, we build fresh taste bridges to tables in 30+ countries across 4 continents.' : 'Türkiye\'nin en bereketli bahçelerinden 4 kıtada 30\'u aşkın ülkenin sofrasına taze lezzet köprüsü kuruyoruz.'))) ?>
            </p>
        </div>

        <!-- İnteraktif Dünya Haritası ve Kavisli Rotalar -->
        <div class="world-map-stage reveal">
            
            <!-- Gerçek Coğrafi Dünya Haritası & Kıta Sınırları & Kavisli Rotalar -->
            <?php require VIEWS_PATH . '/partials/world_map_svg.php'; ?>

            <!-- Harita Üzerinde Konumlanan Modern Yüzen Kapsül Bilgi Kartları (Soft & Yumuşak Tasarım) -->
            <?php
            $c1Title = $mapExtra['capsule1_title_' . $currentLang] ?? ($mapExtra['capsule1_title'] ?? ($mapExtra['capsule1'] ?? ($currentLang === 'en' ? 'Export to 30+ Countries' : '30+ Ülkeye İhracat')));
            $c1Sub   = $mapExtra['capsule1_sub_' . $currentLang] ?? ($mapExtra['capsule1_sub'] ?? ($currentLang === 'en' ? 'Europe, Russia & Scandinavia' : 'Avrupa, Rusya & İskandinavya'));

            $c2Title = $mapExtra['capsule2_title_' . $currentLang] ?? ($mapExtra['capsule2_title'] ?? ($mapExtra['capsule2'] ?? ($currentLang === 'en' ? '150,000+ Tons Capacity' : '150.000+ Ton Kapasite')));
            $c2Sub   = $mapExtra['capsule2_sub_' . $currentLang] ?? ($mapExtra['capsule2_sub'] ?? ($currentLang === 'en' ? 'Modern Cold Storage Facilities' : 'Modern Soğuk Hava Depoları'));

            $c3Title = $mapExtra['capsule3_title_' . $currentLang] ?? ($mapExtra['capsule3_title'] ?? ($mapExtra['capsule3'] ?? ($currentLang === 'en' ? '100% Fresh & Traceable' : '%100 Taze & İzlenebilir')));
            $c3Sub   = $mapExtra['capsule3_sub_' . $currentLang] ?? ($mapExtra['capsule3_sub'] ?? ($currentLang === 'en' ? 'GlobalG.A.P. & BRCGS Certified' : 'GlobalG.A.P. & BRCGS Sertifikalı'));
            ?>
            
            <!-- 1. Kart: Soft Yeşil Rozetli (Avrupa / Merkez - Küre İkonu) -->
            <div class="map-floating-capsule capsule-pos-1 anim-float">
                <div class="capsule-icon-box icon-green">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path>
                        <path d="M2 12h20"></path>
                    </svg>
                </div>
                <div class="capsule-content">
                    <span class="capsule-title"><?= htmlspecialchars((string)$c1Title) ?></span>
                    <span class="capsule-subtitle"><?= htmlspecialchars((string)$c1Sub) ?></span>
                </div>
            </div>

            <!-- 2. Kart: Soft Turuncu Rozetli (Körfez & Ortadoğu - Lojistik/Kapasite İkonu) -->
            <div class="map-floating-capsule capsule-pos-2 anim-float" style="animation-delay: 1.2s;">
                <div class="capsule-icon-box icon-orange">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m7.5 4.27 9 5.15"></path>
                        <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path>
                        <path d="m3.3 7 8.7 5 8.7-5"></path>
                        <path d="M12 22V12"></path>
                    </svg>
                </div>
                <div class="capsule-content">
                    <span class="capsule-title"><?= htmlspecialchars((string)$c2Title) ?></span>
                    <span class="capsule-subtitle"><?= htmlspecialchars((string)$c2Sub) ?></span>
                </div>
            </div>

            <!-- 3. Kart: Soft Zümrüt Rozetli (Global Güvence - Kalkan/Onay İkonu) -->
            <div class="map-floating-capsule capsule-pos-3 anim-float" style="animation-delay: 2.4s;">
                <div class="capsule-icon-box icon-emerald">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        <path d="m9 12 2 2 4-4"></path>
                    </svg>
                </div>
                <div class="capsule-content">
                    <span class="capsule-title"><?= htmlspecialchars((string)$c3Title) ?></span>
                    <span class="capsule-subtitle"><?= htmlspecialchars((string)$c3Sub) ?></span>
                </div>
            </div>
                </div>
            </div>

        </div>

    </div>
</section>



<!-- GRUP MARKALARIMIZ (KULLANICI TALEBİ: AÇIK GRİ BEYAZ TONLARI & GENİŞ ÇERÇEVE) -->
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



<!-- 4. TESİS VE OTOMASYON BÖLÜMÜ (KULLANICI TALEBİ: KUTUSUZ, DÜZ YAZI & BÜYÜTÜLMÜŞ METİNLER) -->
<section class="section" style="padding-top: 60px; padding-bottom: 70px;">
    <div class="container" style="max-width: 1200px;">
        <div class="facility-pure-wrapper reveal">
            <span class="facility-tag-clean"><?= htmlspecialchars($facSec['subtitle_' . $currentLang] ?? ($facSec['subtitle_tr'] ?? __('sec_facility_badge'))) ?></span>
            <?php
            $rawFacTitle = $facSec['title_' . $currentLang] ?? ($facSec['title_tr'] ?? 'Sıradan bir işleme tesisi değiliz; otomasyon, izlenebilirlik ve kalite kontrol süreçlerimizle ürünlerimizi daima güvence altında tutuyoruz.');
            $rawFacBody = $facSec['body_' . $currentLang] ?? ($facSec['body_tr'] ?? 'Modern optik boylama, otomatik paketleme ve hassas sıcaklık kontrollü atmosferik depolarımızla dünya standartlarında hizmet veriyoruz.');

            if ($currentLang === 'en') {
                $highlightedTitle = str_ireplace(
                    ['automation', 'traceability', 'quality control', 'always guaranteed'],
                    [
                        '<span class="txt-color-primary">automation</span>',
                        '<span class="txt-color-orange">traceability</span>',
                        '<span class="txt-color-emerald">quality control</span>',
                        '<span class="txt-color-gradient">always guaranteed</span>'
                    ],
                    htmlspecialchars($rawFacTitle)
                );
                $highlightedBody = str_ireplace(
                    ['optical calibration', 'automatic packaging', 'precision temperature-controlled', 'global standards'],
                    [
                        '<span class="txt-bold-green">optical calibration</span>',
                        '<span class="txt-bold-orange">automatic packaging</span>',
                        '<span class="txt-bold-emerald">precision temperature-controlled</span>',
                        '<span class="txt-bold-green">global standards</span>'
                    ],
                    htmlspecialchars($rawFacBody)
                );
            } else {
                $highlightedTitle = str_ireplace(
                    ['otomasyon', 'izlenebilirlik', 'kalite kontrol', 'daima güvence altında'],
                    [
                        '<span class="txt-color-primary">otomasyon</span>',
                        '<span class="txt-color-orange">izlenebilirlik</span>',
                        '<span class="txt-color-emerald">kalite kontrol</span>',
                        '<span class="txt-color-gradient">daima güvence altında</span>'
                    ],
                    htmlspecialchars($rawFacTitle)
                );
                $highlightedBody = str_ireplace(
                    ['optik boylama', 'otomatik paketleme', 'hassas sıcaklık kontrollü', 'dünya standartlarında'],
                    [
                        '<span class="txt-bold-green">optik boylama</span>',
                        '<span class="txt-bold-orange">otomatik paketleme</span>',
                        '<span class="txt-bold-emerald">hassas sıcaklık kontrollü</span>',
                        '<span class="txt-bold-green">dünya standartlarında</span>'
                    ],
                    htmlspecialchars($rawFacBody)
                );
            }
            ?>
            <h2 class="facility-title-huge">
                <?= $highlightedTitle ?>
            </h2>
            <p class="facility-desc-huge">
                <?= $highlightedBody ?>
            </p>
            <div style="margin-top: 38px;">
                <a href="<?= htmlspecialchars(resolveUrl($facExtra['btn_link'] ?? '/iletisim')) ?>" class="btn btn-primary" style="background:#22c55e; color:#0f172a; font-weight:800; font-size:16px; padding: 16px 42px; border-radius: 9999px; box-shadow: 0 14px 32px -6px rgba(34, 197, 94, 0.45);">
                    <?= htmlspecialchars($currentLang === 'en' ? ($facExtra['btn_text_en'] ?? __('btn_contact_us')) : ($facExtra['btn_text'] ?? __('btn_contact_us'))) ?>
                </a>
            </div>
        </div>
    </div>
</section>



<!-- 5. BLOG VE HABERLER -->
<section class="section">
    <div class="container">
        
        <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom: 40px; flex-wrap: wrap; gap: 16px;" class="reveal">
            <div>
                <span style="font-size: 13px; font-weight: 800; color: var(--theme-primary); letter-spacing: 1.5px; text-transform: uppercase;">
                    <?= htmlspecialchars($blogSec['subtitle_' . $currentLang] ?? ($blogSec['subtitle_tr'] ?? ($currentLang === 'en' ? 'Industry News' : 'Sektörel Haberler'))) ?>
                </span>
                <h2 style="font-family: var(--font-heading); font-size: 36px; font-weight: 800; color: var(--theme-text); margin-top: 4px;">
                    <?= htmlspecialchars($blogSec['title_' . $currentLang] ?? ($blogSec['title_tr'] ?? __('sec_blog_title'))) ?>
                </h2>
            </div>
            <a href="<?= htmlspecialchars(resolveUrl($blogExtra['btn_link'] ?? '/blog')) ?>" class="btn btn-outline" style="display:inline-flex;">
                <?= htmlspecialchars($currentLang === 'en' ? ($blogExtra['btn_text_en'] ?? 'All Articles') : ($blogExtra['btn_text'] ?? 'Tüm Makaleler')) ?>
            </a>
        </div>

        <div class="blog-grid">
            <?php foreach ($recentPosts as $index => $post): ?>
                <article class="blog-card reveal reveal-delay-<?= $index + 1 ?>">
                    <div class="blog-card-img">
                        <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($post['image'] ?: 'blog_cold_chain.png') ?>" alt="<?= htmlspecialchars($post['title_' . $currentLang] ?? $post['title_tr']) ?>" loading="lazy">
                    </div>
                    <div class="blog-card-body">
                        <div class="blog-meta">
                            <span>⏱ <?= htmlspecialchars($post['read_time']) ?></span>
                            <span>•</span>
                            <span><?= date('d.m.Y', strtotime($post['created_at'])) ?></span>
                        </div>
                        <h3 class="blog-card-title"><?= htmlspecialchars($post['title_' . $currentLang] ?? $post['title_tr']) ?></h3>
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

<!-- ========================================================================
     6. KAYAN REFERANSLAR VE GLOBAL İŞ ORTAKLARI (INFINITE LOGO MARQUEE)
     KULLANICI TALEBİ: REFERANSLAR LOGO ALANLARI, BEYAZ ARKA PLAN & EŞİT KART BOYUTLARI
     ======================================================================== -->
<section class="section home-marquee-section" style="padding: 48px 0 80px 0; background: #ffffff !important; border-top: none !important; overflow: hidden;">
    <div class="container" style="margin-bottom: 28px; text-align: center;">
        <span style="font-size: 13px; font-weight: 800; color: #15803d; letter-spacing: 2px; text-transform: uppercase;">
            <?= htmlspecialchars($refSec['subtitle_' . $currentLang] ?? ($refSec['subtitle_tr'] ?? ($currentLang === 'en' ? 'GLOBAL TRADE NETWORK & REFERENCES' : 'KÜRESEL İŞ ORTAKLARI & GÜVENİLİR REFERANSLAR'))) ?>
        </span>
        <h3 style="font-size: 28px; font-weight: 800; color: #0f172a; margin-top: 8px; letter-spacing: -0.3px;">
            <?= htmlspecialchars($refSec['title_' . $currentLang] ?? ($refSec['title_tr'] ?? ($currentLang === 'en' ? 'Export Assured by BİK GRUP' : 'BİK GRUP Güvencesiyle Dünya Standartlarında Ticaret'))) ?>
        </h3>
        <?php if (!empty($refSec['body_tr'])): ?>
            <p style="max-width: 740px; margin: 10px auto 0; font-size: 14.5px; color: #64748b; line-height: 1.5;">
                <?= htmlspecialchars($refSec['body_' . $currentLang] ?? $refSec['body_tr']) ?>
            </p>
        <?php endif; ?>
    </div>

    <!-- Kayan Logo Bandı Sahnesi -->
    <div class="marquee-track-wrapper">
        <div class="marquee-track">
            
            <!-- 1. Grup (Sonsuz Döngü Kesintisiz Logo Kartları) -->
            <?php foreach ($loopLogos as $ref): ?>
                <div class="marquee-logo-card" title="<?= htmlspecialchars($ref['name'] ?? 'Referans') ?>">
                    <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($ref['image']) ?>" 
                         alt="<?= htmlspecialchars($ref['name'] ?? 'Referans') ?>" 
                         class="marquee-logo-img"
                         loading="lazy">
                </div>
            <?php endforeach; ?>

            <!-- 2. Grup (Dikişsiz Sonsuz Döngü Kopyası - Kesintisiz Logo Kartları) -->
            <?php foreach ($loopLogos as $ref): ?>
                <div class="marquee-logo-card" aria-hidden="true" title="<?= htmlspecialchars($ref['name'] ?? 'Referans') ?>">
                    <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($ref['image']) ?>" 
                         alt="<?= htmlspecialchars($ref['name'] ?? 'Referans') ?>" 
                         class="marquee-logo-img"
                         loading="lazy">
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>

<style>
.marquee-track-wrapper {
    position: relative;
    width: 100%;
    overflow: hidden;
    padding: 16px 0;
    mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
    -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
}
.marquee-track {
    display: flex;
    gap: 24px;
    width: max-content;
    animation: marqueeScrollLoop 45s linear infinite;
    will-change: transform;
}
.marquee-track-wrapper:hover .marquee-track {
    animation-play-state: paused;
}
/* KULLANICI TALEBİ: BÜTÜN LOGO KARTLARI BİREBİR EŞİT, DÜZGÜN VE ARKA PLANI SAF BEYAZ */
.marquee-logo-card {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 220px;
    height: 96px;
    background: #ffffff !important;
    border: 1.5px solid #e2e8f0;
    border-radius: 18px;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
    transition: all 0.25s ease;
    user-select: none;
    flex-shrink: 0;
    padding: 12px 20px;
    box-sizing: border-box;
}
.marquee-logo-card:hover {
    border-color: #15803d;
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(21, 128, 61, 0.16);
}
.marquee-logo-img {
    max-width: 100%;
    max-height: 54px;
    width: auto;
    height: auto;
    object-fit: contain;
    background: transparent;
    display: block;
    filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.02));
}
@keyframes marqueeScrollLoop {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(calc(-50% - 12px));
    }
}
@media (max-width: 768px) {
    .marquee-track {
        gap: 14px;
        animation-duration: 32s;
    }
    .marquee-logo-card {
        width: 170px;
        height: 82px;
        padding: 10px 14px;
        border-radius: 14px;
    }
    .marquee-logo-img {
        max-height: 42px;
    }
}
</style>
