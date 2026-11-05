<?php
/**
 * BİKGRUP - Açık Renkli, Yaratıcı & Sanatsal Altbilgi (Footer) Şablonu
 * Sol Tarafta Instagram Butonu, Sağ Tarafta WhatsApp Butonu
 */

declare(strict_types=1);

$currentLang = Lang::current();
$whatsapp = Settings::get('site_whatsapp', '+905320000000');
$phone = Settings::get('site_phone', '+90 (242) 555 01 23');
$email = Settings::get('site_email', 'destek@bikgrup.com');
$address = Settings::getLocalized('site_address', 'Organize Sanayi Bölgesi 4. Cadde No: 18, Antalya / TÜRKİYE');
$instagram = trim(Settings::get('social_instagram', ''));
$facebook  = trim(Settings::get('social_facebook', ''));
$youtube   = trim(Settings::get('social_youtube', ''));
$linkedin  = trim(Settings::get('social_linkedin', ''));
$twitter   = trim(Settings::get('social_x', ''));

$footerSec = PageSection::get('footer_main') ?? [];
$footerExtra = $footerSec['extra'] ?? [];
$siteLogo = Settings::get('site_logo', '');
$siteLogoText = Settings::get('site_logo_text', 'BİK GRUP');
?>

    <?php if (!empty($instagram) && $instagram !== '#'): ?>
    <!-- SOL ALT: Floating Instagram Butonu (Kullanıcı Talebi) -->
    <a href="<?= htmlspecialchars($instagram) ?>" 
       target="_blank" 
       rel="noopener noreferrer" 
       class="floating-instagram-btn anim-pulse" 
       title="Instagram'da BİKGRUP'u Takip Edin">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
        </svg>
        <span>Instagram</span>
    </a>
    <?php endif; ?>

    <!-- SAĞ ALT: Floating Ara Butonu (WhatsApp Butonunun Üzerinde) -->
    <a href="tel:<?= preg_replace('/[^0-9+]/', '', $phone) ?>" 
       class="floating-call-btn anim-pulse" 
       title="<?= $currentLang === 'en' ? 'Call Us: ' . htmlspecialchars($phone) : 'Bizi Arayın: ' . htmlspecialchars($phone) ?>">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
        </svg>
        <span><?= $currentLang === 'en' ? 'Call' : 'Ara' ?></span>
    </a>

    <!-- SAĞ ALT: Floating WhatsApp Butonu -->
    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $whatsapp) ?>?text=Merhaba,%20BİK%20GRUP%20ürünleri%20hakkında%20bilgi%20almak%20istiyorum." 
       target="_blank" 
       rel="noopener noreferrer" 
       class="floating-action-btn anim-pulse" 
       title="<?= $currentLang === 'en' ? 'Inquire via WhatsApp' : 'WhatsApp ile Bilgi Al' ?>">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12.031 2C6.495 2 2 6.495 2 12.031c0 1.956.564 3.782 1.541 5.334L2 22l4.795-1.503a9.98 9.98 0 005.236 1.488c5.536 0 10.031-4.495 10.031-10.031C22.062 6.495 17.567 2 12.031 2zm5.733 14.18c-.244.685-1.42 1.306-1.954 1.365-.494.054-1.127.08-3.266-.807-2.735-1.134-4.507-3.904-4.644-4.088-.136-.184-1.112-1.478-1.112-2.82 0-1.341.704-2.001.954-2.274.244-.265.535-.333.712-.333.178 0 .356.002.511.01.168.008.39-.064.611.468.228.552.778 1.899.845 2.036.067.137.112.298.023.475-.09.178-.134.29-.267.445-.133.155-.28.347-.399.466-.134.133-.274.278-.118.545.156.267.692 1.142 1.487 1.85 1.024.912 1.888 1.195 2.155 1.328.267.134.423.112.578-.067.156-.178.667-.778.845-1.045.178-.267.356-.222.6-.133.245.089 1.556.734 1.823.867.267.134.445.2.511.312.067.111.067.645-.177 1.33z"/>
        </svg>
        <span><?= $currentLang === 'en' ? 'Get Info' : 'Bilgi Al' ?></span>
    </a>

    <!-- YENİ: AÇIK RENKLİ & SANATSAL MİMARİ FOOTER (KULLANICI TALEBİ) -->
    <footer class="footer-creative">
        <div class="container">
            <!-- Footer Alanları (Beyaz Blok Kutusu Kaldırıldı, Doğal & Ferah Mimari) -->
            <div class="footer-grid">
                
                <!-- 1. Marka ve Tanıtım -->
                <div class="footer-col-brand">
                    <a href="<?= BASE_URL ?>/" class="bik-logo" style="margin-bottom: 20px;">
                        <?php if (!empty($siteLogo) && file_exists(UPLOADS_PATH . '/' . $siteLogo)): ?>
                            <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($siteLogo) ?>" alt="<?= htmlspecialchars($siteLogoText) ?>" style="max-height: 58px; width: auto; object-fit: contain;">
                        <?php else: ?>
                            <span class="logo-leaf-icon">🌿</span>
                            <span class="logo-brand"><span class="bold">BİK</span><span class="accent">GRUP</span></span>
                        <?php endif; ?>
                    </a>
                    <p style="color: #64748b; font-size: 15px; line-height: 1.8; margin-bottom: 26px; max-width: 360px;">
                        <?= !empty($footerSec['body_' . $currentLang]) ? nl2br(htmlspecialchars($footerSec['body_' . $currentLang])) : ($currentLang === 'en' ? __('footer_about_text') : (!empty($footerSec['body_tr']) ? nl2br(htmlspecialchars($footerSec['body_tr'])) : __('footer_about_text'))) ?>
                    </p>
                    
                    <!-- Sosyal Medya İkonları (Panele Bağlantı Girildiyse Görünür, Boşsa Pasif Kalır) -->
                    <?php if (!empty($instagram) || !empty($facebook) || !empty($youtube) || !empty($linkedin) || !empty($twitter)): ?>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px;">
                        <?php if (!empty($instagram) && $instagram !== '#'): ?>
                            <a href="<?= htmlspecialchars($instagram) ?>" target="_blank" rel="noopener noreferrer" style="display:inline-flex; align-items:center; justify-content:center; width:40px; height:40px; border-radius:12px; background:#f1f5f9; color:#e1306c; transition:all 0.2s;" title="Instagram">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($facebook) && $facebook !== '#'): ?>
                            <a href="<?= htmlspecialchars($facebook) ?>" target="_blank" rel="noopener noreferrer" style="display:inline-flex; align-items:center; justify-content:center; width:40px; height:40px; border-radius:12px; background:#f1f5f9; color:#1877f2; transition:all 0.2s;" title="Facebook">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($youtube) && $youtube !== '#'): ?>
                            <a href="<?= htmlspecialchars($youtube) ?>" target="_blank" rel="noopener noreferrer" style="display:inline-flex; align-items:center; justify-content:center; width:40px; height:40px; border-radius:12px; background:#f1f5f9; color:#ff0000; transition:all 0.2s;" title="YouTube">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($linkedin) && $linkedin !== '#'): ?>
                            <a href="<?= htmlspecialchars($linkedin) ?>" target="_blank" rel="noopener noreferrer" style="display:inline-flex; align-items:center; justify-content:center; width:40px; height:40px; border-radius:12px; background:#f1f5f9; color:#0a66c2; transition:all 0.2s;" title="LinkedIn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($twitter) && $twitter !== '#'): ?>
                            <a href="<?= htmlspecialchars($twitter) ?>" target="_blank" rel="noopener noreferrer" style="display:inline-flex; align-items:center; justify-content:center; width:40px; height:40px; border-radius:12px; background:#f1f5f9; color:#0f172a; transition:all 0.2s;" title="X (Twitter)">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- 2. Hızlı Kurumsal Bağlantılar -->
                <div>
                    <h4 class="footer-heading"><?= htmlspecialchars($currentLang === 'en' ? ($footerExtra['quick_links_title_en'] ?? __('footer_quick_links')) : ($footerExtra['quick_links_title'] ?? __('footer_quick_links'))) ?></h4>
                    <ul class="footer-links">
                        <li><a href="<?= BASE_URL ?>/"><?= __('nav_home') ?></a></li>
                        <li><a href="<?= BASE_URL ?>/kurumsal"><?= __('nav_about') ?></a></li>
                        <li><a href="<?= BASE_URL ?>/markalar"><?= __('nav_brands', 'Markalarımız') ?></a></li>
                        <li><a href="<?= BASE_URL ?>/urunler"><?= __('nav_products') ?></a></li>
                        <li><a href="<?= BASE_URL ?>/galeri"><?= $currentLang === 'en' ? 'Gallery' : 'Galeri' ?></a></li>
                        <li><a href="<?= BASE_URL ?>/blog"><?= __('nav_blog') ?></a></li>
                        <li><a href="<?= BASE_URL ?>/iletisim"><?= __('nav_contact') ?></a></li>
                    </ul>
                </div>

                <!-- 3. Ürün Kategorileri -->
                <div>
                    <h4 class="footer-heading"><?= htmlspecialchars($currentLang === 'en' ? ($footerExtra['products_title_en'] ?? __('nav_products')) : ($footerExtra['products_title'] ?? __('nav_products'))) ?></h4>
                    <ul class="footer-links">
                        <li><a href="<?= BASE_URL ?>/urunler?kategori=hububat"><?= $currentLang === 'en' ? 'Grains & Crops' : 'Hububat' ?></a></li>
                        <li><a href="<?= BASE_URL ?>/urunler?kategori=narenciye"><?= __('cat_citrus') ?></a></li>
                        <li><a href="<?= BASE_URL ?>/urunler?kategori=meyveler"><?= __('cat_fruits') ?></a></li>
                        <li><a href="<?= BASE_URL ?>/urunler?kategori=sebzeler"><?= __('cat_vegetables') ?></a></li>
                        <li><a href="<?= BASE_URL ?>/kurumsal#sertifikalar"><?= $currentLang === 'en' ? 'Quality Certificates' : 'Kalite Belgeleri' ?></a></li>
                    </ul>
                </div>

                <!-- 4. KULLANICI TALEBİ: Politikalar Sütunu -->
                <div>
                    <h4 class="footer-heading"><?= htmlspecialchars($currentLang === 'en' ? ($footerExtra['policies_title_en'] ?? 'Policies') : ($footerExtra['policies_title'] ?? 'Politikalar')) ?></h4>
                    <ul class="footer-links">
                        <li><a href="<?= BASE_URL ?>/gizlilik"><?= $currentLang === 'en' ? 'Privacy Policy' : 'Gizlilik Politikası' ?></a></li>
                        <li><a href="<?= BASE_URL ?>/kvkk"><?= $currentLang === 'en' ? 'GDPR / KVKK Clarification' : 'KVKK Aydınlatma Metni' ?></a></li>
                        <li><a href="<?= BASE_URL ?>/cerez-politikasi"><?= $currentLang === 'en' ? 'Cookie Policy' : 'Çerez Politikası' ?></a></li>
                    </ul>
                </div>

                <!-- 5. İletişim & Lokasyon -->
                <div class="footer-col-contact">
                    <h4 class="footer-heading"><?= htmlspecialchars($currentLang === 'en' ? ($footerExtra['contact_title_en'] ?? __('footer_contact_info')) : ($footerExtra['contact_title'] ?? __('footer_contact_info'))) ?></h4>
                    <div class="footer-contact-item footer-address-item">
                        <span class="footer-contact-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                        </span>
                        <?php $addressMapLink = Settings::get('site_address_map_link', 'https://maps.app.goo.gl/KxejLkF6GA7i7LsBA'); ?>
                        <a href="<?= htmlspecialchars($addressMapLink) ?>" target="_blank" rel="noopener noreferrer" class="footer-address-link" onmouseover="this.style.color='var(--theme-primary)'" onmouseout="this.style.color='inherit'" title="Google Haritalarda Aç">
                            <?= nl2br(htmlspecialchars($address)) ?>
                        </a>
                    </div>
                    <div class="footer-contact-item">
                        <span class="footer-contact-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                        </span>
                        <a href="tel:<?= preg_replace('/[^0-9+]/', '', $phone) ?>" style="color: var(--theme-primary); font-weight:700;">
                            <?= htmlspecialchars($phone) ?>
                        </a>
                    </div>
                    <div class="footer-contact-item">
                        <span class="footer-contact-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="16" x="2" y="4" rx="2"/>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                            </svg>
                        </span>
                        <a href="mailto:<?= htmlspecialchars($email) ?>" style="color: #475569;">
                            <?= htmlspecialchars($email) ?>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Alt Telif Çubuğu: PC'de Tek Satır, Mobilde Şık Alt Alta -->
            <div class="footer-bottom-bar">
                <div class="footer-copyright-text">
                    <span class="footer-copy-line1">&copy; <?= date('Y') ?> <strong><?= htmlspecialchars(Settings::get('site_name', 'BİK GRUP')) ?></strong>.</span>
                    <span class="footer-copy-line2"><?= htmlspecialchars($currentLang === 'en' ? ($footerExtra['copyright_text_en'] ?? 'All rights reserved.') : ($footerExtra['copyright_text'] ?? 'Tüm hakları saklıdır.')) ?></span>
                </div>
            </div>
        </div>
    </footer>

    <!-- TEKLİF AL MODAL (KULLANICI TALEBİ: ÜRÜN KATEGORİSİ, MİKTAR, AD SOYAD, TEL, MAİL) -->
    <div id="quoteModal" class="quote-modal-backdrop" onclick="if(event.target === this) closeQuoteModal();" role="dialog" aria-modal="true" aria-labelledby="quoteModalTitle">
        <div class="quote-modal-card" style="max-width: 600px;">
            <div class="quote-modal-header">
                <h3 class="quote-modal-title" id="quoteModalTitle">
                    <span style="color: #ea580c;">⚡</span>
                    <span><?= $currentLang === 'en' ? 'Request a Fast Quote' : 'Hızlı İhracat Teklifi Al' ?></span>
                </h3>
                <button type="button" class="quote-modal-close" onclick="closeQuoteModal()" aria-label="Kapat">✕</button>
            </div>
            <div class="quote-modal-body">
                <form id="quoteRequestForm" onsubmit="submitQuoteRequest(event)">
                    <div id="quoteFormAlert" style="display: none; padding: 12px 16px; border-radius: 12px; font-size: 14px; font-weight: 600; margin-bottom: 16px;"></div>

                    <div class="quote-form-grid">
                        <!-- Ürün / Kategori Seçimi -->
                        <div class="quote-form-group">
                            <label for="quoteCategory" class="quote-form-label">
                                🍊 <?= $currentLang === 'en' ? 'Product / Category *' : 'Ürün / Kategori Seçimi *' ?>
                            </label>
                            <div class="custom-select-wrapper" id="quoteCategoryDropdown">
                                <input type="hidden" id="quoteCategory" name="product_category" value="">
                                <button type="button" class="custom-select-trigger" id="quoteCategoryTrigger" aria-haspopup="listbox" aria-expanded="false">
                                    <span class="custom-select-placeholder" id="quoteCategoryText"><?= $currentLang === 'en' ? 'Select product or category...' : 'Ürün veya Kategori seçiniz...' ?></span>
                                    <svg class="custom-select-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </button>
                                <div class="custom-select-dropdown" id="quoteCategoryList" role="listbox" style="max-height: 280px; overflow-y: auto !important; -webkit-overflow-scrolling: touch; overscroll-behavior: contain;">
                                    <?php
                                    $modalCategories = Category::all(true);
                                    $modalProducts = Product::all(true);
                                    $catIconMap = [
                                        'narenciye' => '🍊',
                                        'hububat'   => '🌾',
                                        'meyveler'  => '🍉',
                                        'sebzeler'  => '🍅',
                                        'bakliyat'  => '🌱'
                                    ];
                                    ?>
                                    <?php foreach ($modalCategories as $mCat): ?>
                                        <?php 
                                        $mCatId = (int)$mCat['id'];
                                        $mCatSlug = $mCat['slug'] ?? '';
                                        $mCatName = $mCat['name_' . $currentLang] ?? $mCat['name_tr'];
                                        $mCatIcon = $catIconMap[$mCatSlug] ?? '🌿';
                                        $catProds = array_filter($modalProducts, fn($p) => (int)$p['category_id'] === $mCatId);
                                        ?>
                                        <div class="custom-select-group">
                                            <div class="custom-select-group-header">
                                                <span class="cs-icon"><?= $mCatIcon ?></span>
                                                <span><?= htmlspecialchars($mCatName) ?></span>
                                            </div>
                                            <div class="custom-select-item" data-value="<?= htmlspecialchars($mCatName) ?> (Genel)">
                                                <span class="item-text"><?= htmlspecialchars($mCatName) ?> (<?= $currentLang === 'en' ? 'All / General Request' : 'Tüm / Genel Talep' ?>)</span>
                                                <span class="item-check">✓</span>
                                            </div>
                                            <?php foreach ($catProds as $mProd): ?>
                                                <?php 
                                                $mProdName = $mProd['name_' . $currentLang] ?? $mProd['name_tr'];
                                                ?>
                                                <div class="custom-select-item" data-value="<?= htmlspecialchars($mProdName) ?>">
                                                    <span class="item-text"><?= htmlspecialchars($mProdName) ?></span>
                                                    <span class="item-check">✓</span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endforeach; ?>

                                    <!-- Özel / Karma Sevkiyat Talepleri -->
                                    <div class="custom-select-group">
                                        <div class="custom-select-group-header">
                                            <span class="cs-icon">📦</span>
                                            <span><?= $currentLang === 'en' ? 'Special Requests' : 'Özel Talepler' ?></span>
                                        </div>
                                        <div class="custom-select-item" data-value="Karma / Özel İhracat Talebi">
                                            <span class="item-text"><?= $currentLang === 'en' ? 'Mixed / Custom Export Request' : 'Karma / Özel İhracat Talebi' ?></span>
                                            <span class="item-check">✓</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ürün Miktarı (Yazıyla) -->
                        <div class="quote-form-group">
                            <label for="quoteQuantity" class="quote-form-label">
                                <?= $currentLang === 'en' ? 'Quantity (in words) *' : 'Ürün Miktarı (Yazıyla) *' ?>
                            </label>
                            <input type="text" id="quoteQuantity" name="quantity" required class="quote-form-input">
                        </div>

                        <!-- Ad Soyad -->
                        <div class="quote-form-group">
                            <label for="quoteFullName" class="quote-form-label">
                                <?= $currentLang === 'en' ? 'Full Name *' : 'Adınız ve Soyadınız *' ?>
                            </label>
                            <input type="text" id="quoteFullName" name="full_name" required class="quote-form-input">
                        </div>

                        <!-- Telefon Numarası -->
                        <div class="quote-form-group">
                            <label for="quotePhone" class="quote-form-label">
                                <?= $currentLang === 'en' ? 'Phone / WhatsApp *' : 'Telefon Numarası *' ?>
                            </label>
                            <input type="tel" id="quotePhone" name="phone" required class="quote-form-input">
                        </div>

                        <!-- E-Posta (Opsiyonel) -->
                        <div class="quote-form-group quote-form-full">
                            <label for="quoteEmail" class="quote-form-label">
                                <?= $currentLang === 'en' ? 'Email Address (Optional)' : 'E-Posta Adresi (Opsiyonel)' ?>
                            </label>
                            <input type="email" id="quoteEmail" name="email" class="quote-form-input">
                        </div>

                        <!-- Ek Notlar -->
                        <div class="quote-form-group quote-form-full">
                            <label for="quoteNotes" class="quote-form-label">
                                <?= $currentLang === 'en' ? 'Additional Notes / Destination' : 'Ek Açıklama / Hedef Liman veya Ülke (Opsiyonel)' ?>
                            </label>
                            <textarea id="quoteNotes" name="notes" rows="2" class="quote-form-input"></textarea>
                        </div>
                    </div>

                    <div class="quote-modal-actions">
                        <button type="button" class="btn btn-outline" onclick="closeQuoteModal()" style="border-radius: 999px;">
                            <?= $currentLang === 'en' ? 'Cancel' : 'İptal' ?>
                        </button>
                        <button type="submit" id="quoteSubmitBtn" class="btn-quote-send">
                            <span><?= $currentLang === 'en' ? 'Submit Quote Request' : 'Teklif Talebini Gönder' ?></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ÇEREZ POLİTİKASI (COOKIE CONSENT) BANNER -->
    <div id="cookieConsentBanner" class="cookie-consent-bar" style="display: none;" role="region" aria-label="<?= $currentLang === 'en' ? 'Cookie Consent' : 'Çerez Onayı' ?>">
        <div class="cookie-consent-inner">
            <div class="cookie-consent-icon" aria-hidden="true">
                <span>🍪</span>
            </div>
            <div class="cookie-consent-text">
                <strong class="cookie-consent-title">
                    <?= $currentLang === 'en' ? 'Cookie Policy & Privacy' : 'Çerez Politikası ve Gizlilik' ?>
                </strong>
                <p class="cookie-consent-desc">
                    <?= $currentLang === 'en'
                        ? 'We use cookies to enhance your browsing experience, analyze site traffic, and ensure seamless performance. Learn more in our <a href="' . BASE_URL . '/cerez-politikasi?lang=en" target="_blank" rel="noopener">Cookie Policy</a>.'
                        : 'Sizlere daha iyi bir gezinme deneyimi sunmak, site trafiğini analiz etmek ve güvenli bir kullanım sağlamak amacıyla çerezler (cookies) kullanıyoruz. Detaylı bilgi için <a href="' . BASE_URL . '/cerez-politikasi" target="_blank" rel="noopener">Çerez Politikamızı</a> inceleyebilirsiniz.' ?>
                </p>
            </div>
            <div class="cookie-consent-actions">
                <button type="button" class="cookie-btn cookie-btn-reject" onclick="handleCookieConsent('rejected')">
                    <?= $currentLang === 'en' ? 'Reject' : 'Reddet' ?>
                </button>
                <button type="button" class="cookie-btn cookie-btn-accept" onclick="handleCookieConsent('accepted')">
                    <?= $currentLang === 'en' ? 'Accept All' : 'Kabul Et' ?>
                </button>
            </div>
        </div>
    </div>

    <style>
    .cookie-consent-bar {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%) translateY(40px);
        width: calc(100% - 36px);
        max-width: 660px;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1.5px solid rgba(226, 232, 240, 0.95);
        border-radius: 20px;
        box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.18), 0 0 0 1px rgba(255, 255, 255, 0.8) inset;
        z-index: 99998;
        padding: 18px 22px;
        opacity: 0;
        transition: opacity 0.35s ease, transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        pointer-events: none;
    }
    .cookie-consent-bar.cookie-show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
        pointer-events: auto;
    }
    .cookie-consent-inner {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .cookie-consent-icon {
        font-size: 28px;
        line-height: 1;
        flex-shrink: 0;
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fef3c7;
        border-radius: 14px;
    }
    .cookie-consent-text {
        flex: 1;
        min-width: 0;
    }
    .cookie-consent-title {
        display: block;
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 3px;
        letter-spacing: -0.2px;
    }
    .cookie-consent-desc {
        font-size: 13px;
        line-height: 1.45;
        color: #475569;
        margin: 0;
    }
    .cookie-consent-desc a {
        color: #15803d;
        font-weight: 700;
        text-decoration: underline;
        text-underline-offset: 2px;
        transition: color 0.15s ease;
    }
    .cookie-consent-desc a:hover {
        color: #166534;
    }
    .cookie-consent-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }
    .cookie-btn {
        padding: 9px 18px;
        font-size: 13px;
        font-weight: 700;
        border-radius: 9999px;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        outline: none;
        font-family: inherit;
        white-space: nowrap;
    }
    .cookie-btn-accept {
        background: #15803d;
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(21, 128, 61, 0.3);
    }
    .cookie-btn-accept:hover {
        background: #166534;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(21, 128, 61, 0.4);
    }
    .cookie-btn-reject {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }
    .cookie-btn-reject:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    @media (max-width: 768px) {
        .cookie-consent-bar {
            bottom: 84px;
            left: 12px;
            right: 12px;
            width: auto;
            transform: translateY(40px);
            padding: 14px 16px;
            border-radius: 18px;
        }
        .cookie-consent-bar.cookie-show {
            transform: translateY(0);
        }
        .cookie-consent-inner {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        .cookie-consent-icon {
            display: none;
        }
        .cookie-consent-actions {
            width: 100%;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .cookie-btn {
            flex: 1;
            text-align: center;
            padding: 10px 14px;
        }
    }
    </style>

    <script>
    (function() {
        const consentKey = 'bikgrup_cookie_consent';
        const banner = document.getElementById('cookieConsentBanner');
        if (!banner) return;

        try {
            const currentConsent = localStorage.getItem(consentKey);
            if (!currentConsent) {
                banner.style.display = 'block';
                setTimeout(function() {
                    banner.classList.add('cookie-show');
                }, 600);
            }
        } catch (e) {
            banner.style.display = 'block';
            banner.classList.add('cookie-show');
        }

        window.handleCookieConsent = function(status) {
            try {
                localStorage.setItem(consentKey, status);
            } catch (e) {}
            banner.classList.remove('cookie-show');
            setTimeout(function() {
                banner.style.display = 'none';
            }, 350);
        };
    })();
    </script>

    <!-- Ana JavaScript Motoru -->
    <script src="<?= BASE_URL ?>/public/js/main.js?v=<?= file_exists(PUBLIC_PATH . '/js/main.js') ? filemtime(PUBLIC_PATH . '/js/main.js') : time() ?>"></script>
</body>
</html>
