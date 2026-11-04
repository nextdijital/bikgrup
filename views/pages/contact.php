<?php
/**
 * BIKGRUP - Iletisim Sayfasi
 * Adler Tarzi Cizgisel Cerceveli Dashboard & Canli Harita
 */

declare(strict_types=1);

$currentLang = Lang::current();
$whatsapp = Settings::get('site_whatsapp', '+905320000000');
$phone = Settings::get('site_phone', '+90 (242) 555 01 23');
$email = Settings::get('site_email', 'destek@bikgrup.com');
$address = Settings::getLocalized('site_address', 'Organize Sanayi Bölgesi 4. Cadde No: 18, Antalya / TÜRKİYE');
$defaultSubject = $_GET['konu'] ?? $_GET['subject'] ?? '';

$contactHero = PageSection::get('contact_hero') ?? [];
$contactHeroExtra = $contactHero['extra'] ?? [];
$siteLogo = Settings::get('site_logo', '');
$siteLogoText = Settings::get('site_logo_text', 'BİK GRUP');

$hasHero = true;
$GLOBALS['hasHero'] = true;
?>

<!-- ========================================================================
     ILETISIM - CIZGISEL MIMARI HERO & HARITA DASHBOARD (KULLANICI TALEBI)
     ======================================================================== -->
<section class="adler-hero-viewport" style="min-height: calc(100vh - 40px); margin-bottom: 0;">
    
    <!-- Ikonik Beyaz Cizgisel Cerceve (Ortalanmis Mimari) -->
    <div class="adler-contour-frame frame-centered">
        
        <!-- Çerçevenin İçiyle Sınırlandırılmış Narenciye & Bahce Arka Plani -->
        <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($contactHeroExtra['bg_image'] ?? 'citrus_showcase.jpg') ?>" alt="BIKGRUP Iletisim ve Global Ofisler" class="adler-bg-poster" loading="eager">
        <div class="adler-vignette"></div>
        
        <!-- Cercevenin Ust Siniri: Entegre Minimal Header -->
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
                <a href="<?= BASE_URL ?>/iletisim" class="adler-nav-link active"><?= __('nav_contact') ?></a>
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

            <button class="mobile-toggle" aria-label="Menu">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>

        <!-- Çerçevenin Merkezi: BAŞLIK, KÜÇÜK DİKDÖRTGEN İLETİŞİM KARTLARI VE HARİTA KUTUSU -->
        <div class="adler-inner-center-hero" style="max-width: 1040px; padding: 15px 20px 5px;">
            <h1 class="adler-inner-mega-title" style="margin-bottom: 8px; font-size: clamp(28px, 3.8vw, 48px);">
                <?= !empty($contactHero['title_' . $currentLang]) ? nl2br(htmlspecialchars($contactHero['title_' . $currentLang])) : (!empty($contactHero['title_tr']) ? nl2br(htmlspecialchars($contactHero['title_tr'])) : ($currentLang === 'en' ? 'Connect With Us.' : 'Bizimle İletişime Geçin.')) ?>
            </h1>
            <p class="adler-inner-desc" style="margin-bottom: 16px; font-size: 15px; max-width: 680px;">
                <?= htmlspecialchars($contactHero['body_' . $currentLang] ?? ($contactHero['body_tr'] ?? ($currentLang === 'en' 
                    ? 'Get in touch with our expert trade team anytime for fresh produce sourcing, export partnership, or facility visits.' 
                    : 'Taze ürün tedariği, ihracat ortaklığı veya tesis ziyaretleri için uzman ekibimizle dilediğiniz zaman iletişime geçebilirsiniz.'))) ?>
            </p>

            <!-- KULLANICI TALEBİ: KÜÇÜK DİKDÖRTGEN KARTLAR VE HARİTA KUTUSU DASHBOARD -->
            <div class="contact-hero-dashboard">
                
                <!-- Sol Kolon: Küçük Dikdörtgen İletişim Kartları -->
                <div class="contact-cards-stack">
                    
                    <!-- 1. Adres Kartı (Küçük Dikdörtgen) -->
                    <?php $addressMapLink = Settings::get('site_address_map_link', 'https://maps.app.goo.gl/KxejLkF6GA7i7LsBA'); ?>
                    <a href="<?= htmlspecialchars($addressMapLink) ?>" target="_blank" rel="noopener noreferrer" class="contact-compact-rect" title="Haritada Aç">
                        <div class="contact-rect-icon">📍</div>
                        <div class="contact-rect-content">
                            <span class="contact-rect-label"><?= $currentLang === 'en' ? 'LOCATIONS & OFFICES' : 'MERKEZ & ŞUBELER' ?></span>
                            <span class="contact-rect-val" style="line-height: 1.5;"><?= nl2br(htmlspecialchars($address)) ?></span>
                        </div>
                    </a>

                    <!-- 2. Telefon & WhatsApp Kartı (Küçük Dikdörtgen) -->
                    <a href="tel:<?= preg_replace('/[^0-9+]/', '', $phone) ?>" class="contact-compact-rect" title="Bizi Arayın">
                        <div class="contact-rect-icon">📞</div>
                        <div class="contact-rect-content">
                            <span class="contact-rect-label"><?= $currentLang === 'en' ? 'DIRECT PHONE & WHATSAPP' : 'TELEFON & WHATSAPP' ?></span>
                            <span class="contact-rect-val"><?= htmlspecialchars($phone) ?> &bull; <?= htmlspecialchars($whatsapp) ?></span>
                        </div>
                    </a>

                    <!-- 3. E-Posta & Mesai Kartı (Küçük Dikdörtgen) -->
                    <a href="mailto:<?= htmlspecialchars($email) ?>" class="contact-compact-rect" title="E-Posta Gönderin">
                        <div class="contact-rect-icon">✉️</div>
                        <div class="contact-rect-content">
                            <span class="contact-rect-label"><?= $currentLang === 'en' ? 'OFFICIAL EMAIL & HOURS' : 'E-POSTA & ÇALIŞMA SAATLERİ' ?></span>
                            <span class="contact-rect-val"><?= htmlspecialchars($email) ?></span>
                            <span class="contact-rect-sub" style="display: block; font-size: 13px; color: #64748b; font-weight: 500; margin-top: 2px;">
                                <?= $currentLang === 'en' ? 'Mon - Sat: 08:30 - 18:30' : 'Pzt - Cmt: 08:30 - 18:30' ?>
                            </span>
                        </div>
                    </a>

                </div>

                <!-- Sag Kolon: Harita Gosteren Kutu (Kullanici Google Maps Linki Entegreli) -->
                <div class="contact-map-box" style="position: relative;">
                    <iframe 
                        src="https://maps.google.com/maps?q=Sirkenli%20ANA%20YOL%20%C3%9CZER%C4%B0%2001900%20Karata%C5%9F%20Adana&t=&z=14&ie=UTF8&iwloc=&output=embed" 
                        loading="lazy" 
                        title="BİK GRUP Lokasyon Haritası"
                        allowfullscreen>
                    </iframe>
                    
                    <!-- Dogrudan Google Maps Baglantisi Butonu -->
                    <a href="https://maps.app.goo.gl/KxejLkF6GA7i7LsBA" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       style="position: absolute; bottom: 14px; right: 14px; background: #15803d; color: #ffffff; padding: 10px 20px; border-radius: 9999px; font-weight: 800; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3); z-index: 10; transition: all 0.2s ease;"
                       onmouseover="this.style.background='#166534'; this.style.transform='translateY(-2px)';"
                       onmouseout="this.style.background='#15803d'; this.style.transform='translateY(0)';">
                        <span>📍 <?= $currentLang === 'en' ? 'Open in Google Maps' : 'Google Haritalar\'da Aç' ?></span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="7" y1="17" x2="17" y2="7"></line>
                            <polyline points="7 7 17 7 17 17"></polyline>
                        </svg>
                    </a>
                </div>

            </div>

        </div>

        <!-- Alt Denge Boslugu -->
        <div style="height: 25px;"></div>

    </div>
</section>
<!-- KULLANICI TALEBI: ALTTAN TUM FORMLAR VE EKSTRA ALANLAR KALDIRILDI, HEMEN FOOTER GELIYOR -->
