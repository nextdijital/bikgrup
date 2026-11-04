<?php
/**
 * BİKGRUP - BİK GRUP Yönetim Paneli Footer Yönetimi
 */

declare(strict_types=1);
?>

<div class="panel-card">
    <div class="panel-card-header">
        <div>
            <h2 class="panel-card-title">📑 Footer (Altbilgi) Yönetimi</h2>
            <p style="color: var(--panel-text-muted); font-size: 14px; margin-top: 4px;">
                Web sitesinin en altındaki marka tanıtım metnini, sütun başlıklarını ve telif alanı yazılarını buradan yönetebilirsiniz.
            </p>
        </div>
    </div>

    <form action="<?= BASE_URL ?>/index.php?url=<?= PANEL_SLUG ?>/save-footer" method="POST">
        <?= Security::csrfField() ?>

        <div style="background: #f8fafc; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px; margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <span>🌿</span> 1. Marka & Tanıtım Bloğu (Sol Sütun)
            </h3>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label">Marka Başlığı</label>
                <input type="text" name="title_tr" value="<?= htmlspecialchars($footer['title_tr'] ?? 'BİK GRUP') ?>" class="form-control" placeholder="BİK GRUP">
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label">Slogan / Üst Vurgu</label>
                <input type="text" name="subtitle_tr" value="<?= htmlspecialchars($footer['subtitle_tr'] ?? 'Topraktan Geleceğe Güvenle...') ?>" class="form-control" placeholder="Topraktan Geleceğe Güvenle...">
            </div>

            <div class="form-group">
                <label class="form-label">Marka Tanıtım Paragrafı (Hakkımızda Kısa Açıklaması)</label>
                <textarea name="body_tr" rows="3" class="form-control" placeholder="Firma tanıtım metnini buraya yazın..."><?= htmlspecialchars($footer['body_tr'] ?? '') ?></textarea>
            </div>
        </div>

        <div style="background: #f8fafc; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px; margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <span>📑</span> 2. Menü Sütun Başlıkları
            </h3>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                <div class="form-group">
                    <label class="form-label">1. Sütun Başlığı (Hızlı Menü)</label>
                    <input type="text" name="quick_links_title" value="<?= htmlspecialchars($footerExtra['quick_links_title'] ?? 'Hızlı Bağlantılar') ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">2. Sütun Başlığı (Ürünler)</label>
                    <input type="text" name="products_title" value="<?= htmlspecialchars($footerExtra['products_title'] ?? 'Ürünlerimiz') ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">3. Sütun Başlığı (Politikalar)</label>
                    <input type="text" name="policies_title" value="<?= htmlspecialchars($footerExtra['policies_title'] ?? 'Politikalar') ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">4. Sütun Başlığı (İletişim & Lokasyon)</label>
                    <input type="text" name="contact_title" value="<?= htmlspecialchars($footerExtra['contact_title'] ?? 'İletişim & Lokasyon') ?>" class="form-control">
                </div>
            </div>
        </div>

        <div style="background: #f8fafc; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px; margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <span>©️</span> 3. En Alt Çubuk (Telif Metni)
            </h3>

            <div class="form-group">
                <label class="form-label">Telif Hakkı Metni</label>
                <input type="text" name="copyright_text" value="<?= htmlspecialchars($footerExtra['copyright_text'] ?? 'Tüm hakları saklıdır.') ?>" class="form-control" placeholder="Tüm hakları saklıdır.">
            </div>
        </div>

        <div style="text-align: right;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 36px; font-size: 15px;">
                💾 Footer Değişikliklerini Kaydet
            </button>
        </div>
    </form>
</div>

