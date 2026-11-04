<?php
/**
 * BİKGRUP - Fotoğraf ve Video Galerisi Sayfası (Adler Style Mimari)
 * PHP 8.2 / Strict Types
 */

declare(strict_types=1);

$currentLang = Lang::current();
$siteLogo = Settings::get('site_logo', '');
$siteLogoText = Settings::get('site_logo_text', 'BİK GRUP');
$hasHero = true;

// Kategori Türkçe & İngilizce Doğru Karakter Etiket Haritası
$categoryPillMap = [
    'hasat'     => ['tr' => 'HASAT',     'en' => 'HARVEST'],
    'tesisler'  => ['tr' => 'TESİSLER',  'en' => 'FACILITIES'],
    'paketleme' => ['tr' => 'PAKETLEME', 'en' => 'PACKAGING'],
    'urunler'   => ['tr' => 'ÜRÜNLER',   'en' => 'PRODUCTS'],
    'lojistik'  => ['tr' => 'LOJİSTİK',  'en' => 'COLD CHAIN'],
    'tanitim'   => ['tr' => 'TANITIM',   'en' => 'CORPORATE'],
    'genel'     => ['tr' => 'GENEL',     'en' => 'GENERAL']
];

$galleryHero = PageSection::get('gallery_page_hero') ?? [];
$galleryHeroExtra = $galleryHero['extra'] ?? (!empty($galleryHero['extra_json']) ? json_decode($galleryHero['extra_json'], true) : []);
$heroBg = $galleryHeroExtra['bg_image'] ?? 'nature_hero_bg.jpg';
$heroTitle = $galleryHero['title_tr'] ?? 'Fotoğraf & Video Galerisi';
$heroBadge = $galleryHeroExtra['badge_text'] ?? ($currentLang === 'en' ? 'BIK GROUP | Harvest & Facility Moments' : 'BİK GRUP | Sahadan ve Tesislerimizden Kareler');
$heroDesc = $galleryHero['content_tr'] ?? ($currentLang === 'en' 
    ? 'From our Mediterranean groves to modern packaging facilities and global export destinations, witness our agricultural journey.' 
    : 'Akdeniz bahçelerimizden hasada, modern paketleme tesislerimizden küresel sevkiyata uzanan üretim ve ihracat yolculuğumuz.');

if ($currentLang === 'en') {
    $heroTitle = !empty($galleryHero['title_en']) ? $galleryHero['title_en'] : 'Photo & Video Gallery';
    $heroDesc = !empty($galleryHero['content_en']) ? $galleryHero['content_en'] : 'From our Mediterranean groves to modern packaging facilities and global export destinations, witness our agricultural journey.';
}
?>

<!-- ========================================================================
     GALERİ - ADLER STYLE MİNİMAL HERO GİRİŞ ALANI
     ======================================================================== -->
<section class="adler-hero-viewport inner-hero-viewport">
    
    <div class="adler-contour-frame frame-centered inner-hero-frame">
        
        <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($heroBg) ?>" alt="BİKGRUP Galeri" class="adler-bg-poster" loading="eager">
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
                <a href="<?= BASE_URL ?>/galeri" class="adler-nav-link active"><?= $currentLang === 'en' ? 'Gallery' : 'Galeri' ?></a>
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

        <!-- Hero Ortası: Galeri Başlığı & Açıklaması -->
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
     GALERİ ANA BÖLÜMÜ - FİLTRE VE MODERN GRID
     ======================================================================== -->
<section class="section gallery-section-main" style="padding: 60px 0 100px 0; background: #ffffff;">
    <div class="container" style="max-width: 1360px;">
        
        <!-- Filtre Sekmeleri -->
        <div class="gallery-filters-bar">
            <button type="button" class="gallery-filter-btn active" data-filter="all">
                <?= $currentLang === 'en' ? 'All Media' : 'Tüm Medya' ?>
            </button>
            <button type="button" class="gallery-filter-btn" data-filter="type-image">
                📷 <?= $currentLang === 'en' ? 'Photos' : 'Fotoğraflar' ?>
            </button>
            <button type="button" class="gallery-filter-btn" data-filter="type-video">
                🎬 <?= $currentLang === 'en' ? 'Videos' : 'Videolar' ?>
            </button>
            <span class="gallery-filter-divider" style="display: inline-block; width: 1px; height: 26px; background: #e2e8f0; margin: 0 4px;"></span>
            <button type="button" class="gallery-filter-btn" data-filter="cat-urunler">
                <?= $currentLang === 'en' ? 'Products' : 'Ürünlerimiz' ?>
            </button>
            <button type="button" class="gallery-filter-btn" data-filter="cat-hasat">
                <?= $currentLang === 'en' ? 'Harvest & Orchards' : 'Hasat & Bahçeler' ?>
            </button>
            <button type="button" class="gallery-filter-btn" data-filter="cat-tesisler">
                <?= $currentLang === 'en' ? 'Facilities' : 'Tesislerimiz' ?>
            </button>
            <button type="button" class="gallery-filter-btn" data-filter="cat-paketleme">
                <?= $currentLang === 'en' ? 'Packaging' : 'Paketleme' ?>
            </button>
            <button type="button" class="gallery-filter-btn" data-filter="cat-lojistik">
                <?= $currentLang === 'en' ? 'Cold Chain & Logistics' : 'Soğuk Zincir & Lojistik' ?>
            </button>
            <button type="button" class="gallery-filter-btn" data-filter="cat-tanitim">
                <?= $currentLang === 'en' ? 'Corporate' : 'Kurumsal Tanıtım' ?>
            </button>
        </div>

        <!-- Medya Kartları Grid -->
        <div class="gallery-grid" id="galleryGrid">
            <?php if (empty($items)): ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #94a3b8;">
                    <p style="font-size: 16px;">
                        <?= $currentLang === 'en' ? 'No media items found yet.' : 'Henüz galeriye medya eklenmemiş.' ?>
                    </p>
                </div>
            <?php else: ?>
                <?php foreach ($items as $index => $item): 
                    $title = ($currentLang === 'en' && !empty($item['title_en'])) ? $item['title_en'] : $item['title_tr'];
                    $img = !empty($item['image_path']) ? $item['image_path'] : 'nature_hero_bg.jpg';
                    $type = $item['type'] ?? 'image';
                    $cat = strtolower($item['category'] ?? 'genel');
                    $videoUrl = $item['video_url'] ?? '';
                    $videoFile = $item['video_file'] ?? '';
                ?>
                    <div class="gallery-item type-<?= htmlspecialchars($type) ?> cat-<?= htmlspecialchars($cat) ?>"
                         data-type="<?= htmlspecialchars($type) ?>"
                         data-title="<?= htmlspecialchars($title) ?>"
                         data-img="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($img) ?>"
                         data-video-url="<?= htmlspecialchars($videoUrl) ?>"
                         data-video-file="<?= !empty($videoFile) ? BASE_URL . '/public/images/' . htmlspecialchars($videoFile) : '' ?>"
                         onclick="openMediaModal(this)">
                        
                        <div class="gallery-card">
                            <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($img) ?>" 
                                 alt="<?= htmlspecialchars($title) ?>" 
                                 loading="lazy" 
                                 class="gallery-card-img">
                            
                            <!-- Tür İkonu / Oynat Butonu -->
                            <?php if ($type === 'video'): ?>
                                <div class="gallery-video-play-btn">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                </div>
                            <?php else: ?>
                                <div class="gallery-zoom-badge">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                        <line x1="11" y1="8" x2="11" y2="14"></line>
                                        <line x1="8" y1="11" x2="14" y2="11"></line>
                                    </svg>
                                </div>
                            <?php endif; ?>

                            <!-- Alt Bilgi Paneli -->
                            <div class="gallery-card-overlay">
                                <?php 
                                $catKey = strtolower(trim((string)($item['category'] ?? 'genel')));
                                $catBadge = $categoryPillMap[$catKey][$currentLang] ?? ($currentLang === 'en' ? strtoupper($catKey) : mb_strtoupper($catKey, 'UTF-8'));
                                ?>
                                <span class="gallery-category-pill">
                                    <?= htmlspecialchars($catBadge) ?>
                                </span>
                                <h3 class="gallery-item-title">
                                    <?= htmlspecialchars($title) ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</section>

<!-- ========================================================================
     MODERN LIGHTBOX & VIDEO MODAL POPUP
     ======================================================================== -->
<div id="mediaLightbox" class="media-lightbox" onclick="closeMediaModal(event)">
    <div class="media-lightbox-container">
        
        <!-- Kapat Butonu -->
        <button type="button" class="media-lightbox-close" onclick="closeMediaModal(null)" aria-label="Kapat">
            &times;
        </button>

        <!-- İçerik Kutusu -->
        <div class="media-lightbox-body" id="lightboxBody">
            <!-- Dinamik Olarak Resim veya Video Yerleşir -->
        </div>

        <!-- Alt Başlık Barı -->
        <div class="media-lightbox-footer" id="lightboxFooter">
            <span id="lightboxTitle"></span>
        </div>
    </div>
</div>

<style>
/* Hero Responsive Mimari */
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
    .gallery-filters-bar {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 45px;
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
    .inner-hero-frame .adler-hero-badge {
        font-size: 11px !important;
        padding: 5px 12px !important;
        margin-bottom: 10px !important;
        letter-spacing: 0.5px !important;
        max-width: 95% !important;
        white-space: normal !important;
        text-align: center;
    }
    .inner-hero-frame .adler-inner-mega-title {
        font-size: clamp(26px, 7vw, 36px) !important;
        margin-bottom: 10px !important;
        line-height: 1.25 !important;
    }
    .inner-hero-frame .adler-inner-desc {
        font-size: 13.5px !important;
        line-height: 1.5 !important;
        margin: 0 auto !important;
        opacity: 0.95;
    }
    .inner-hero-bottom-spacer {
        height: 25px !important;
        display: block !important;
    }
    .gallery-section-main {
        padding: 20px 0 60px 0 !important;
    }
    .gallery-filters-bar {
        display: flex !important;
        flex-wrap: nowrap !important;
        overflow-x: auto !important;
        justify-content: flex-start !important;
        align-items: center !important;
        gap: 8px !important;
        padding: 6px 16px 14px 16px !important;
        margin: 0 -16px 20px -16px !important;
        -webkit-overflow-scrolling: touch !important;
        scrollbar-width: none !important;
    }
    .gallery-filters-bar::-webkit-scrollbar {
        display: none !important;
    }
    .gallery-filter-btn {
        flex-shrink: 0 !important;
        white-space: nowrap !important;
        padding: 7px 14px !important;
        font-size: 12.5px !important;
        border-radius: 20px !important;
    }
    .gallery-filter-divider {
        display: none !important;
    }
}

/* Filtre Butonları */
.gallery-filter-btn {
    background: #f8fafc;
    color: #475569;
    border: 1.5px solid #e2e8f0;
    padding: 8px 18px;
    border-radius: 30px;
    font-size: 13.5px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.25s ease;
}

.gallery-filter-btn:hover {
    background: #f1f5f9;
    color: #0f172a;
    border-color: #cbd5e1;
    transform: translateY(-1px);
}

.gallery-filter-btn.active {
    background: var(--theme-primary, #059669);
    color: #ffffff;
    border-color: var(--theme-primary, #059669);
    box-shadow: 0 4px 14px rgba(5, 150, 105, 0.3);
}

/* Grid Düzeni */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
}

@media (max-width: 992px) {
    .gallery-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
}

@media (max-width: 768px) {
    .gallery-grid {
        grid-template-columns: 1fr !important;
        gap: 16px !important;
        padding: 0 4px !important;
    }
    .gallery-card {
        border-radius: 14px !important;
        aspect-ratio: 16 / 10 !important;
    }
    .gallery-card-overlay {
        padding: 16px 14px !important;
    }
    .gallery-item-title {
        font-size: 15px !important;
        line-height: 1.3 !important;
    }
    .gallery-category-pill {
        font-size: 10px !important;
        padding: 3px 8px !important;
        margin-bottom: 6px !important;
    }
    .gallery-video-play-btn {
        width: 48px !important;
        height: 48px !important;
    }
    .gallery-video-play-btn svg {
        width: 18px !important;
        height: 18px !important;
    }
}

/* Medya Kartı */
.gallery-card {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    aspect-ratio: 16 / 11;
    background: #0f172a;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
    cursor: pointer;
    transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}

.gallery-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 35px -8px rgba(0, 0, 0, 0.2);
}

.gallery-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

.gallery-card:hover .gallery-card-img {
    transform: scale(1.08);
}

/* Overlay */
.gallery-card-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0) 40%, rgba(0,0,0,0.85) 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 22px 20px;
    color: #ffffff;
    z-index: 2;
}

.gallery-category-pill {
    align-self: flex-start;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(8px);
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.8px;
    margin-bottom: 8px;
    border: 1px solid rgba(255, 255, 255, 0.25);
}

.gallery-item-title {
    font-size: 16px;
    font-weight: 700;
    line-height: 1.35;
    margin: 0;
    color: #ffffff;
    text-shadow: 0 2px 8px rgba(0,0,0,0.4);
}

/* Oynat & Zoom İkonları */
.gallery-video-play-btn {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.92);
    color: #dc2626;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    z-index: 3;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    padding-left: 4px;
}

.gallery-card:hover .gallery-video-play-btn {
    transform: translate(-50%, -50%) scale(1.15);
    background: #ffffff;
    color: #b91c1c;
}

.gallery-zoom-badge {
    position: absolute;
    top: 16px;
    right: 16px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.45);
    backdrop-filter: blur(6px);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 3;
    opacity: 0;
    transform: scale(0.8);
    transition: all 0.3s ease;
}

.gallery-card:hover .gallery-zoom-badge {
    opacity: 1;
    transform: scale(1);
}

/* Lightbox Modal */
.media-lightbox {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(10, 15, 25, 0.94);
    backdrop-filter: blur(10px);
    z-index: 10000;
    align-items: center;
    justify-content: center;
    padding: 30px;
    animation: fadeIn 0.25s ease-out;
}

.media-lightbox-container {
    position: relative;
    max-width: 1050px;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.media-lightbox-close {
    position: absolute;
    top: -45px;
    right: 0;
    background: transparent;
    border: none;
    color: #ffffff;
    font-size: 38px;
    cursor: pointer;
    line-height: 1;
    transition: opacity 0.2s;
}

.media-lightbox-close:hover {
    opacity: 0.7;
}

.media-lightbox-body {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 12px;
    overflow: hidden;
    background: #000;
}

.media-lightbox-body img {
    max-width: 100%;
    max-height: 75vh;
    object-fit: contain;
    border-radius: 10px;
}

.media-lightbox-body iframe,
.media-lightbox-body video {
    width: 100%;
    aspect-ratio: 16 / 9;
    border: none;
    border-radius: 10px;
}

/* Ses butonunu ve ses çubuğunu tamamen kaldır (Ses açma yok) */
.media-lightbox video::-webkit-media-controls-volume-control-container,
.media-lightbox video::-webkit-media-controls-mute-button,
.media-lightbox video::-webkit-media-controls-volume-slider,
.media-lightbox video::-webkit-media-controls-volume-slider-container {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    pointer-events: none !important;
    width: 0 !important;
    height: 0 !important;
}

.media-lightbox-footer {
    margin-top: 14px;
    color: #e2e8f0;
    font-size: 15px;
    font-weight: 600;
    text-align: center;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>

<script>
// Filtreleme
document.querySelectorAll('.gallery-filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.gallery-filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const filter = this.getAttribute('data-filter');

        const items = document.querySelectorAll('.gallery-item');
        items.forEach(item => {
            if (filter === 'all') {
                item.style.display = '';
            } else if (item.classList.contains(filter)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
});

// Modal Açma
function openMediaModal(el) {
    const type = el.getAttribute('data-type');
    const title = el.getAttribute('data-title');
    const img = el.getAttribute('data-img');
    const videoUrl = el.getAttribute('data-video-url');
    const videoFile = el.getAttribute('data-video-file');
    const body = document.getElementById('lightboxBody');
    const titleEl = document.getElementById('lightboxTitle');

    titleEl.textContent = title || '';
    body.innerHTML = '';

    if (type === 'video') {
        if (videoFile) {
            body.innerHTML = `<video src="${videoFile}" controls controlslist="nodownload noplaybackrate" autoplay muted loop playsinline style="max-height: 75vh; width: 100%; border-radius: 10px;"></video>`;
            const videoElem = body.querySelector('video');
            if (videoElem) {
                videoElem.muted = true;
                videoElem.defaultMuted = true;
                videoElem.volume = 0;
                // Ses açmayı tamamen kilitle - ses kesinlikle yok
                videoElem.onvolumechange = function() {
                    this.muted = true;
                    this.volume = 0;
                };
            }
        } else if (videoUrl) {
            // YouTube embed dönüştürme (Sessiz başlatma: mute=1)
            let embedUrl = videoUrl;
            if (videoUrl.includes('youtube.com/watch?v=')) {
                const vidId = videoUrl.split('watch?v=')[1].split('&')[0];
                embedUrl = 'https://www.youtube.com/embed/' + vidId + '?autoplay=1&mute=1';
            } else if (videoUrl.includes('youtu.be/')) {
                const vidId = videoUrl.split('youtu.be/')[1].split('?')[0];
                embedUrl = 'https://www.youtube.com/embed/' + vidId + '?autoplay=1&mute=1';
            } else if (videoUrl.includes('vimeo.com/')) {
                const vidId = videoUrl.split('vimeo.com/')[1];
                embedUrl = 'https://player.vimeo.com/video/' + vidId + '?autoplay=1&muted=1';
            }
            body.innerHTML = `<iframe src="${embedUrl}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
        } else {
            body.innerHTML = `<img src="${img}" alt="${title}">`;
        }
    } else {
        body.innerHTML = `<img src="${img}" alt="${title}">`;
    }

    document.getElementById('mediaLightbox').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeMediaModal(e) {
    if (!e || e.target === document.getElementById('mediaLightbox') || e.target.classList.contains('media-lightbox-close')) {
        const body = document.getElementById('lightboxBody');
        body.innerHTML = ''; // Videoyu durdur
        document.getElementById('mediaLightbox').style.display = 'none';
        document.body.style.overflow = '';
    }
}

// ESC ile kapatma
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeMediaModal(null);
    }
});
</script>

