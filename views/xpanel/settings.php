<?php
/**
 * BİKGRUP - BİK GRUP Yönetim Paneli Genel Ayarlar, Logo & İletişim Bilgileri
 */

declare(strict_types=1);

// Ayarları anahtar-değer haritasına dönüştür
$settingsMap = [];
foreach ($generalSettings as $s) {
    $settingsMap[$s['setting_key']] = $s['setting_value'] ?? '';
}
?>

<div class="panel-card">
    <div class="panel-card-header">
        <div>
            <h2 class="panel-card-title">⚙️ Genel Site Ayarları, Logo & Merkezi İletişim</h2>
            <p style="color: var(--panel-text-muted); font-size: 14px; margin-top: 4px;">
                Tüm sitedeki sabit telefon numaraları, WhatsApp yönlendirmeleri, merkez adres ve logo görsellerini tek bir ortak alandan yönetin.
            </p>
        </div>
    </div>

    <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-settings" method="POST" enctype="multipart/form-data">
        <?= Security::csrfField() ?>

        <!-- 1. LOGO VE GÖRSEL ALANLARI -->
        <div style="background: #f8fafc; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px; margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                <span>🖼️</span> 1. Kurumsal Logo & Favicon Yönetimi
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <!-- Site Logosu -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                    <label class="form-label" style="font-size: 14px; font-weight: 700; color: #1e293b;">Site Ana Logosu</label>
                    <p style="font-size: 12.5px; color: #64748b; margin-top: 2px; margin-bottom: 12px;">Önerilen: Şeffaf PNG veya SVG (240x60px)</p>
                    
                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 14px;">
                        <div style="width: 140px; height: 60px; background: #0c0f0e; border-radius: 8px; display: flex; align-items: center; justify-content: center; padding: 6px; box-sizing: border-box;">
                            <?php if (!empty($settingsMap['site_logo']) && file_exists(UPLOADS_PATH . '/' . $settingsMap['site_logo'])): ?>
                                <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($settingsMap['site_logo']) ?>" alt="Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            <?php else: ?>
                                <span style="color: #ffffff; font-weight: 800; font-size: 16px; letter-spacing: -0.5px;">🌿 BİK<span style="color: #22c55e;">GRUP</span></span>
                            <?php endif; ?>
                        </div>
                        <span style="font-size: 12px; color: #64748b;">(Mevcut Logo)</span>
                    </div>

                    <input type="file" name="site_logo" accept="image/*" class="form-control" style="font-size: 13px; padding: 8px 12px;">
                </div>

                <!-- Favicon (Tarayıcı Sekme İkonu) -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                    <label class="form-label" style="font-size: 14px; font-weight: 700; color: #1e293b;">Tarayıcı Sekme İkonu (Favicon)</label>
                    <p style="font-size: 12.5px; color: #64748b; margin-top: 2px; margin-bottom: 12px;">Önerilen: 64x64 veya 128x128 PNG / ICO</p>

                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 14px;">
                        <div style="width: 48px; height: 48px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                            <?php if (!empty($settingsMap['site_favicon']) && file_exists(UPLOADS_PATH . '/' . $settingsMap['site_favicon'])): ?>
                                <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($settingsMap['site_favicon']) ?>" alt="Favicon" style="width: 32px; height: 32px; object-fit: contain;">
                            <?php else: ?>
                                🌿
                            <?php endif; ?>
                        </div>
                        <span style="font-size: 12px; color: #64748b;">(Mevcut İkon)</span>
                    </div>

                    <input type="file" name="site_favicon" accept="image/*" class="form-control" style="font-size: 13px; padding: 8px 12px;">
                </div>
            </div>
        </div>

        <!-- 2. KURUMSAL FİRMA BİLGİLERİ -->
        <div style="background: #f8fafc; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px; margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                <span>🏢</span> 2. Kurumsal Firma Bilgileri
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Firma Adı</label>
                    <input type="text" name="site_name" value="<?= htmlspecialchars($settingsMap['site_name'] ?? 'BİK GRUP') ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Logo Metni</label>
                    <input type="text" name="site_logo_text" value="<?= htmlspecialchars($settingsMap['site_logo_text'] ?? 'BİK GRUP') ?>" class="form-control">
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Firma Sloganı</label>
                    <input type="text" name="site_tagline_tr" value="<?= htmlspecialchars($settingsMap['site_tagline_tr'] ?? 'Köklerimiz Toprakta, Hedefimiz Dünyada.') ?>" class="form-control">
                </div>
            </div>
        </div>

        <!-- 3. MERKEZİ İLETİŞİM BİLGİLERİ (TÜM SİTEDE ORTAK DEĞİŞEN ALANLAR) -->
        <div style="background: #f8fafc; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px; margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                <span>📞</span> 3. Merkezi İletişim Bilgileri (Tüm Sitede Ortak Değişir)
            </h3>
            <p style="font-size: 13px; color: #64748b; margin-top: -10px; margin-bottom: 18px;">
                Burada güncelleyeceğiniz telefon numarası, WhatsApp ve adres bilgileri sitedeki tüm butonlara (Yüzen Ara Butonu, Yüzen WhatsApp Butonu, Header ve Footer) otomatik yansır.
            </p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Merkez Telefon Numarası (Arama Butonları)</label>
                    <input type="text" name="site_phone" value="<?= htmlspecialchars($settingsMap['site_phone'] ?? '05337989901') ?>" class="form-control" placeholder="05337989901">
                </div>

                <div class="form-group">
                    <label class="form-label">WhatsApp İletişim Numarası (WhatsApp Butonları)</label>
                    <input type="text" name="site_whatsapp" value="<?= htmlspecialchars($settingsMap['site_whatsapp'] ?? '05337989901') ?>" class="form-control" placeholder="05337989901">
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Merkez E-Posta Adresi</label>
                    <input type="email" name="site_email" value="<?= htmlspecialchars($settingsMap['site_email'] ?? 'info@bikgrup.com') ?>" class="form-control" placeholder="info@bikgrup.com">
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label" style="font-weight: 700; color: #0f172a;">🏢 1. Merkez Adres Bilgisi</label>
                    <textarea name="site_address_tr" rows="2" class="form-control" placeholder="Merkez ofis açık adres bilgisi..."><?= htmlspecialchars($settingsMap['site_address_tr'] ?? 'Sirkenli, ANA YOL ÜZERİ, 01900 Karataş/Adana') ?></textarea>
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">📍 Merkez Adres Harita Linki (Google Maps URL)</label>
                    <input type="url" name="site_address_map_link" value="<?= htmlspecialchars($settingsMap['site_address_map_link'] ?? 'https://maps.app.goo.gl/KxejLkF6GA7i7LsBA') ?>" class="form-control" placeholder="https://maps.app.goo.gl/...">
                </div>

                <div class="form-group" style="grid-column: span 2; border-top: 1px dashed #cbd5e1; padding-top: 16px; margin-top: 8px;">
                    <label class="form-label" style="font-weight: 700; color: #0f172a;">🏭 2. Depo Adresi Bilgisi</label>
                    <textarea name="site_warehouse_address_tr" rows="2" class="form-control" placeholder="Paketleme ve soğuk hava deposu açık adres bilgisi..."><?= htmlspecialchars($settingsMap['site_warehouse_address_tr'] ?? 'Sirkenli Paketleme & Soğuk Hava Deposu Tesisleri, Karataş / Adana') ?></textarea>
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">📍 Depo Adresi Harita Linki (Google Maps URL)</label>
                    <input type="url" name="site_warehouse_map_link" value="<?= htmlspecialchars($settingsMap['site_warehouse_map_link'] ?? 'https://maps.app.goo.gl/KxejLkF6GA7i7LsBA') ?>" class="form-control" placeholder="https://maps.app.goo.gl/...">
                </div>
            </div>
        </div>

        <!-- 4. SOSYAL MEDYA PROFİLLERİ -->
        <div style="background: #f8fafc; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px; margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                <span>🌐</span> 4. Sosyal Medya Hesapları
            </h3>
            <p style="font-size: 13px; color: #64748b; margin-top: 0; margin-bottom: 18px;">
                Bağlantı girilen sosyal medya kanalları sitede ve altbilgide (footer) otomatik görünür. Boş bırakılan kanallar pasif sayılır ve sitede gizlenir.
            </p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Instagram Profil Linki</label>
                    <input type="url" name="social_instagram" value="<?= htmlspecialchars($settingsMap['social_instagram'] ?? 'https://instagram.com/bikgrup') ?>" class="form-control" placeholder="https://instagram.com/bikgrup">
                </div>

                <div class="form-group">
                    <label class="form-label">Facebook Sayfa Linki</label>
                    <input type="url" name="social_facebook" value="<?= htmlspecialchars($settingsMap['social_facebook'] ?? '') ?>" class="form-control" placeholder="https://facebook.com/bikgrup">
                </div>

                <div class="form-group">
                    <label class="form-label">YouTube Kanal Linki</label>
                    <input type="url" name="social_youtube" value="<?= htmlspecialchars($settingsMap['social_youtube'] ?? '') ?>" class="form-control" placeholder="https://youtube.com/@bikgrup">
                </div>

                <div class="form-group">
                    <label class="form-label">LinkedIn Profil Linki</label>
                    <input type="url" name="social_linkedin" value="<?= htmlspecialchars($settingsMap['social_linkedin'] ?? 'https://linkedin.com/company/bikgrup') ?>" class="form-control" placeholder="https://linkedin.com/company/bikgrup">
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">X (Twitter) Profil Linki</label>
                    <input type="url" name="social_x" value="<?= htmlspecialchars($settingsMap['social_x'] ?? '') ?>" class="form-control" placeholder="https://x.com/bikgrup">
                </div>
            </div>
        </div>

        <!-- 5. E-POSTA & SMTP BİLDİRİM AYARLARI (HOSTINGER UYUMLU) -->
        <div style="background: #f8fafc; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px; margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                <span>✉️</span> 5. E-Posta & SMTP Bildirim Ayarları (Hostinger Uyumlu)
            </h3>
            <p style="font-size: 13px; color: #64748b; margin-top: 0; margin-bottom: 18px;">
                Gelen ihracat teklifleri ve web sitesi iletişim talepleri bu SMTP sunucusu üzerinden otomatik olarak iletilir. Hostinger kurumsal e-posta ayarlarınız ile %100 uyumludur.
            </p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Giden SMTP Sunucusu (Host)</label>
                    <input type="text" name="smtp_host" value="<?= htmlspecialchars($settingsMap['smtp_host'] ?? 'smtp.hostinger.com') ?>" class="form-control" placeholder="smtp.hostinger.com">
                </div>

                <div class="form-group">
                    <label class="form-label">SMTP Bağlantı Noktası (Port)</label>
                    <input type="number" name="smtp_port" value="<?= htmlspecialchars($settingsMap['smtp_port'] ?? '465') ?>" class="form-control" placeholder="465">
                </div>

                <div class="form-group">
                    <label class="form-label">Güvenlik / Şifreleme Türü</label>
                    <select name="smtp_secure" class="form-control">
                        <option value="ssl" <?= (($settingsMap['smtp_secure'] ?? 'ssl') === 'ssl') ? 'selected' : '' ?>>SSL (Port 465 - Hostinger Tavsiye Edilen)</option>
                        <option value="tls" <?= (($settingsMap['smtp_secure'] ?? '') === 'tls') ? 'selected' : '' ?>>TLS / STARTTLS (Port 587)</option>
                        <option value="none" <?= (($settingsMap['smtp_secure'] ?? '') === 'none') ? 'selected' : '' ?>>Şifresiz (Port 25)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">SMTP E-Posta (Kullanıcı Adı)</label>
                    <input type="email" name="smtp_user" value="<?= htmlspecialchars($settingsMap['smtp_user'] ?? 'destek@bikgrup.com') ?>" class="form-control" placeholder="destek@bikgrup.com">
                </div>

                <div class="form-group">
                    <label class="form-label">SMTP E-Posta Şifresi</label>
                    <input type="password" name="smtp_pass" value="<?= htmlspecialchars($settingsMap['smtp_pass'] ?? '') ?>" class="form-control" placeholder="Hostinger webmail şifrenizi giriniz">
                    <span style="font-size: 11.5px; color: #64748b; margin-top: 4px; display: block;">Hostinger webmail kutunuzun şifresidir.</span>
                </div>

                <div class="form-group">
                    <label class="form-label">Teklif & Bildirimlerin İletileceği E-Posta</label>
                    <input type="email" name="quote_notification_email" value="<?= htmlspecialchars($settingsMap['quote_notification_email'] ?? 'destek@bikgrup.com') ?>" class="form-control" placeholder="destek@bikgrup.com">
                    <span style="font-size: 11.5px; color: #64748b; margin-top: 4px; display: block;">Müşterilerin siteden gönderdiği hızlı tekliflerin ve form mesajlarının düşeceği e-posta adresi.</span>
                </div>
            </div>
        </div>

        <div style="text-align: right;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 36px; font-size: 15px;">
                💾 Tüm Ayarları & Bilgileri Güncelle
            </button>
        </div>
    </form>

    <!-- SMTP TEST E-POSTASI GÖNDERME ALANI -->
    <div style="margin-top: 32px; background: #f0fdf4; border: 1.5px dashed #86efac; border-radius: 16px; padding: 20px 24px;">
        <h4 style="font-size: 15px; font-weight: 800; color: #166534; margin: 0 0 8px 0; display: flex; align-items: center; gap: 8px;">
            <span>🚀</span> Canlı E-Posta Gönderim Testi
        </h4>
        <p style="font-size: 13px; color: #15803d; margin: 0 0 16px 0;">
            Yukarıdaki SMTP ayarlarınızı kaydettikten sonra, sistemin e-posta gönderip gönderemediğini anında test edebilirsiniz.
        </p>
        <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/test-email" method="POST" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <?= Security::csrfField() ?>
            <input type="email" name="test_email" value="<?= htmlspecialchars($settingsMap['quote_notification_email'] ?? 'destek@bikgrup.com') ?>" required class="form-control" style="max-width: 340px; background: #ffffff;" placeholder="Test alıcı e-postası">
            <button type="submit" class="btn" style="background: #15803d; color: #ffffff; padding: 10px 24px; font-weight: 700; border-radius: 10px; cursor: pointer;">
                📨 Test E-Postası Gönder
            </button>
        </form>
    </div>
</div>
