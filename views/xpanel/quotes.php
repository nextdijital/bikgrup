<?php
/**
 * BİKGRUP - BİK GRUP Yönetim Paneli Hızlı Teklif Talepleri & Gelen Mesajlar
 */

declare(strict_types=1);

$totalQuotes = count($quotes);
$pendingCount = 0;
$repliedCount = 0;
$completedCount = 0;

foreach ($quotes as $q) {
    if ($q['status'] === 'pending') $pendingCount++;
    elseif ($q['status'] === 'replied') $repliedCount++;
    elseif ($q['status'] === 'completed') $completedCount++;
}
?>

<div class="panel-section-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-top: 4px; margin-bottom: 26px; padding-bottom: 6px;">
    <div>
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 24px; font-weight: 800; color: #0f172a; margin: 0 0 6px 0;">
            ⚡ Hızlı Teklifler & Gelen Talepler
        </h2>
        <p style="font-size: 14px; color: #64748b; margin: 0;">
            Web sitesi teklif modalından ve iletişim formundan gelen müşteri mesajları, talep detayları ve bildirim entegrasyonu.
        </p>
    </div>
</div>

<!-- Özet Sayaç Kartları -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 26px;">
    <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 22px; box-shadow: var(--shadow-sm);">
        <div style="font-size: 12.5px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Toplam Talep / Mesaj</div>
        <div style="font-size: 32px; font-weight: 900; color: #0f172a; margin-top: 6px;"><?= $totalQuotes ?></div>
    </div>
    <div style="background: #ffffff; border-radius: 16px; border: 1px solid #fed7aa; padding: 22px; box-shadow: var(--shadow-sm); border-left: 5px solid #f97316;">
        <div style="font-size: 12.5px; font-weight: 700; color: #ea580c; text-transform: uppercase; letter-spacing: 0.5px;">Bekleyen Talepler</div>
        <div style="font-size: 32px; font-weight: 900; color: #ea580c; margin-top: 6px;"><?= $pendingCount ?></div>
    </div>
    <div style="background: #ffffff; border-radius: 16px; border: 1px solid #bbf7d0; padding: 22px; box-shadow: var(--shadow-sm); border-left: 5px solid #16a34a;">
        <div style="font-size: 12.5px; font-weight: 700; color: #16a34a; text-transform: uppercase; letter-spacing: 0.5px;">İşlem Yapılan / İncelenen</div>
        <div style="font-size: 32px; font-weight: 900; color: #16a34a; margin-top: 6px;"><?= $repliedCount + $completedCount ?></div>
    </div>
</div>

<!-- Teklif Bildirim E-Posta Entegrasyon Kartı -->
<?php
$quoteEmail = Settings::get('quote_notification_email');
if (empty($quoteEmail)) {
    $quoteEmail = Settings::get('site_email', 'destek@bikgrup.com');
}
$smtpHost   = Settings::get('smtp_host', '');
$smtpPort   = Settings::get('smtp_port', '587');
$smtpUser   = Settings::get('smtp_user', '');
$smtpSecure = Settings::get('smtp_secure', 'tls');
$smtpPass   = Settings::get('smtp_pass', '');
?>
<div style="background: #ffffff; border-radius: 20px; border: 1.5px solid #cbd5e1; padding: 26px 30px; margin-bottom: 28px; box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; margin-bottom: 20px;">
        <div style="display: flex; align-items: center; gap: 14px;">
            <div style="width: 46px; height: 46px; border-radius: 14px; background: #eff6ff; border: 1.5px solid #bfdbfe; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                📧
            </div>
            <div>
                <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">
                    Teklif Bildirim E-Posta Entegrasyonu
                </h3>
                <p style="margin: 0; font-size: 13.5px; color: #64748b;">
                    Siteden veya modal pencereden gönderilen tüm teklif talepleri anında buraya girdiğiniz e-posta adresine düşer.
                </p>
            </div>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <button type="button" onclick="document.getElementById('testMailForm').submit();" class="btn" style="background: #f8fafc; border: 1.5px solid #cbd5e1; color: #334155; font-weight: 700; font-size: 13.5px; padding: 9px 18px; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                <span>🔔 Test Maili Gönder</span>
            </button>
        </div>
    </div>

    <?php if (empty($smtpPass)): ?>
        <div style="background: #fff7ed; border: 1.5px solid #fed7aa; border-radius: 12px; padding: 14px 18px; margin-bottom: 18px; display: flex; align-items: flex-start; gap: 12px;">
            <span style="font-size: 22px; line-height: 1;">⚠️</span>
            <div>
                <strong style="color: #c2410c; font-size: 14px; display: block; margin-bottom: 2px;">
                    SMTP E-Posta Şifresi Henüz Girilmemiş!
                </strong>
                <span style="color: #9a3412; font-size: 13px; line-height: 1.5;">
                    Teklifler panele sorunsuz kaydediliyor ancak e-posta adresinize iletilebilmesi için aşağıdaki <strong>SMTP Şifresi</strong> kutusuna e-posta kutunuzun (webmail) şifresini yazıp <strong>"Kaydet"</strong>e basınız.
                </span>
            </div>
        </div>
    <?php else: ?>
        <div style="background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 12px; padding: 10px 16px; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; color: #166534; font-size: 13px; font-weight: 600;">
            <span>✅</span> <span>SMTP Sunucu şifresi tanımlıdır. Yeni gelen teklifler doğrudan e-postanıza iletilmektedir.</span>
        </div>
    <?php endif; ?>

    <form id="quoteMailSettingsForm" action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/quote-mail-settings" method="POST">
        <?= Security::csrfField() ?>

        <div style="display: grid; grid-template-columns: 1fr auto; gap: 16px; align-items: flex-end; margin-bottom: 16px;">
            <div>
                <label class="form-label" style="font-weight: 700; color: #0f172a; font-size: 14px; margin-bottom: 6px; display: block;">
                    Tekliflerin İletileceği E-Posta Adresi <span style="color: #ef4444;">*</span>
                </label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 16px; color: #94a3b8;">✉️</span>
                    <input type="email" name="quote_notification_email" value="<?= htmlspecialchars($quoteEmail) ?>" required placeholder="ornek: info@bikgrup.com" class="form-control" style="font-family: inherit; font-size: 15px; font-weight: 600; color: #0f172a; padding-left: 44px; height: 48px; border-radius: 12px; border: 1.5px solid #cbd5e1; width: 100%;">
                </div>
                <div style="font-size: 12.5px; color: #64748b; margin-top: 6px;">
                    ℹ️ Birden fazla e-posta için virgül (,) ile ayırarak yazabilirsiniz (örn: info@bikgrup.com, satis@bikgrup.com).
                </div>
            </div>
            <div>
                <button type="submit" class="btn btn-primary" style="background: #15803d; border-color: #15803d; height: 48px; padding: 0 26px; font-size: 14.5px; font-weight: 700; border-radius: 12px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(21, 128, 61, 0.2);">
                    <span>💾 Kaydet</span>
                </button>
            </div>
        </div>

        <!-- İsteğe Bağlı SMTP Ayarları Açılır Kutusu -->
        <details <?= empty($smtpPass) ? 'open' : '' ?> style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 18px; margin-top: 14px;">
            <summary style="font-size: 13.5px; font-weight: 700; color: #475569; cursor: pointer; user-select: none; display: flex; align-items: center; justify-content: space-between;">
                <span>⚙️ Gelişmiş SMTP Sunucu Ayarları (İsteğe Bağlı - Kurumsal Mail / Gmail / Yandex)</span>
                <span style="font-size: 12px; color: #94a3b8;">Ayarları Aç / Kapat ▾</span>
            </summary>
            <div style="padding-top: 18px; border-top: 1px solid #e2e8f0; margin-top: 12px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                <div>
                    <label class="form-label" style="font-size: 12.5px; font-weight: 600;">SMTP Sunucu Adresi</label>
                    <input type="text" name="smtp_host" value="<?= htmlspecialchars($smtpHost) ?>" placeholder="Örn: mail.bikgrup.com" class="form-control" style="font-size: 13.5px;">
                </div>
                <div>
                    <label class="form-label" style="font-size: 12.5px; font-weight: 600;">SMTP Port</label>
                    <input type="text" name="smtp_port" value="<?= htmlspecialchars($smtpPort) ?>" placeholder="587 veya 465" class="form-control" style="font-size: 13.5px;">
                </div>
                <div>
                    <label class="form-label" style="font-size: 12.5px; font-weight: 600;">Güvenlik Protokolü</label>
                    <select name="smtp_secure" class="form-control" style="font-size: 13.5px;">
                        <option value="tls" <?= $smtpSecure === 'tls' ? 'selected' : '' ?>>TLS (Port 587 - Önerilen)</option>
                        <option value="ssl" <?= $smtpSecure === 'ssl' ? 'selected' : '' ?>>SSL (Port 465)</option>
                        <option value="none" <?= $smtpSecure === 'none' ? 'selected' : '' ?>>Şifresiz / Yok (Port 25)</option>
                    </select>
                </div>
                <div>
                    <label class="form-label" style="font-size: 12.5px; font-weight: 600;">SMTP E-Posta / Kullanıcı</label>
                    <input type="text" name="smtp_user" value="<?= htmlspecialchars($smtpUser) ?>" placeholder="teklif@bikgrup.com" class="form-control" style="font-size: 13.5px;">
                </div>
                <div>
                    <label class="form-label" style="font-size: 12.5px; font-weight: 600;">SMTP Şifresi</label>
                    <input type="password" name="smtp_pass" placeholder="••••••••" class="form-control" style="font-size: 13.5px;">
                </div>
            </div>
            <div style="font-size: 12px; color: #64748b; margin-top: 12px;">
                💡 <em>Not: SMTP bilgileri kaydedildiğinde sistem tüm e-postaları doğrudan bu güvenli sunucu üzerinden iletir.</em>
            </div>
        </details>
    </form>

    <!-- Test Maili İçin Görünmez Form -->
    <form id="testMailForm" action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/quote-mail-test" method="POST" style="display: none;">
        <?= Security::csrfField() ?>
    </form>
</div>

<!-- Teklif & Mesaj Listesi Tablosu (Sadece Gelen İçerik ve Silme Butonu) -->
<div style="background: #ffffff; border-radius: 20px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: var(--shadow-sm);">
    <div style="padding: 20px 24px; border-bottom: 1.5px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; background: #fafafa;">
        <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
            <span>📨</span> Gelen Müşteri Talepleri ve Mesaj İçerikleri (<?= $totalQuotes ?>)
        </h3>
        <span style="font-size: 12.5px; color: #64748b;">Müşterinin form üzerinden gönderdiği ve e-postanıza düşen orijinal içeriklerdir.</span>
    </div>

    <?php if (empty($quotes)): ?>
        <div style="text-align: center; padding: 60px 20px; color: #64748b;">
            <div style="font-size: 44px; margin-bottom: 12px;">⚡</div>
            <div style="font-size: 18px; font-weight: 700; color: #0f172a;">Henüz gelen teklif veya mesaj bulunmuyor</div>
            <p style="font-size: 14px; margin-top: 6px;">Web sitenizden teklif formu doldurulduğunda talepler anında bu alana yansıyacak ve bildirim e-postanıza iletilecektir.</p>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1.5px solid #e2e8f0; color: #475569; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                        <th style="padding: 16px 20px; font-weight: 700; width: 140px;"># & Tarih</th>
                        <th style="padding: 16px 20px; font-weight: 700; width: 220px;">Talep Eden Müşteri</th>
                        <th style="padding: 16px 20px; font-weight: 700; width: 220px;">İstenen Ürün & Miktar</th>
                        <th style="padding: 16px 20px; font-weight: 700;">Gelen Mesaj / Talep İçeriği (Maile Gelenler)</th>
                        <th style="padding: 16px 20px; font-weight: 700; text-align: right; width: 100px;">İşlem</th>
                    </tr>
                </thead>
                <tbody style="divide-y: 1px solid #f1f5f9;">
                    <?php foreach ($quotes as $q): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;" onmouseover="this.style.background='#fbfcfd'" onmouseout="this.style.background='transparent'">
                            <!-- # & Tarih -->
                            <td style="padding: 18px 20px; vertical-align: top;">
                                <div style="font-weight: 800; color: #ea580c; font-size: 14px;">#<?= $q['id'] ?></div>
                                <div style="font-size: 12.5px; color: #64748b; margin-top: 4px; white-space: nowrap;">
                                    <?= date('d.m.Y', strtotime($q['created_at'])) ?><br>
                                    <span style="color: #94a3b8; font-size: 11.5px;"><?= date('H:i', strtotime($q['created_at'])) ?></span>
                                </div>
                            </td>

                            <!-- Müşteri Bilgileri -->
                            <td style="padding: 18px 20px; vertical-align: top;">
                                <div style="font-weight: 800; color: #0f172a; font-size: 15px;">
                                    <?= htmlspecialchars($q['full_name']) ?>
                                </div>
                                <div style="font-size: 13px; color: #64748b; margin-top: 5px; display: flex; flex-direction: column; gap: 4px;">
                                    <?php if (!empty($q['phone'])): ?>
                                        <a href="tel:<?= htmlspecialchars($q['phone']) ?>" style="color: #15803d; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                                            <span>📞</span> <?= htmlspecialchars($q['phone']) ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($q['email'])): ?>
                                        <a href="mailto:<?= htmlspecialchars($q['email']) ?>" style="color: #2563eb; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                                            <span>✉️</span> <?= htmlspecialchars($q['email']) ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Talep Edilen Ürün & Miktar -->
                            <td style="padding: 18px 20px; vertical-align: top;">
                                <div style="font-weight: 800; color: #15803d; font-size: 14.5px;">
                                    <?= htmlspecialchars($q['product_category']) ?>
                                </div>
                                <div style="font-size: 13px; color: #0f172a; margin-top: 4px; font-weight: 700; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 3px 8px; border-radius: 6px; display: inline-block;">
                                    📦 <?= htmlspecialchars($q['quantity']) ?>
                                </div>
                            </td>

                            <!-- Mesajın İçeriği (Maile Düşen Metin) -->
                            <td style="padding: 18px 20px; vertical-align: top;">
                                <?php if (!empty($q['notes'])): ?>
                                    <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 12px 16px; font-size: 13.5px; color: #1e293b; line-height: 1.6; word-break: break-word;">
                                        <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">💬 Müşteri Mesajı:</div>
                                        <?= nl2br(htmlspecialchars($q['notes'])) ?>
                                    </div>
                                <?php else: ?>
                                    <div style="color: #94a3b8; font-size: 13px; font-style: italic; background: #f8fafc; padding: 10px 14px; border-radius: 8px;">
                                        Ek bir mesaj veya not yazılmadı. (Yalnızca ürün ve miktar talebi)
                                    </div>
                                <?php endif; ?>
                            </td>

                            <!-- İşlem: Sadece Silme Butonu -->
                            <td style="padding: 18px 20px; vertical-align: top; text-align: right;">
                                <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/quote-delete?id=<?= $q['id'] ?>" 
                                   class="btn" 
                                   style="background:#fee2e2; color:#b91c1c; font-weight:700; font-size:12.5px; padding:8px 14px; border-radius:10px; display:inline-flex; align-items:center; gap:6px; border:1px solid #fecaca; text-decoration:none; transition: all 0.15s;" 
                                   onclick="return confirm('#<?= $q['id'] ?> numaralı bu talebi ve mesaj kaydını silmek istediğinizden emin misiniz?');">
                                    🗑️ Sil
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
