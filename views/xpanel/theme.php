<?php
/**
 * BİKGRUP - BİK GRUP Yönetim Paneli Canlı Tema ve Renk Yöneticisi
 */

declare(strict_types=1);

$themeMap = [];
foreach ($themeSettings as $s) {
    $themeMap[$s['setting_key']] = $s;
}
?>

<div class="panel-card">
    <div class="panel-card-header" style="flex-wrap: wrap; gap: 16px;">
        <div>
            <h2 class="panel-card-title">🎨 Canlı Tema & Renk Özelleştirici</h2>
            <p style="color: var(--panel-text-muted); font-size: 14px; margin-top: 4px;">
                Sitenin ana renklerini, buton tonlarını ve yazı renklerini buradan özelleştirebilirsiniz. Kaydettiğiniz anda tüm sitede aktif olur.
            </p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <button type="button" onclick="fillDefaultTheme()" class="btn btn-outline" style="padding: 12px 20px; font-weight: 600; background: #ffffff;">
                ↺ Varsayılanları Yükle
            </button>
            <button type="submit" form="theme-reset-form" class="btn btn-outline" style="padding: 12px 20px; color: #dc2626; border-color: #fca5a5; background: #fff5f5; font-weight: 600;" onclick="return confirm('Tüm tema ve renk ayarlarını sitenin orijinal varsayılan renklerine sıfırlamak istediğinize emin misiniz?');">
                ↺ Varsayılan Fabrika Renklerine Sıfırla
            </button>
            <button type="submit" form="theme-form" class="btn btn-primary" style="padding: 12px 28px;">
                💾 Değişiklikleri Kaydet
            </button>
        </div>
    </div>

    <!-- Canlı Önizleme Simülatörü -->
    <div id="preview-simulation-container" style="background: #ffffff; border-radius: 20px; padding: 24px; border: 2px dashed #cbd5e1; margin-bottom: 30px; transition: all 0.3s ease;">
        <h4 style="font-size: 14px; font-weight: 700; color: var(--panel-text-muted); text-transform: uppercase; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
            <span>Canlı Önizleme Simülasyonu</span>
            <span style="font-size: 11px; text-transform: none; font-weight: 500; color: #059669; background: #dcfce7; padding: 2px 8px; border-radius: 999px;">Renkler anında yansır</span>
        </h4>
        <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap;">
            <button type="button" id="preview-btn-primary" class="btn btn-primary">Örnek Birincil Buton</button>
            <button type="button" id="preview-btn-secondary" class="btn btn-secondary">Örnek İkincil Buton</button>
            <span id="preview-accent" style="display: inline-flex; align-items: center; font-size: 12px; font-weight: 800; color: #ffffff; background: var(--theme-accent, #059669); padding: 6px 14px; border-radius: 9999px; letter-spacing: 0.5px;">
                Örnek Vurgu Etiketi
            </span>
            <span id="preview-heading" style="font-family:'Outfit', sans-serif; font-size: 22px; font-weight: 800; color: var(--theme-primary);">
                Başlık Vurgu Rengi
            </span>
            <span id="preview-text" style="font-size: 14px; color: var(--theme-text-muted);">
                Örnek alt açıklama metni...
            </span>
        </div>
    </div>

    <!-- Fabrika Ayarlarına Sıfırlama Formu -->
    <form id="theme-reset-form" action="<?= BASE_URL ?>/index.php?url=<?= PANEL_SLUG ?>/reset-theme" method="POST" style="display: none;">
        <?= Security::csrfField() ?>
    </form>

    <form id="theme-form" action="<?= BASE_URL ?>/index.php?url=<?= PANEL_SLUG ?>/save-theme" method="POST">
        <?= Security::csrfField() ?>

        <div class="theme-color-grid">
            
            <?php foreach ($themeSettings as $setting): ?>
                <?php if ($setting['input_type'] === 'color'): ?>
                    <div class="theme-color-item">
                        <input type="color" 
                               name="<?= htmlspecialchars($setting['setting_key']) ?>" 
                               id="picker_<?= htmlspecialchars($setting['setting_key']) ?>"
                               value="<?= htmlspecialchars($setting['setting_value'] ?: '#15803d') ?>" 
                               class="theme-color-input">
                        <div style="flex-grow: 1;">
                            <label class="form-label" style="margin-bottom: 2px;">
                                <?= htmlspecialchars($setting['label_tr']) ?>
                            </label>
                            <input type="text" 
                                   data-color-sync="<?= htmlspecialchars($setting['setting_key']) ?>" 
                                   value="<?= htmlspecialchars($setting['setting_value']) ?>" 
                                   class="form-control" 
                                   style="font-family: monospace; font-size: 13px; padding: 6px 10px;">
                        </div>
                    </div>
                <?php else: ?>
                    <div class="theme-color-item" style="grid-column: span 1;">
                        <div style="width: 100%;">
                            <label class="form-label"><?= htmlspecialchars($setting['label_tr']) ?></label>
                            <input type="text" 
                                   name="<?= htmlspecialchars($setting['setting_key']) ?>" 
                                   id="input_<?= htmlspecialchars($setting['setting_key']) ?>"
                                   value="<?= htmlspecialchars($setting['setting_value']) ?>" 
                                   class="form-control">
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

        </div>

        <div style="margin-top: 35px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; border-top: 1px solid #e2e8f0; padding-top: 24px;">
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <button type="button" onclick="fillDefaultTheme()" class="btn btn-outline" style="padding: 12px 22px; font-weight: 600; background: #ffffff;">
                    ↺ Varsayılan Renkleri Forma Doldur
                </button>
                <button type="submit" form="theme-reset-form" class="btn btn-outline" style="padding: 12px 22px; color: #dc2626; border-color: #fca5a5; background: #fff5f5; font-weight: 600;" onclick="return confirm('Tüm tema ve renk ayarlarını sitenin orijinal varsayılan renklerine sıfırlamak istediğinize emin misiniz?');">
                    ↺ Fabrika Ayarlarına Doğrudan Sıfırla
                </button>
            </div>
            <button type="submit" class="btn btn-primary" style="padding: 14px 38px; font-size: 16px;">
                💾 Tüm Tema Renklerini Kaydet
            </button>
        </div>
    </form>
</div>

<script>
const defaultThemeMap = {
    'theme_primary': '#15803d',
    'theme_primary_hover': '#166534',
    'theme_secondary': '#ea580c',
    'theme_secondary_hover': '#c2410c',
    'theme_accent': '#059669',
    'theme_bg': '#fcfdfd',
    'theme_card_bg': '#ffffff',
    'theme_text': '#0f172a',
    'theme_text_muted': '#64748b',
    'theme_navbar_bg': 'rgba(255, 255, 255, 0.94)',
    'theme_border_radius': '20px'
};

function fillDefaultTheme() {
    for (const [key, val] of Object.entries(defaultThemeMap)) {
        // Color input
        const colorInput = document.querySelector(`input[type="color"][name="${key}"]`);
        if (colorInput) {
            colorInput.value = val;
            colorInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
        // Text sync input
        const textSyncInput = document.querySelector(`input[data-color-sync="${key}"]`);
        if (textSyncInput) {
            textSyncInput.value = val;
        }
        // Direct text input
        const textInput = document.querySelector(`input[type="text"][name="${key}"]`);
        if (textInput) {
            textInput.value = val;
            textInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }
}
</script>

