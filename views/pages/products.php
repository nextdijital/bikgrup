<?php
/**
 * BİKGRUP - Ürünler Listesi Sayfası
 * SEO Uyumlu Temiz URL'ler
 */

declare(strict_types=1);

$currentLang = Lang::current();
$prodHero = PageSection::get('products_hero') ?? [];
$prodHeroExtra = $prodHero['extra'] ?? [];
$siteLogo = Settings::get('site_logo', '');
$siteLogoText = Settings::get('site_logo_text', 'BİK GRUP');
?>

<?php
$hasHero = true;
$GLOBALS['hasHero'] = true;
?>

<!-- ========================================================================
     ÜRÜNLER - ÇİZGİSEL MİMARİ HERO GİRİŞ ALANI (ORTALANMIŞ & SOL KARTSIZ)
     ======================================================================== -->
<section class="adler-hero-viewport">
    
    <!-- İkonik Beyaz Çizgisel Çerçeve (Ortalanmış Mimari) -->
    <div class="adler-contour-frame frame-centered">
        
        <!-- Çerçevenin İçiyle Sınırlandırılmış Doğa & Tarım Arka Planı -->
        <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($prodHeroExtra['bg_image'] ?? 'nature_hero_bg.jpg') ?>" alt="BİKGRUP Taze Ürünler ve İhracat" class="adler-bg-poster" loading="eager">
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
                <a href="<?= BASE_URL ?>/urunler" class="adler-nav-link active"><?= __('nav_products') ?></a>
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

        <!-- Çerçevenin Merkezi: ORTALANMIŞ BAŞLIK VE SADECE TÜM ÜRÜNLER BUTONU -->
        <div class="adler-inner-center-hero">
            <h1 class="adler-inner-mega-title">
                <?= $selectedCategory 
                    ? htmlspecialchars($selectedCategory['name_' . $currentLang] ?? $selectedCategory['name_tr']) 
                    : (!empty($prodHero['title_' . $currentLang]) ? nl2br(htmlspecialchars($prodHero['title_' . $currentLang])) : (!empty($prodHero['title_tr']) ? nl2br(htmlspecialchars($prodHero['title_tr'])) : ($currentLang === 'en' ? 'Fresh Harvest &<br>Global Export.' : 'Taze Mahsul &<br>Global İhracat.'))) ?>
            </h1>
            <p class="adler-inner-desc">
                <?= $selectedCategory 
                    ? htmlspecialchars($selectedCategory['description_' . $currentLang] ?? $selectedCategory['description_tr']) 
                    : htmlspecialchars($prodHero['subtitle_' . $currentLang] ?? ($prodHero['subtitle_tr'] ?? ($currentLang === 'en' ? 'Our nutritious and premium produce grown in the fertile soils of the Mediterranean maintains its peak freshness on its journey from our orchards to your table.' : 'Akdeniz ve Ege nin verimli topraklarinda yetisen besleyici ve saglikli urunlerimiz tarladan sofraniza uzanan yolculukta tazeligini korur.'))) ?>
            </p>

            <!-- KULLANICI TALEBİ: DİĞER SAYFALARLA BİREBİR AYNI TASARIMLI BUTON (İKONSUZ) -->
            <div class="adler-inner-actions">
                <a href="#urunler-vitrini" class="about-pill-btn" style="border-color: #ffffff; color: #ffffff; background: rgba(255,255,255,0.15); backdrop-filter: blur(8px);">
                    <?= htmlspecialchars($prodHeroExtra['all_products_text'] ?? __('cat_all')) ?>
                </a>
            </div>
        </div>

        <!-- Alt Denge Boşluğu -->
        <div style="height: 30px;"></div>

    </div>
</section>

<!-- ÜRÜN KARTLARI BÖLÜMÜ (Aşağı Kaydırma Hedefi) -->
<section class="section" id="urunler-vitrini" style="padding-top: 50px; scroll-margin-top: 40px;">
    <div class="container">
        
        <!-- KULLANICI TALEBİ: ÜRÜN KARTLARININ ÜZERİNDE ORTALANMIŞ KATEGORİ BUTONLARI -->
        <div class="products-filter-bar reveal" style="display: flex; justify-content: center; align-items: center; gap: 12px; margin-bottom: 45px; flex-wrap: wrap;">
            <a href="<?= BASE_URL ?>/urunler#urunler-vitrini" 
               class="cat-filter-btn <?= empty($selectedCategory) ? 'active' : '' ?>">
                <?= __('cat_all') ?>
            </a>
            <?php foreach ($categories as $cat): ?>
                <?php $isActive = !empty($selectedCategory) && (int)$selectedCategory['id'] === (int)$cat['id']; ?>
                <a href="<?= BASE_URL ?>/urunler?kategori=<?= urlencode($cat['slug']) ?>#urunler-vitrini" 
                   class="cat-filter-btn <?= $isActive ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat['name_' . $currentLang] ?? $cat['name_tr']) ?>
                </a>
            <?php endforeach; ?>
        </div>
        
        <!-- DİNAMİK YENİLENEN ÜRÜN KARTLARI KONTEYNERİ (SAYFA YENİLENMEDEN YALNIZCA KARTLAR GÜNCELLENİR) -->
        <div id="products-dynamic-container" style="transition: opacity 0.22s ease; min-height: 280px;">
            <?php if (empty($products)): ?>
                <div style="text-align:center; padding: 60px 20px; background:#ffffff; border-radius: 20px; border:1px solid #e2e8f0;">
                    <p style="font-size: 18px; color: var(--theme-text-muted); margin: 0 0 16px 0;"><?= $currentLang === 'en' ? 'No published products found in this category yet.' : 'Bu kategoride henüz yayınlanmış ürün bulunamadı.' ?></p>
                    <a href="<?= BASE_URL ?>/urunler#urunler-vitrini" class="btn btn-primary" style="border-radius: 9999px;"><?= $currentLang === 'en' ? 'Return to All Products' : 'Tüm Ürünlere Dön' ?></a>
                </div>
            <?php else: ?>
                <div class="popout-products-grid">
                    <?php foreach ($products as $index => $prod): ?>
                        <div class="popout-product-card card-color-<?= ($index % 4) + 1 ?> reveal reveal-delay-<?= ($index % 3) + 1 ?>">
                            <!-- Karttan Dışarı Taşan 3 Boyutlu Ürün Görseli -->
                            <a href="<?= BASE_URL ?>/urunler/<?= urlencode($prod['slug']) ?>" class="popout-img-wrapper">
                                <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($prod['image'] ?: 'product_orange.png') ?>" 
                                     alt="<?= htmlspecialchars($prod['name_' . $currentLang] ?? $prod['name_tr']) ?>" 
                                     class="popout-floating-img" 
                                     loading="lazy">
                            </a>

                            <!-- Kart Alt İçeriği: Ürün İsimleri & Sağ Altta Çapraz Ok Butonu -->
                            <div class="popout-card-bottom">
                                <div class="popout-card-text">
                                    <span class="popout-card-prefix"><?= $currentLang === 'en' ? 'Fresh & Premium' : 'Taze & Doğal' ?></span>
                                    <h3 class="popout-card-title">
                                        <a href="<?= BASE_URL ?>/urunler/<?= urlencode($prod['slug']) ?>">
                                            <?= htmlspecialchars($prod['name_' . $currentLang] ?? $prod['name_tr']) ?>
                                        </a>
                                    </h3>
                                </div>

                                <!-- Sayı yerine çapraz ok işareti olan dairesel buton -->
                                <a href="<?= BASE_URL ?>/urunler/<?= urlencode($prod['slug']) ?>" class="popout-circle-btn" aria-label="<?= htmlspecialchars($prod['name_' . $currentLang] ?? $prod['name_tr']) . ' ' . ($currentLang === 'en' ? 'Inspect' : 'İncele') ?>">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="7" y1="17" x2="17" y2="7"></line>
                                        <polyline points="7 7 17 7 17 17"></polyline>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<!-- KULLANICI TALEBİ: SAYFA YENİLENMEDEN SADECE KARTLARI PÜRÜZSÜZ GÜNCELLEYEN SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const filterBtns = document.querySelectorAll('.cat-filter-btn');
    const container = document.getElementById('products-dynamic-container');

    // Sayfa ilk yüklendiğinde URL'de scroll varsa vitrine kaydır
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('scroll') || urlParams.has('kategori') || window.location.hash.includes('urunler-vitrini')) {
        const target = document.getElementById('urunler-vitrini');
        if (target) {
            setTimeout(() => {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 80);
        }
    }

    // Hero'daki Tüm Ürünler butonuna tıklandığında yumuşak kaydır
    const heroBtn = document.querySelector('a[href*="urunler-vitrini"]');
    if (heroBtn) {
        heroBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.getElementById('urunler-vitrini');
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    }

    if (!filterBtns.length || !container) return;

    // Kategori butonlarına SPA / AJAX tıklandığında sayfa yenilenmesini engelle
    filterBtns.forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const targetUrl = btn.getAttribute('href');
            if (!targetUrl || btn.classList.contains('active')) return;

            // Aktif buton stilini anında güncelle
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Kartları hafifçe soldur (fade-out)
            container.style.opacity = '0.35';

            try {
                const response = await fetch(targetUrl);
                if (!response.ok) throw new Error('Yüklenemedi');

                const htmlText = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(htmlText, 'text/html');

                const newContent = doc.getElementById('products-dynamic-container');
                if (newContent) {
                    container.innerHTML = newContent.innerHTML;
                    container.querySelectorAll('.reveal').forEach(el => el.classList.add('active'));
                }

                // Sayfa yenilenmeden adres çubuğundaki URL'yi güncelle
                window.history.pushState({ path: targetUrl }, '', targetUrl);

            } catch (err) {
                console.error('Kategori yükleme hatası:', err);
                window.location.href = targetUrl;
            } finally {
                // Kartları tekrar netleştir (fade-in)
                setTimeout(() => {
                    container.style.opacity = '1';
                }, 50);
            }
        });
    });

    // Sayfa ilk açıldığında da container içindeki kartları aktif et
    container.querySelectorAll('.reveal').forEach(el => el.classList.add('active'));

    // Tarayıcının Geri / İleri butonlarına tıklandığında da sayfa yenilenmeden çalışsın
    window.addEventListener('popstate', async () => {
        const currentUrl = window.location.href;
        container.style.opacity = '0.35';
        try {
            const res = await fetch(currentUrl);
            const text = await res.text();
            const doc = new DOMParser().parseFromString(text, 'text/html');

            const newContent = doc.getElementById('products-dynamic-container');
            const newFilterBar = doc.querySelector('.products-filter-bar');

            if (newContent) {
                container.innerHTML = newContent.innerHTML;
                container.querySelectorAll('.reveal').forEach(el => el.classList.add('active'));
            }
            if (newFilterBar) {
                document.querySelector('.products-filter-bar').innerHTML = newFilterBar.innerHTML;
            }
        } catch (e) {
            window.location.reload();
        } finally {
            container.style.opacity = '1';
        }
    });
});
</script>
