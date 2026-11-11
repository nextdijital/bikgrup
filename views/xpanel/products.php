<?php
/**
 * BİKGRUP - BİK GRUP Yönetim Paneli Ürün Yönetimi
 * In-Page Ürün Düzenleme & Listeleme
 */

declare(strict_types=1);

$isEditing = ($action === 'edit' || $action === 'new');
$isNew = ($action === 'new');
$prod = $editingProduct ?? [];

$prodId = $prod['id'] ?? null;
$prodName = $prod['name_tr'] ?? '';
$prodSlug = $prod['slug'] ?? '';
$prodCategoryId = (int)($prod['category_id'] ?? ($categories[0]['id'] ?? 1));
$prodSortOrder = (int)($prod['sort_order'] ?? 0);
$prodHarvestTr = $prod['harvest_season_tr'] ?? 'Ekim - Nisan';
$prodOriginTr = $prod['origin_tr'] ?? 'Akdeniz & Ege / Türkiye';
$prodPackagingTr = $prod['packaging_tr'] ?? 'Teleskopik Kutu & Plastik Kasa';
$prodTemp = $prod['temperature'] ?? '+4°C / +8°C';
$prodShortDesc = $prod['short_desc_tr'] ?? '';
$prodContent = $prod['content_tr'] ?? '';
$prodImage = $prod['image'] ?? 'product_riored.png';
$prodIsFeatured = isset($prod['is_featured']) ? ((int)$prod['is_featured'] === 1) : true;
$prodIsActive = isset($prod['is_active']) ? ((int)$prod['is_active'] === 1) : true;

// Extra JSON Çözümleme
$prodExtra = [];
if (!empty($prod['extra_json'])) {
    if (is_array($prod['extra_json'])) {
        $prodExtra = $prod['extra_json'];
    } else {
        $decoded = json_decode((string)$prod['extra_json'], true);
        if (is_array($decoded)) {
            $prodExtra = $decoded;
        }
    }
}

// Genel Karakteristikler (Dinamik Başlıklar & Değerler)
$charPanelTitle = $prodExtra['char_panel_title'] ?? '';
$charPanelDesc = $prodExtra['char_panel_desc'] ?? '';

$charLblEt = $prodExtra['char_lbl_et_rengi'] ?? '';
$charEt = $prodExtra['char_et_rengi'] ?? '';

$charLblCekirdek = $prodExtra['char_lbl_cekirdek'] ?? '';
$charCekirdek = $prodExtra['char_cekirdek'] ?? '';

$charLblKabuk = $prodExtra['char_lbl_kabuk_rengi'] ?? '';
$charKabuk = $prodExtra['char_kabuk_rengi'] ?? '';

$charLblDoku = $prodExtra['char_lbl_dokusu'] ?? '';
$charDoku = $prodExtra['char_dokusu'] ?? '';

$charLblSekil = $prodExtra['char_lbl_sekil'] ?? '';
$charSekil = $prodExtra['char_sekil'] ?? '';

$charLblTat = $prodExtra['char_lbl_tadi'] ?? '';
$charTat = $prodExtra['char_tadi'] ?? '';

// Galeri Entegrasyonu (Kullanıcı Talebi: Galeri Kategorisini Ürüne Bağlama)
$galleryCategory = $prodExtra['gallery_category'] ?? 'all';
$showProductGallery = isset($prodExtra['show_product_gallery']) ? ((int)$prodExtra['show_product_gallery'] === 1) : true;

// Vitrin & Rozetler
$nutriCal = $prodExtra['nutrition_calorie'] ?? '47 kcal / 100g';
$nutriBrix = $prodExtra['nutrition_brix'] ?? '9.8° Brix';
$nutriAcid = $prodExtra['nutrition_acidity'] ?? '0.9% Sitrik Asit';
$badge1 = $prodExtra['badge1'] ?? 'C Vitamini Deposu';
$badge2 = $prodExtra['badge2'] ?? 'Yüksek Doğal Su & Lif';
$badge3 = $prodExtra['badge3'] ?? 'Zengin Antioksidan';

// Cinsler & Alt Çeşitler
$varieties = [];
if (!empty($prodExtra['varieties']) && is_array($prodExtra['varieties'])) {
    $varieties = $prodExtra['varieties'];
}

// Varsayılan Kutu Seçenekleri
$boxDefaults = [
    1 => ['title' => 'Karton Kutu', 'weight' => '7 Kg', 'dim' => '600 x 400 x 120', 'img' => 'packaging_carton_1.png'],
    2 => ['title' => 'Karton Kutu', 'weight' => '15 Kg', 'dim' => '600 x 400 x 160', 'img' => 'packaging_carton_2.png'],
    3 => ['title' => 'Karton Kutu Derin', 'weight' => '15 Kg', 'dim' => '600 x 400 x 180', 'img' => 'packaging_carton_3.png'],
    4 => ['title' => 'Karton Kutu – Teleskopik', 'weight' => '15 Kg', 'dim' => '600 x 400 x 180', 'img' => 'packaging_telescopic.png'],
    5 => ['title' => 'File Ambalaj', 'weight' => '1.5 Kg - 2 Kg', 'dim' => 'Perakende Market Rafı', 'img' => 'packaging_mesh.png'],
];

// Kutu & Ambalaj Listesi ve Seçenek Durumu (Kullanıcı Talebi: Seçenekli Kutucuk Ayarı)
$showPackaging = isset($prodExtra['show_packaging']) ? ((int)$prodExtra['show_packaging'] === 1) : (!empty($prodExtra['box1_title']) || $isNew);

$packagingItems = [];
if (!empty($prodExtra['packaging_items']) && is_array($prodExtra['packaging_items'])) {
    $packagingItems = $prodExtra['packaging_items'];
} else {
    // Legacy box1..5 kontrolü
    for ($b = 1; $b <= 5; $b++) {
        if (!empty($prodExtra['box' . $b . '_title']) || !empty($prodExtra['box' . $b . '_image'])) {
            $packagingItems[] = [
                'title'  => $prodExtra['box' . $b . '_title'] ?? '',
                'weight' => $prodExtra['box' . $b . '_weight'] ?? '',
                'dim'    => $prodExtra['box' . $b . '_dim'] ?? '',
                'image'  => $prodExtra['box' . $b . '_image'] ?? ('packaging_carton_' . min($b, 3) . '.png')
            ];
        }
    }
}

// Eğer hiçbir kutu yoksa ve yeni ürün ise varsayılan kutuları getir
if (empty($packagingItems) && $isNew) {
    foreach ($boxDefaults as $bDef) {
        $packagingItems[] = [
            'title'  => $bDef['title'],
            'weight' => $bDef['weight'],
            'dim'    => $bDef['dim'],
            'image'  => $bDef['img']
        ];
    }
}
?>

<?php if (!$isEditing): ?>
<!-- ============================================================== -->
<!-- 1. ÜRÜN LİSTE GÖRÜNÜMÜ                                         -->
<!-- ============================================================== -->

<?php
$prodPageHero = PageSection::get('products_hero') ?? [];
$prodPageHeroExtra = $prodPageHero['extra'] ?? (!empty($prodPageHero['extra_json']) ? json_decode($prodPageHero['extra_json'], true) : []);
?>

<!-- Ürünler Kataloğu Sayfası Hero Afiş & Başlık Alanı -->
<div class="panel-card" style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;" onclick="const b = document.getElementById('prodHeroCardBody'); b.style.display = (b.style.display === 'none' ? 'block' : 'none');">
        <div>
            <h2 class="panel-card-title" style="display: flex; align-items: center; gap: 8px;">
                <span>🌄</span> Ürünler Sayfası Hero Giriş Afişi & Başlık Alanı
            </h2>
            <p style="color: var(--panel-text-muted); font-size: 13.5px; margin-top: 4px;">
                Sitedeki /urunler kataloğu sayfasının en üstündeki afiş görselini, başlığını ve buton metinlerini buradan yönetin. (Açıp/kapatmak için tıklayın)
            </p>
        </div>
        <button type="button" class="btn btn-secondary" style="padding: 6px 14px; font-size: 12.5px; font-weight: 700; border-radius: 8px;">
            Afiş Alanını Düzenle ⚙️
        </button>
    </div>

    <div id="prodHeroCardBody" style="display: none; margin-top: 20px; padding-top: 20px; border-top: 1.5px solid #f1f5f9;">
        <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-page" method="POST" enctype="multipart/form-data">
            <?= Security::csrfField() ?>
            <input type="hidden" name="section_key" value="products_hero">
            <input type="hidden" name="page_key" value="products">
            <input type="hidden" name="redirect_to" value="<?= PANEL_SLUG ?>/products">

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 18px;">
                <label class="form-label" style="font-weight: 700; color: #0f172a;">Ürünler Sayfası Arka Plan Görseli</label>
                <div style="display: flex; align-items: center; gap: 20px; margin-top: 10px;">
                    <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($prodPageHeroExtra['bg_image'] ?? 'nature_hero_bg.jpg') ?>" 
                         alt="Ürünler Arka Plan" style="width: 160px; height: 90px; object-fit: cover; border-radius: 8px; border: 2px solid #cbd5e1;">
                    <div>
                        <span style="font-size: 13px; color: #475569; display: block; margin-bottom: 6px;">Mevcut Görsel: <strong><?= htmlspecialchars($prodPageHeroExtra['bg_image'] ?? 'nature_hero_bg.jpg') ?></strong></span>
                        <input type="file" name="bg_image" accept="image/*" class="form-control" style="font-size: 13px;">
                        <input type="hidden" name="extra[bg_image]" value="<?= htmlspecialchars($prodPageHeroExtra['bg_image'] ?? 'nature_hero_bg.jpg') ?>">
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 16px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700;">Büyük Başlık</label>
                    <input type="text" name="title_tr" value="<?= htmlspecialchars($prodPageHero['title_tr'] ?? 'Tarladan Dünyaya Taze Lezzetler') ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700;">Alt Slogan / Açıklama</label>
                    <input type="text" name="subtitle_tr" value="<?= htmlspecialchars($prodPageHero['subtitle_tr'] ?? 'Akdeniz\'in en taze narenciye, meyve ve sebzelerini özenle seçiyor, uluslararası kalite ve soğuk zincir güvencesiyle dünya sofralarına ulaştırıyoruz.') ?>" class="form-control">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 16px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700;">Teklif Al Buton Metni</label>
                    <input type="text" name="extra[quote_btn_text]" value="<?= htmlspecialchars($prodPageHeroExtra['quote_btn_text'] ?? 'Teklif Al') ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Tüm Ürünler Buton Metni</label>
                    <input type="text" name="extra[all_products_text]" value="<?= htmlspecialchars($prodPageHeroExtra['all_products_text'] ?? 'Tüm Ürünler') ?>" class="form-control">
                </div>
            </div>

            <div style="text-align: right;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 26px; font-weight: 700;">💾 Afiş Alanını Güncelle</button>
            </div>
        </form>
    </div>
</div>

<div class="panel-card">
    <div class="panel-card-header">
        <div>
            <h2 class="panel-card-title">🍊 Ürün & Hasat Yönetimi</h2>
            <p style="color: var(--panel-text-muted); font-size: 14px; margin-top: 4px;">
                Sitede sergilenen tüm taze meyve, narenciye, sebze ve hububat ürünlerini ve kategorilerini yönetin.
            </p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <button onclick="openNewCategoryModal()" class="btn btn-secondary" style="padding: 10px 18px; display: inline-flex; align-items: center; gap: 6px; background: #f8fafc; border: 1.5px solid #cbd5e1; color: #1e293b; font-weight: 700; border-radius: 10px; cursor: pointer;">
                <span>📁</span> <span>+ Yeni Kategori Ekle</span>
            </button>
            <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/products?action=new" class="btn btn-primary" style="padding: 10px 22px; display: inline-flex; align-items: center; gap: 6px; background: #16a34a; border-color: #16a34a; font-weight: 700; border-radius: 10px; text-decoration: none;">
                <span>➕</span> <span>+ Yeni Ürün Ekle</span>
            </a>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table class="panel-table">
            <thead>
                <tr>
                    <th style="width: 70px;">Görsel</th>
                    <th>Ürün Adı (TR)</th>
                    <th>Kategori</th>
                    <th>Hasat Sezonu</th>
                    <th>Öne Çıkarılan</th>
                    <th>Durum</th>
                    <th style="text-align: right;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $pItem): ?>
                    <tr>
                        <td>
                            <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($pItem['image'] ?: 'citrus_showcase.jpg') ?>" 
                                 alt="<?= htmlspecialchars($pItem['name_tr']) ?>"
                                 style="width: 52px; height: 52px; border-radius: 12px; object-fit: cover; border: 1.5px solid #e2e8f0; background: #ffffff; box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
                        </td>
                        <td>
                            <div style="font-weight: 800; font-size: 15px; color: #0f172a;">
                                <?= htmlspecialchars($pItem['name_tr']) ?>
                            </div>
                            <div style="font-size: 12px; color: #64748b; font-family: monospace; margin-top: 2px;">
                                /urunler/<?= htmlspecialchars($pItem['slug']) ?>
                            </div>
                        </td>
                        <td>
                            <span style="display: inline-block; font-size: 12.5px; font-weight: 700; padding: 4px 10px; border-radius: 8px; background: #f8fafc; border: 1px solid #e2e8f0; color: <?= htmlspecialchars($pItem['badge_color'] ?? '#15803d') ?>;">
                                📁 <?= htmlspecialchars($pItem['category_name_tr'] ?? '') ?>
                            </span>
                        </td>
                        <td style="font-size: 13.5px; font-weight: 600; color: #334155;">
                            🗓️ <?= htmlspecialchars($pItem['harvest_season_tr'] ?: 'Tüm Yıl') ?>
                        </td>
                        <td>
                            <?= $pItem['is_featured'] ? '<span style="color:#d97706; font-weight:800; background:#fef3c7; padding:3px 9px; border-radius:20px; font-size:12px;">⭐ Vitrinde</span>' : '<span style="color:#94a3b8; font-size:12px;">—</span>' ?>
                        </td>
                        <td>
                            <?= $pItem['is_active'] ? '<span style="background:#f0fdf4; color:#166534; font-size:12px; font-weight:700; padding:4px 10px; border-radius:20px; border:1px solid #bbf7d0;">🟢 Yayında</span>' : '<span style="background:#fef2f2; color:#991b1b; font-size:12px; font-weight:700; padding:4px 10px; border-radius:20px; border:1px solid #fecaca;">🔴 Pasif</span>' ?>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <a href="<?= BASE_URL ?>/products/<?= urlencode($pItem['slug']) ?>" target="_blank" class="btn btn-sm btn-outline" style="margin-right: 4px; padding: 6px 12px; border-radius: 8px; text-decoration: none; font-size: 12.5px;" title="Ön yüzde canlı incele">
                                Canlı Gör ↗
                            </a>
                            <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/products?action=edit&id=<?= $pItem['id'] ?>" class="btn btn-sm btn-primary" style="margin-right: 4px; background: #15803d; border-color: #15803d; padding: 6px 14px; border-radius: 8px; font-weight: 700; text-decoration: none; font-size: 12.5px;">
                                Düzenle ✏️
                            </a>
                            <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/delete-product?id=<?= $pItem['id'] ?>" 
                               data-confirm-delete="<?= htmlspecialchars($pItem['name_tr']) ?> ürününü silmek istediğinize emin misiniz?" 
                               class="btn btn-sm btn-danger" style="padding: 6px 12px; border-radius: 8px; font-size: 12.5px;">
                                Sil 🗑️
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- KULLANICI TALEBİ: Ürün Kategorileri Yönetim Kartı -->
<div class="panel-card" style="margin-top: 28px;">
    <div class="panel-card-header">
        <div>
            <h2 class="panel-card-title">📁 Ürün Kategorileri</h2>
            <p style="color: var(--panel-text-muted); font-size: 14px; margin-top: 4px;">
                Mevcut kategorileri düzenleyin veya sitenin ürün menüsüne yeni kategori ekleyin.
            </p>
        </div>
        <div>
            <button onclick="openNewCategoryModal()" class="btn btn-primary" style="padding: 10px 20px; background: #15803d; border-color: #15803d; border-radius: 10px; font-weight: 700;">
                + Yeni Kategori Ekle
            </button>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table class="panel-table">
            <thead>
                <tr>
                    <th>Sıra</th>
                    <th>Kategori Adı</th>
                    <th>URL (Slug)</th>
                    <th>Rozet Rengi</th>
                    <th>Durum</th>
                    <th style="text-align: right;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td style="font-weight: 800; color: #64748b;">
                            #<?= (int)$cat['sort_order'] ?>
                        </td>
                        <td style="font-weight: 800; font-size: 14.5px; color: #0f172a;">
                            <?= htmlspecialchars($cat['name_tr']) ?>
                        </td>
                        <td style="font-family: monospace; font-size: 13px; color: #475569;">
                            /urunler?kategori=<?= htmlspecialchars($cat['slug']) ?>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="display: inline-block; width: 18px; height: 18px; border-radius: 50%; background: <?= htmlspecialchars($cat['badge_color'] ?: '#15803d') ?>; border: 1.5px solid #cbd5e1;"></span>
                                <span style="font-size: 12.5px; font-weight: 700; color: #475569;"><?= htmlspecialchars($cat['badge_color'] ?: '#15803d') ?></span>
                            </div>
                        </td>
                        <td>
                            <?= $cat['is_active'] ? '<span style="background:#f0fdf4; color:#166534; font-size:11.5px; font-weight:700; padding:3px 9px; border-radius:20px; border:1px solid #bbf7d0;">Aktif</span>' : '<span style="background:#fef2f2; color:#991b1b; font-size:11.5px; font-weight:700; padding:3px 9px; border-radius:20px;">Pasif</span>' ?>
                        </td>
                        <td style="text-align: right;">
                            <button type="button" class="btn btn-sm btn-outline" style="margin-right: 4px; padding: 5px 12px; border-radius: 8px;" onclick='openEditCategoryModal(<?= json_encode($cat, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'>
                                Düzenle ✏️
                            </button>
                            <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/delete-category?id=<?= $cat['id'] ?>" 
                               data-confirm-delete="<?= htmlspecialchars($cat['name_tr']) ?> kategorisini silmek istediğinize emin misiniz? (Bu kategorideki ürünler genel kategoriye aktarılacaktır)" 
                               class="btn btn-sm btn-danger" style="padding: 5px 12px; border-radius: 8px;">
                                Sil 🗑️
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Kategori Ekleme & Düzenleme Modalı -->
<div id="categoryModal" style="display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.65); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #ffffff; border-radius: 20px; width: 100%; max-width: 520px; padding: 28px; box-shadow: 0 25px 60px rgba(0,0,0,0.25);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 14px;">
            <h3 id="categoryModalTitle" style="font-family:'Outfit', sans-serif; font-size: 20px; font-weight: 800; color:#0f172a; margin:0;">
                📁 Yeni Kategori Ekle
            </h3>
            <button type="button" onclick="closeModal('categoryModal')" style="background:none; border:none; font-size: 22px; cursor: pointer; color:#94a3b8;">✕</button>
        </div>

        <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-category" method="POST">
            <?= Security::csrfField() ?>
            <input type="hidden" name="id" id="cat_id">

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" style="font-weight: 700;">Kategori Adı *</label>
                <input type="text" name="name_tr" id="cat_name_tr" required class="form-control">
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" style="font-weight: 700;">URL Uzantısı (Slug)</label>
                <input type="text" name="slug" id="cat_slug" class="form-control">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700;">Rozet Rengi</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="cat_badge_color_picker" value="#15803d" style="width: 44px; height: 38px; border-radius: 8px; border: 1.5px solid #cbd5e1; cursor: pointer;" oninput="document.getElementById('cat_badge_color').value = this.value">
                        <input type="text" name="badge_color" id="cat_badge_color" value="#15803d" class="form-control" style="flex: 1;" oninput="document.getElementById('cat_badge_color_picker').value = this.value">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 700;">Sıralama No</label>
                    <input type="number" name="sort_order" id="cat_sort_order" value="1" min="0" class="form-control">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-weight: 700; color: #0f172a;">
                    <input type="checkbox" name="is_active" id="cat_is_active" value="1" checked style="width: 18px; height: 18px;">
                    <span>Kategori Sitede Aktif Olsun</span>
                </label>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" onclick="closeModal('categoryModal')" class="btn btn-secondary" style="padding: 10px 20px;">
                    İptal
                </button>
                <button type="submit" id="catSubmitBtn" class="btn btn-primary" style="padding: 10px 26px; background: #15803d; border-color: #15803d;">
                    💾 Kategoriyi Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<!-- ============================================================== -->
<!-- 2. IN-PAGE TAM SAYFA ÜRÜN DÜZENLEME & EKLEME GÖRÜNÜMÜ          -->
<!-- ============================================================== -->

<div style="margin-bottom: 24px;">
    <!-- Üst Bar: Geri Dönüş ve Başlık -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 20px 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
        <div>
            <div style="margin-bottom: 6px;">
                <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/products" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px; padding: 7px 14px; font-size: 13px; font-weight: 700; border-radius: 10px; text-decoration: none;">
                    <span>←</span> <span>Tüm Ürünler Listesine Dön</span>
                </a>
            </div>
            <h1 style="font-family: 'Outfit', sans-serif; font-size: 24px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 10px;">
                <span><?= $isNew ? '➕' : '✏️' ?></span>
                <span><?= $isNew ? 'Yeni Ürün Ekle' : 'Ürünü Düzenle: ' . htmlspecialchars($prodName) ?></span>
            </h1>
            <p style="font-size: 13.5px; color: #64748b; margin: 4px 0 0 0;">
                Cinsler, hasat takvimi, karakteristikler, besin değerleri ve ihracat ambalajlarını tek bir sayfadan kolayca yönetin.
            </p>
        </div>

        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <?php if (!$isNew && !empty($prodSlug)): ?>
                <a href="<?= BASE_URL ?>/products/<?= urlencode($prodSlug) ?>" target="_blank" class="btn btn-outline" style="padding: 10px 18px; border-radius: 10px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    <span>🌐</span> <span>Canlı Sayfayı Gör ↗</span>
                </a>
            <?php endif; ?>
            <button type="button" onclick="submitProductForm('stay')" class="btn btn-primary" style="padding: 10px 22px; background: #15803d; border-color: #15803d; border-radius: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; box-shadow: 0 4px 12px rgba(21,128,61,0.25);">
                <span>💾</span> <span>Değişiklikleri Kaydet</span>
            </button>
            <button type="button" onclick="submitProductForm('list')" class="btn btn-secondary" style="padding: 10px 18px; border-radius: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; background: #f8fafc; border: 1.5px solid #cbd5e1;">
                <span>✅</span> <span>Kaydet & Listeye Dön</span>
            </button>
        </div>
    </div>
</div>

<!-- Ana Form -->
<form id="productMainForm" action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-product" method="POST" enctype="multipart/form-data">
    <?= Security::csrfField() ?>
    <input type="hidden" name="id" value="<?= $isNew ? '' : (int)$prodId ?>">
    <input type="hidden" name="redirect_to" id="redirect_to_field" value="stay">

    <!-- 2 Sütunlu Sayfa Düzeni (Sol: İçerik & Cinsler & Karakteristikler & Ambalaj | Sağ: Görsel, Hasat, Yayınlama & Standartlar) -->
    <div style="display: grid; grid-template-columns: minmax(0, 1fr) 380px; gap: 24px; align-items: start;">
        
        <!-- ================= SOL SÜTUN ================= -->
        <div style="display: flex; flex-direction: column; gap: 24px;">

            <!-- KART 1: TEMEL BİLGİLER -->
            <div class="panel-card" style="border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 26px;">
                <h3 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 18px 0; display: flex; align-items: center; gap: 10px;">
                    <span>📋</span> <span>1. Temel Ürün Bilgileri</span>
                </h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                    <div>
                        <label class="form-label" style="font-weight: 700;">Kategori *</label>
                        <select name="category_id" required class="form-control" style="font-weight: 600;">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $prodCategoryId) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name_tr']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="form-label" style="font-weight: 700;">Ürün Adı (Türkçe) *</label>
                        <input type="text" name="name_tr" id="inp_name_tr" value="<?= htmlspecialchars($prodName) ?>" required class="form-control" style="font-weight: 700;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 18px; margin-bottom: 18px;">
                    <div>
                        <label class="form-label" style="font-weight: 700;">SEO URL (Slug / Bağlantı Adresi)</label>
                        <input type="text" name="slug" id="inp_slug" value="<?= htmlspecialchars($prodSlug) ?>" class="form-control">
                        <span style="font-size: 12px; color: #64748b; margin-top: 4px; display: block;">Boş bırakılırsa ürün adından otomatik oluşturulur.</span>
                    </div>

                    <div>
                        <label class="form-label" style="font-weight: 700;">Sıralama Önceliği</label>
                        <input type="number" name="sort_order" value="<?= $prodSortOrder ?>" min="0" class="form-control">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label class="form-label" style="font-weight: 700;">Kısa Açıklama (Hero ve Ürün Kartlarında Görünür)</label>
                    <textarea name="short_desc_tr" rows="2" class="form-control"><?= htmlspecialchars($prodShortDesc) ?></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-weight: 700;">Detaylı Ürün İçeriği ve Hikayesi</label>
                    <textarea name="content_tr" rows="4" class="form-control"><?= htmlspecialchars($prodContent) ?></textarea>
                </div>
            </div>

            <!-- KART 2: CİNSLER VE ALT ÇEŞİTLER (KULLANICI TALEBİ: TAM KAPSAMLI & ANLAŞILIR) -->
            <div class="panel-card" style="border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 26px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px; border-bottom: 1.5px solid #f1f5f9; padding-bottom: 14px;">
                    <div>
                        <h3 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 10px;">
                            <span>🌾</span> <span>2. Cinsler & Alt Çeşitler Yönetimi</span>
                        </h3>
                        <p style="font-size: 13px; color: #64748b; margin: 4px 0 0 0;">
                            Ürün detay sayfasındaki Hasat Takvimi tablosunda listelenir. Ziyaretçi cinsi seçtiğinde besin ve kalite değerleri canlı değişir.
                        </p>
                    </div>
                    <button type="button" onclick="addNewVarietyCard()" class="btn btn-primary" style="padding: 8px 18px; background: #16a34a; border-color: #16a34a; border-radius: 10px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; cursor: pointer;">
                        <span>➕</span> <span>+ Yeni Cins Ekle</span>
                    </button>
                </div>

                <!-- Cins Kartları Konteyneri -->
                <div id="varietiesContainer" style="display: flex; flex-direction: column; gap: 16px;">
                    <?php if (empty($varieties)): ?>
                        <!-- İlk Açılış İçin 2 Örnek Kart -->
                        <?php 
                        $varieties = [
                            ['name' => ($prodName ? $prodName . ' - Select' : ''), 'months' => $prodHarvestTr, 'desc' => '', 'char_note' => '', 'nutrition' => ['kcal' => '', 'protein' => '', 'carb' => '', 'fat' => ''], 'char' => ['et' => '', 'cekirdek' => '', 'kabuk' => '', 'doku' => '', 'sekil' => '', 'tat' => '']],
                            ['name' => ($prodName ? $prodName . ' - Standart' : ''), 'months' => $prodHarvestTr, 'desc' => '', 'char_note' => '', 'nutrition' => ['kcal' => '', 'protein' => '', 'carb' => '', 'fat' => ''], 'char' => ['et' => '', 'cekirdek' => '', 'kabuk' => '', 'doku' => '', 'sekil' => '', 'tat' => '']]
                        ];
                        ?>
                    <?php endif; ?>

                    <?php foreach ($varieties as $vIdx => $v): ?>
                        <div class="variety-item-card" style="background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 14px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span class="variety-item-badge" style="background: #f0fdf4; color: #166534; font-weight: 800; font-size: 12.5px; padding: 4px 10px; border-radius: 8px; border: 1px solid #bbf7d0;">
                                        #<?= $vIdx + 1 ?>. Cins / Alt Çeşit
                                    </span>
                                    <span class="variety-title-preview" style="font-weight: 700; font-size: 14px; color: #1e293b;">
                                        <?= htmlspecialchars($v['name'] ?? 'İsimsiz Cins') ?>
                                    </span>
                                </div>
                                <button type="button" onclick="this.closest('.variety-item-card').remove(); reindexVarieties();" class="btn btn-sm btn-danger" style="padding: 4px 12px; font-size: 12px; border-radius: 8px; font-weight: 700;" title="Bu Cinsi Kaldır">
                                    🗑️ Cinsi Sil
                                </button>
                            </div>

                            <!-- Temel Cins Bilgileri -->
                            <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 14px; margin-bottom: 12px;">
                                <div>
                                    <label class="form-label" style="font-size: 12px; font-weight: 700; margin-bottom: 4px;">Cins / Çeşit Adı *</label>
                                    <input type="text" name="extra[varieties][<?= $vIdx ?>][name]" value="<?= htmlspecialchars($v['name'] ?? '') ?>" class="form-control variety-name-input" style="font-weight: 700; font-size: 13.5px;" required oninput="updateVarietyBadge(this)">
                                </div>
                                <div>
                                    <label class="form-label" style="font-size: 12px; font-weight: 700; margin-bottom: 4px;">Hasat Sezonu Ayları</label>
                                    <input type="text" name="extra[varieties][<?= $vIdx ?>][months]" value="<?= htmlspecialchars($v['months'] ?? '') ?>" class="form-control" style="font-size: 13px;">
                                </div>
                            </div>

                            <div style="margin-bottom: 12px;">
                                <label class="form-label" style="font-size: 12px; font-weight: 700; margin-bottom: 4px;">Cins Açıklaması & Kalite Notları</label>
                                <textarea name="extra[varieties][<?= $vIdx ?>][desc]" rows="2" class="form-control" style="font-size: 12.5px;"><?= htmlspecialchars($v['desc'] ?? '') ?></textarea>
                            </div>

                            <div style="margin-bottom: 14px;">
                                <label class="form-label" style="font-size: 12px; font-weight: 700; margin-bottom: 4px;">Öne Çıkan Karakteristik / Kalite Özelliği</label>
                                <input type="text" name="extra[varieties][<?= $vIdx ?>][char_note]" value="<?= htmlspecialchars($v['char_note'] ?? '') ?>" class="form-control" style="font-size: 12.5px;">
                            </div>

                            <!-- Katlanabilir / Genişletilmiş Cins Detayları (Besin Değerleri ve Karakteristikler) -->
                            <details style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px;">
                                <summary style="font-size: 12.5px; font-weight: 700; color: #15803d; cursor: pointer; user-select: none;">
                                    ⚙️ Bu Cinse Özel Besin Değerleri ve Karakteristikleri Özelleştir (Opsiyonel)
                                </summary>
                                <div style="margin-top: 14px; padding-top: 12px; border-top: 1px dashed #cbd5e1;">
                                    
                                    <!-- Besin Değerleri (100g) -->
                                    <div style="margin-bottom: 14px;">
                                        <div style="font-size: 12px; font-weight: 800; color: #334155; margin-bottom: 8px;">🍽️ Besin Değerleri (100g için)</div>
                                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;">
                                            <div>
                                                <label style="font-size: 11px; font-weight: 700; color: #64748b;">Kalori (kcal)</label>
                                                <input type="text" name="extra[varieties][<?= $vIdx ?>][nutrition][kcal]" value="<?= htmlspecialchars($v['nutrition']['kcal'] ?? '') ?>" class="form-control" style="font-size: 12px;">
                                            </div>
                                            <div>
                                                <label style="font-size: 11px; font-weight: 700; color: #64748b;">Protein</label>
                                                <input type="text" name="extra[varieties][<?= $vIdx ?>][nutrition][protein]" value="<?= htmlspecialchars($v['nutrition']['protein'] ?? '') ?>" class="form-control" style="font-size: 12px;">
                                            </div>
                                            <div>
                                                <label style="font-size: 11px; font-weight: 700; color: #64748b;">Karbonhidrat</label>
                                                <input type="text" name="extra[varieties][<?= $vIdx ?>][nutrition][carb]" value="<?= htmlspecialchars($v['nutrition']['carb'] ?? '') ?>" class="form-control" style="font-size: 12px;">
                                            </div>
                                            <div>
                                                <label style="font-size: 11px; font-weight: 700; color: #64748b;">Yağ</label>
                                                <input type="text" name="extra[varieties][<?= $vIdx ?>][nutrition][fat]" value="<?= htmlspecialchars($v['nutrition']['fat'] ?? '') ?>" class="form-control" style="font-size: 12px;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cins Karakteristikleri -->
                                    <div>
                                        <div style="font-size: 12px; font-weight: 800; color: #334155; margin-bottom: 8px;">🌿 Karakteristik Özellikleri (Tablo Tıklanınca Sağda Gözükür)</div>
                                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 10px;">
                                            <div>
                                                <label style="font-size: 11px; font-weight: 700; color: #64748b;">Et / Tane Rengi</label>
                                                <input type="text" name="extra[varieties][<?= $vIdx ?>][char][et]" value="<?= htmlspecialchars($v['char']['et'] ?? '') ?>" class="form-control" style="font-size: 12px;">
                                            </div>
                                            <div>
                                                <label style="font-size: 11px; font-weight: 700; color: #64748b;">Çekirdek / Sertlik</label>
                                                <input type="text" name="extra[varieties][<?= $vIdx ?>][char][cekirdek]" value="<?= htmlspecialchars($v['char']['cekirdek'] ?? '') ?>" class="form-control" style="font-size: 12px;">
                                            </div>
                                            <div>
                                                <label style="font-size: 11px; font-weight: 700; color: #64748b;">Kabuk Rengi / Kepek</label>
                                                <input type="text" name="extra[varieties][<?= $vIdx ?>][char][kabuk]" value="<?= htmlspecialchars($v['char']['kabuk'] ?? '') ?>" class="form-control" style="font-size: 12px;">
                                            </div>
                                        </div>
                                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                                            <div>
                                                <label style="font-size: 11px; font-weight: 700; color: #64748b;">Dokusu & Verim</label>
                                                <input type="text" name="extra[varieties][<?= $vIdx ?>][char][doku]" value="<?= htmlspecialchars($v['char']['doku'] ?? '') ?>" class="form-control" style="font-size: 12px;">
                                            </div>
                                            <div>
                                                <label style="font-size: 11px; font-weight: 700; color: #64748b;">Tane / Meyve Şekli</label>
                                                <input type="text" name="extra[varieties][<?= $vIdx ?>][char][sekil]" value="<?= htmlspecialchars($v['char']['sekil'] ?? '') ?>" class="form-control" style="font-size: 12px;">
                                            </div>
                                            <div>
                                                <label style="font-size: 11px; font-weight: 700; color: #64748b;">Tadı & Aroma</label>
                                                <input type="text" name="extra[varieties][<?= $vIdx ?>][char][tat]" value="<?= htmlspecialchars($v['char']['tat'] ?? '') ?>" class="form-control" style="font-size: 12px;">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </details>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div style="margin-top: 14px; text-align: right;">
                    <button type="button" onclick="addNewVarietyCard()" class="btn btn-secondary" style="padding: 8px 18px; border-radius: 10px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; border: 1.5px solid #cbd5e1;">
                        <span>➕</span> <span>+ Yeni Cins Ekle</span>
                    </button>
                </div>
            </div>

            <!-- KART 3: DİNAMİK ÜRÜN KARAKTERİSTİK ÖZELLİKLERİ (BAŞLIKLAR VE DEĞERLER DÜZENLENEBİLİR) -->
            <div class="panel-card" style="border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 26px;">
                <h3 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 6px 0; display: flex; align-items: center; gap: 10px;">
                    <span>🌿</span> <span>3. Ürün Karakteristik Özellikleri (Tamamen Dinamik Başlık & Değerler)</span>
                </h3>
                <p style="font-size: 13px; color: #64748b; margin: 0 0 18px 0;">
                    Ürün detay sayfasındaki raptiyeli not kartında gösterilir. Başlıkları ve değerleri ürün türünüze göre (örneğin Hububatta: <em>"TANE / ET RENGİ"</em>, Narenciyede: <em>"ET RENGİ"</em>) dilediğiniz gibi özelleştirebilirsiniz.
                </p>

                <!-- Kart Üst Başlıkları -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                    <div style="font-size: 13px; font-weight: 800; color: #334155; margin-bottom: 10px;">📌 Raptiyeli Kart Başlık & Alt Metinleri</div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px;">
                        <div>
                            <label class="form-label" style="font-size: 11.5px; font-weight: 700; color: #475569;">Kart Ana Başlığı</label>
                            <input type="text" name="extra[char_panel_title]" value="<?= htmlspecialchars($charPanelTitle) ?>" class="form-control" style="font-size: 13px;">
                        </div>
                        <div>
                            <label class="form-label" style="font-size: 11.5px; font-weight: 700; color: #475569;">Kart Alt Açıklaması</label>
                            <input type="text" name="extra[char_panel_desc]" value="<?= htmlspecialchars($charPanelDesc) ?>" class="form-control" style="font-size: 13px;">
                        </div>
                        <div>
                            <label class="form-label" style="font-size: 11.5px; font-weight: 700; color: #475569;">Alt Rozet / Standart Metni</label>
                            <input type="text" name="extra[char_panel_footer]" value="<?= htmlspecialchars($prodExtra['char_panel_footer'] ?? '') ?>" class="form-control" style="font-size: 13px;">
                        </div>
                    </div>
                </div>

                <!-- 6 Satır / Kutu Dinamik Başlık & Değer -->
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px;">
                    <!-- 1. Özellik -->
                    <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px;">
                        <span style="font-size: 11px; font-weight: 800; color: #16a34a; text-transform: uppercase; display: block; margin-bottom: 6px;">1. Özellik Satırı</span>
                        <div style="display: grid; grid-template-columns: 1fr 1.3fr; gap: 10px;">
                            <div>
                                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 3px;">Sabit Başlık / Etiket</label>
                                <input type="text" name="extra[char_lbl_et_rengi]" value="<?= htmlspecialchars($charLblEt) ?>" class="form-control" style="font-size: 12.5px; font-weight: 700;">
                            </div>
                            <div>
                                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 3px;">Değer</label>
                                <input type="text" name="extra[char_et_rengi]" value="<?= htmlspecialchars($charEt) ?>" class="form-control" style="font-size: 12.5px;">
                            </div>
                        </div>
                    </div>

                    <!-- 2. Özellik -->
                    <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px;">
                        <span style="font-size: 11px; font-weight: 800; color: #16a34a; text-transform: uppercase; display: block; margin-bottom: 6px;">2. Özellik Satırı</span>
                        <div style="display: grid; grid-template-columns: 1fr 1.3fr; gap: 10px;">
                            <div>
                                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 3px;">Sabit Başlık / Etiket</label>
                                <input type="text" name="extra[char_lbl_cekirdek]" value="<?= htmlspecialchars($charLblCekirdek) ?>" class="form-control" style="font-size: 12.5px; font-weight: 700;">
                            </div>
                            <div>
                                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 3px;">Değer</label>
                                <input type="text" name="extra[char_cekirdek]" value="<?= htmlspecialchars($charCekirdek) ?>" class="form-control" style="font-size: 12.5px;">
                            </div>
                        </div>
                    </div>

                    <!-- 3. Özellik -->
                    <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px;">
                        <span style="font-size: 11px; font-weight: 800; color: #16a34a; text-transform: uppercase; display: block; margin-bottom: 6px;">3. Özellik Satırı</span>
                        <div style="display: grid; grid-template-columns: 1fr 1.3fr; gap: 10px;">
                            <div>
                                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 3px;">Sabit Başlık / Etiket</label>
                                <input type="text" name="extra[char_lbl_kabuk_rengi]" value="<?= htmlspecialchars($charLblKabuk) ?>" class="form-control" style="font-size: 12.5px; font-weight: 700;">
                            </div>
                            <div>
                                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 3px;">Değer</label>
                                <input type="text" name="extra[char_kabuk_rengi]" value="<?= htmlspecialchars($charKabuk) ?>" class="form-control" style="font-size: 12.5px;">
                            </div>
                        </div>
                    </div>

                    <!-- 4. Özellik -->
                    <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px;">
                        <span style="font-size: 11px; font-weight: 800; color: #16a34a; text-transform: uppercase; display: block; margin-bottom: 6px;">4. Özellik Satırı</span>
                        <div style="display: grid; grid-template-columns: 1fr 1.3fr; gap: 10px;">
                            <div>
                                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 3px;">Sabit Başlık / Etiket</label>
                                <input type="text" name="extra[char_lbl_dokusu]" value="<?= htmlspecialchars($charLblDoku) ?>" class="form-control" style="font-size: 12.5px; font-weight: 700;">
                            </div>
                            <div>
                                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 3px;">Değer</label>
                                <input type="text" name="extra[char_dokusu]" value="<?= htmlspecialchars($charDoku) ?>" class="form-control" style="font-size: 12.5px;">
                            </div>
                        </div>
                    </div>

                    <!-- 5. Özellik -->
                    <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px;">
                        <span style="font-size: 11px; font-weight: 800; color: #16a34a; text-transform: uppercase; display: block; margin-bottom: 6px;">5. Özellik Satırı</span>
                        <div style="display: grid; grid-template-columns: 1fr 1.3fr; gap: 10px;">
                            <div>
                                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 3px;">Sabit Başlık / Etiket</label>
                                <input type="text" name="extra[char_lbl_sekil]" value="<?= htmlspecialchars($charLblSekil) ?>" class="form-control" style="font-size: 12.5px; font-weight: 700;">
                            </div>
                            <div>
                                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 3px;">Değer</label>
                                <input type="text" name="extra[char_sekil]" value="<?= htmlspecialchars($charSekil) ?>" class="form-control" style="font-size: 12.5px;">
                            </div>
                        </div>
                    </div>

                    <!-- 6. Özellik -->
                    <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px;">
                        <span style="font-size: 11px; font-weight: 800; color: #16a34a; text-transform: uppercase; display: block; margin-bottom: 6px;">6. Özellik Satırı</span>
                        <div style="display: grid; grid-template-columns: 1fr 1.3fr; gap: 10px;">
                            <div>
                                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 3px;">Sabit Başlık / Etiket</label>
                                <input type="text" name="extra[char_lbl_tadi]" value="<?= htmlspecialchars($charLblTat) ?>" class="form-control" style="font-size: 12.5px; font-weight: 700;">
                            </div>
                            <div>
                                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 3px;">Değer</label>
                                <input type="text" name="extra[char_tadi]" value="<?= htmlspecialchars($charTat) ?>" class="form-control" style="font-size: 12.5px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KART 4: İHRACAT AMBALAJ VE KUTU SEÇENEKLERİ (DİNAMİK EKLEME / SİLME & SEÇENEKLİ KUTUCUK) -->
            <div class="panel-card" style="border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 26px;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 8px;">
                    <h3 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 10px;">
                        <span>📦</span> <span>4. İhracat Ambalaj ve Kutu Seçenekleri</span>
                    </h3>
                    <button type="button" onclick="addNewPackagingBox()" class="btn btn-sm btn-primary" style="background: #15803d; border-color: #15803d; border-radius: 8px; font-weight: 700; padding: 6px 14px; font-size: 12.5px; display: inline-flex; align-items: center; gap: 6px;">
                        <span>➕</span> <span>Yeni Kutu / Ambalaj Ekle</span>
                    </button>
                </div>
                <p style="font-size: 13px; color: #64748b; margin: 0 0 16px 0;">
                    Ürün detay sayfasında sunulan ambalaj, dökme veya karton kasa seçeneklerini buradan yönetin, ekleyip silin.
                </p>

                <!-- KULLANICI TALEBİ: SEÇENEKLİ KUTUCUK AYARI -->
                <div style="background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 12px; padding: 14px 18px; margin-bottom: 20px;">
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; font-weight: 800; font-size: 14px; color: #166534; margin: 0;">
                        <input type="hidden" name="extra[show_packaging]" value="0">
                        <input type="checkbox" name="extra[show_packaging]" value="1" id="chk_show_packaging" <?= $showPackaging ? 'checked' : '' ?> onchange="togglePackagingSection(this.checked)" style="width: 20px; height: 20px; accent-color: #16a34a; cursor: pointer;">
                        <span>📦 Bu Üründe İhracat Ambalaj ve Kutu Seçeneklerini Göster (Aktif)</span>
                    </label>
                    <small style="color: #15803d; font-size: 11.5px; display: block; margin-top: 5px; margin-left: 32px;">
                        İşaretlenirse ürün detay sayfasında kutu/ambalaj bölümü görüntülenir; işaretsiz bırakılırsa bu bölüm ürün sayfasında tamamen gizlenir.
                    </small>
                </div>

                <!-- Dinamik Kutu Listesi Konteyneri -->
                <div id="packagingItemsContainer" style="display: <?= $showPackaging ? 'flex' : 'none' ?>; flex-direction: column; gap: 16px;">
                    <?php if (empty($packagingItems)): ?>
                        <div id="emptyPackagingNotice" style="text-align: center; padding: 30px; background: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: 12px; color: #64748b;">
                            Henüz kutu/ambalaj seçeneği eklenmemiş. Yukarıdaki <strong>"Yeni Kutu / Ambalaj Ekle"</strong> butonuna tıklayarak kutu ekleyebilirsiniz.
                        </div>
                    <?php else: ?>
                        <?php foreach ($packagingItems as $bIdx => $bItem): 
                            $bTitle = $bItem['title'] ?? '';
                            $bWeight = $bItem['weight'] ?? '';
                            $bDim = $bItem['dim'] ?? '';
                            $bImg = !empty($bItem['image']) ? $bItem['image'] : 'packaging_carton_1.png';
                        ?>
                            <div class="packaging-item-card" data-box-index="<?= $bIdx ?>" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                                    <span style="font-weight: 800; font-size: 13px; color: #0f172a;" class="box-card-title">
                                        📦 Kutu / Ambalaj #<?= $bIdx + 1 ?>
                                    </span>
                                    <button type="button" onclick="removePackagingBox(this)" class="btn btn-sm btn-danger" style="padding: 4px 10px; font-size: 11.5px; border-radius: 6px; background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; cursor: pointer; font-weight: 700;">
                                        🗑️ Bu Kutuyu Sil
                                    </button>
                                </div>

                                <div style="display: grid; grid-template-columns: 80px 1.5fr 1fr 1.5fr; gap: 14px; align-items: center;">
                                    <div>
                                        <img id="pkg_box_preview_<?= $bIdx ?>" src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($bImg) ?>" style="width: 70px; height: 60px; object-fit: contain; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 4px;">
                                    </div>
                                    <div>
                                        <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 2px;">Ambalaj Tipi / Başlık</label>
                                        <input type="text" name="extra[packaging_items][<?= $bIdx ?>][title]" value="<?= htmlspecialchars($bTitle) ?>" class="form-control" style="font-size: 12.5px;">
                                        <input type="hidden" name="extra[packaging_items][<?= $bIdx ?>][image]" value="<?= htmlspecialchars($bImg) ?>" class="pkg-img-input">
                                        <input type="file" name="pkg_box_file_<?= $bIdx ?>" accept="image/*" style="font-size: 11px; margin-top: 4px;" onchange="previewPkgFile(this, 'pkg_box_preview_<?= $bIdx ?>')">
                                    </div>
                                    <div>
                                        <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 2px;">Net Ağırlık</label>
                                        <input type="text" name="extra[packaging_items][<?= $bIdx ?>][weight]" value="<?= htmlspecialchars($bWeight) ?>" class="form-control" style="font-size: 12.5px;">
                                    </div>
                                    <div>
                                        <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 2px;">Ölçüler / Kullanım Alanı</label>
                                        <input type="text" name="extra[packaging_items][<?= $bIdx ?>][dim]" value="<?= htmlspecialchars($bDim) ?>" class="form-control" style="font-size: 12.5px;">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Alt Ekle Butonu -->
                <div style="margin-top: 18px; text-align: left;">
                    <button type="button" onclick="addNewPackagingBox()" class="btn btn-secondary" style="background: #f8fafc; border: 1.5px dashed #94a3b8; color: #0f172a; padding: 10px 22px; font-weight: 700; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                        <span>➕</span> <span>Yeni Kutu / Ambalaj Ekle</span>
                    </button>
                </div>
            </div>

            <!-- KART 5: ÜRÜN DETAYI KAYAN GALERİ ENTEGRASYONU (KULLANICI TALEBİ) -->
            <div class="panel-card" style="border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 26px;">
                <h3 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 6px 0; display: flex; align-items: center; gap: 10px;">
                    <span>🖼️</span> <span>5. Ürün Detayında Kayan Galeri Entegrasyonu</span>
                </h3>
                <p style="font-size: 13px; color: #64748b; margin: 0 0 16px 0;">
                    Ürün detay sayfasının en altında, ana sayfadaki referans kaydırma bandı gibi sürekli yavaşça kayan ve elle de sürüklenebilen galeri şeridini buradan yönetin.
                </p>

                <!-- Aç/Kapat Seçeneği -->
                <div style="background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 12px; padding: 14px 18px; margin-bottom: 16px;">
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; font-weight: 800; font-size: 14px; color: #166534; margin: 0;">
                        <input type="hidden" name="extra[show_product_gallery]" value="0">
                        <input type="checkbox" name="extra[show_product_gallery]" value="1" <?= $showProductGallery ? 'checked' : '' ?> style="width: 20px; height: 20px; accent-color: #16a34a; cursor: pointer;">
                        <span>🖼️ Bu Ürünün Detay Sayfasında Kayan Galeri Bandını Göster (Aktif)</span>
                    </label>
                </div>

                <!-- Galeri Kategori Bağlantısı -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label class="form-label" style="font-size: 12.5px; font-weight: 700;">📂 Bağlanacak Galeri Kategorisi</label>
                        <select name="extra[gallery_category]" class="form-control" style="font-size: 13.5px; font-weight: 600;">
                            <option value="all" <?= ($galleryCategory === 'all' || empty($galleryCategory)) ? 'selected' : '' ?>>🌟 Tüm Galeri Görselleri</option>
                            <option value="urunler" <?= ($galleryCategory === 'urunler') ? 'selected' : '' ?>>🍊 Ürünlerimiz (urunler)</option>
                            <option value="hasat" <?= ($galleryCategory === 'hasat') ? 'selected' : '' ?>>🌾 Hasat & Bahçelerimiz (hasat)</option>
                            <option value="tesisler" <?= ($galleryCategory === 'tesisler') ? 'selected' : '' ?>>🏭 Tesisler & Teknoloji (tesisler)</option>
                            <option value="paketleme" <?= ($galleryCategory === 'paketleme') ? 'selected' : '' ?>>📦 Paketleme & Tasarım (paketleme)</option>
                            <option value="lojistik" <?= ($galleryCategory === 'lojistik') ? 'selected' : '' ?>>🚚 Soğuk Zincir & Lojistik (lojistik)</option>
                            <option value="tanitim" <?= ($galleryCategory === 'tanitim') ? 'selected' : '' ?>>🎬 Kurumsal Tanıtım (tanitim)</option>
                        </select>
                        <small style="color: #64748b; font-size: 11.5px; display: block; margin-top: 4px;">
                            Galeride bu kategoriye eklenmiş görseller otomatik olarak bu ürünün altında akar.
                        </small>
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 12.5px; font-weight: 700;">🏷️ Galeri Özel Başlığı (Opsiyonel)</label>
                        <input type="text" name="extra[gallery_custom_title]" value="<?= htmlspecialchars($prodExtra['gallery_custom_title'] ?? '') ?>" class="form-control" style="font-size: 13px;">
                        <small style="color: #64748b; font-size: 11.5px; display: block; margin-top: 4px;">
                            Boş bırakılırsa standart başlık gösterilir.
                        </small>
                    </div>
                </div>
            </div>

        </div>

        <!-- ================= SAĞ SÜTUN (SIDEBAR) ================= -->
        <div style="display: flex; flex-direction: column; gap: 24px; position: sticky; top: 20px;">

            <!-- KART: YAYIN VE KAYIT AKSİYONU -->
            <div class="panel-card" style="border: 2px solid #bbf7d0; background: #ffffff; border-radius: 16px; padding: 22px; box-shadow: 0 4px 16px rgba(21,128,61,0.08);">
                <h4 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0 0 14px 0; display: flex; align-items: center; gap: 8px;">
                    <span>💾</span> <span>Yayın ve Kayıt İşlemleri</span>
                </h4>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 14px; margin-bottom: 16px;">
                    <label style="display: flex; align-items: center; gap: 10px; font-weight: 700; cursor: pointer; color: #0f172a; margin-bottom: 8px;">
                        <input type="checkbox" name="is_active" value="1" <?= $prodIsActive ? 'checked' : '' ?> style="width: 18px; height: 18px; accent-color: #15803d;">
                        <span>🟢 Sitede Yayında Göster</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; font-weight: 700; cursor: pointer; color: #0f172a;">
                        <input type="checkbox" name="is_featured" value="1" <?= $prodIsFeatured ? 'checked' : '' ?> style="width: 18px; height: 18px; accent-color: #d97706;">
                        <span>⭐ Vitrinde Öne Çıkar</span>
                    </label>
                </div>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <button type="button" onclick="submitProductForm('stay')" class="btn btn-primary" style="width: 100%; padding: 12px; background: #15803d; border-color: #15803d; border-radius: 10px; font-weight: 800; font-size: 14.5px; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer;">
                        <span>💾</span> <span>Değişiklikleri Kaydet</span>
                    </button>
                    <button type="button" onclick="submitProductForm('list')" class="btn btn-secondary" style="width: 100%; padding: 10px; border-radius: 10px; font-weight: 700; font-size: 13.5px; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; background: #f8fafc; border: 1.5px solid #cbd5e1;">
                        <span>✅</span> <span>Kaydet & Listeye Dön</span>
                    </button>
                    <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/products" class="btn btn-outline" style="width: 100%; padding: 8px; border-radius: 10px; font-weight: 600; font-size: 13px; text-align: center; text-decoration: none; color: #64748b;">
                        Vazgeç / Listeye Dön
                    </a>
                </div>
            </div>

            <!-- KART: ANA ÜRÜN GÖRSELİ -->
            <div class="panel-card" style="border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 22px;">
                <h4 style="font-size: 15px; font-weight: 800; color: #0f172a; margin: 0 0 12px 0; display: flex; align-items: center; gap: 8px;">
                    <span>📸</span> <span>Ürün Ana Görseli</span>
                </h4>

                <div style="text-align: center; background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 14px; padding: 16px; margin-bottom: 12px;">
                    <img id="main_prod_preview" src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($prodImage) ?>" style="max-width: 100%; height: 160px; object-fit: contain; margin-bottom: 10px; filter: drop-shadow(0 4px 10px rgba(0,0,0,0.06));">
                    <div style="font-size: 12px; font-weight: 700; color: #475569;" id="main_img_label">
                        <?= htmlspecialchars($prodImage) ?>
                    </div>
                    <span style="font-size: 11px; color: #15803d; font-weight: 700; display: block; margin-top: 2px;">
                        ⚡ Otomatik WebP Dönüştürücü Aktif
                    </span>
                </div>

                <input type="hidden" name="image" id="hidden_main_image" value="<?= htmlspecialchars($prodImage) ?>">
                <label class="form-label" style="font-size: 12px; font-weight: 700;">Yeni Görsel Yükle</label>
                <input type="file" name="image_file" accept="image/*" class="form-control" style="font-size: 12px;" onchange="previewMainProductImage(this)">
            </div>

            <!-- KART: HASAT SEZONU AY SEÇİCİ -->
            <div class="panel-card" style="border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 22px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <h4 style="font-size: 15px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                        <span>🗓️</span> <span>12 Ay Hasat Sezonu</span>
                    </h4>
                </div>

                <!-- Hızlı Hazır Butonlar -->
                <div style="display: flex; gap: 6px; margin-bottom: 12px; flex-wrap: wrap;">
                    <button type="button" onclick="presetHarvestMonths('all')" class="btn btn-sm btn-outline" style="font-size: 11px; padding: 3px 8px; border-radius: 6px;">Tüm Yıl</button>
                    <button type="button" onclick="presetHarvestMonths('citrus')" class="btn btn-sm btn-outline" style="font-size: 11px; padding: 3px 8px; border-radius: 6px;">Narenciye (Eki-Nis)</button>
                    <button type="button" onclick="presetHarvestMonths('summer')" class="btn btn-sm btn-outline" style="font-size: 11px; padding: 3px 8px; border-radius: 6px;">Yaz (May-Ağu)</button>
                    <button type="button" onclick="clearHarvestMonths()" class="btn btn-sm btn-outline" style="font-size: 11px; padding: 3px 8px; border-radius: 6px; color: #ef4444 !important; border-color: #fca5a5;">Temizle</button>
                </div>

                <!-- 12 Ay Buton Grid -->
                <div id="harvestMonthsGrid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; margin-bottom: 12px;"></div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 11.5px; font-weight: 700;">Hasat Sezonu Metni (TR)</label>
                    <input type="text" name="harvest_season_tr" id="inp_harvest_season_tr" value="<?= htmlspecialchars($prodHarvestTr) ?>" class="form-control" style="font-size: 12.5px; font-weight: 700;">
                </div>
            </div>

            <!-- KART: MENŞEİ VE LOJİSTİK BİLGİLERİ -->
            <div class="panel-card" style="border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 22px;">
                <h4 style="font-size: 15px; font-weight: 800; color: #0f172a; margin: 0 0 14px 0; display: flex; align-items: center; gap: 8px;">
                    <span>🚚</span> <span>Lojistik & Depolama Bilgileri</span>
                </h4>

                <div class="form-group" style="margin-bottom: 12px;">
                    <label class="form-label" style="font-size: 12px; font-weight: 700;">Menşei Bölge</label>
                    <input type="text" name="origin_tr" value="<?= htmlspecialchars($prodOriginTr) ?>" class="form-control" style="font-size: 12.5px;">
                </div>

                <div class="form-group" style="margin-bottom: 12px;">
                    <label class="form-label" style="font-size: 12px; font-weight: 700;">Ambalaj & Paketleme Türü</label>
                    <input type="text" name="packaging_tr" value="<?= htmlspecialchars($prodPackagingTr) ?>" class="form-control" style="font-size: 12.5px;">
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 12px; font-weight: 700;">Saklama Sıcaklığı / Soğuk Zincir</label>
                    <input type="text" name="temperature" value="<?= htmlspecialchars($prodTemp) ?>" class="form-control" style="font-size: 12.5px;">
                </div>
            </div>

            <!-- KART: VİTRİN NİTELİK & ROZETLERİ -->
            <div class="panel-card" style="border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 22px;">
                <h4 style="font-size: 15px; font-weight: 800; color: #0f172a; margin: 0 0 14px 0; display: flex; align-items: center; gap: 8px;">
                    <span>✨</span> <span>Vitrin Nitelik & Rozetleri</span>
                </h4>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                    <div>
                        <label class="form-label" style="font-size: 11px; font-weight: 700;">Kalori Değeri</label>
                        <input type="text" name="extra[nutrition_calorie]" value="<?= htmlspecialchars($nutriCal) ?>" class="form-control" style="font-size: 12px;">
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 11px; font-weight: 700;">Şeker (Brix)</label>
                        <input type="text" name="extra[nutrition_brix]" value="<?= htmlspecialchars($nutriBrix) ?>" class="form-control" style="font-size: 12px;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 10px;">
                    <label class="form-label" style="font-size: 11px; font-weight: 700;">Asitlik / Enerji Oranı</label>
                    <input type="text" name="extra[nutrition_acidity]" value="<?= htmlspecialchars($nutriAcid) ?>" class="form-control" style="font-size: 12px;">
                </div>

                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <div>
                        <label class="form-label" style="font-size: 11px; font-weight: 700;">1. Nitelik Rozeti</label>
                        <input type="text" name="extra[badge1]" value="<?= htmlspecialchars($badge1) ?>" class="form-control" style="font-size: 12px;">
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 11px; font-weight: 700;">2. Nitelik Rozeti</label>
                        <input type="text" name="extra[badge2]" value="<?= htmlspecialchars($badge2) ?>" class="form-control" style="font-size: 12px;">
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 11px; font-weight: 700;">3. Nitelik Rozeti</label>
                        <input type="text" name="extra[badge3]" value="<?= htmlspecialchars($badge3) ?>" class="form-control" style="font-size: 12px;">
                    </div>
                </div>
            </div>

        </div>

    </div>
</form>

<?php endif; ?>

<!-- ============================================================== -->
<!-- JAVASCRIPT: DİNAMİK CİNSLER, AY SEÇİCİ, GÖRSEL ÖNİZLEME         -->
<!-- ============================================================== -->
<script>
const MONTH_NAMES = [
    { id: 1, tr: 'Ocak', en: 'January' },
    { id: 2, tr: 'Şubat', en: 'February' },
    { id: 3, tr: 'Mart', en: 'March' },
    { id: 4, tr: 'Nisan', en: 'April' },
    { id: 5, tr: 'Mayıs', en: 'May' },
    { id: 6, tr: 'Haziran', en: 'June' },
    { id: 7, tr: 'Temmuz', en: 'July' },
    { id: 8, tr: 'Ağustos', en: 'August' },
    { id: 9, tr: 'Eylül', en: 'September' },
    { id: 10, tr: 'Ekim', en: 'October' },
    { id: 11, tr: 'Kasım', en: 'November' },
    { id: 12, tr: 'Aralık', en: 'December' }
];

let activeMonthIds = new Set();

function initHarvestMonths() {
    const input = document.getElementById('inp_harvest_season_tr');
    if (!input) return;
    parseAndSelectMonths(input.value);
}

function renderMonthButtons() {
    const container = document.getElementById('harvestMonthsGrid');
    if (!container) return;
    container.innerHTML = '';
    
    MONTH_NAMES.forEach(m => {
        const isSelected = activeMonthIds.has(m.id);
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.textContent = m.tr;
        btn.style.cssText = `
            padding: 7px 3px;
            border-radius: 8px;
            font-size: 11.5px;
            cursor: pointer;
            text-align: center;
            transition: all 0.15s ease;
            ${isSelected ? 'background: #15803d; color: #ffffff; border: 1.5px solid #15803d; font-weight: 800; box-shadow: 0 2px 6px rgba(21,128,61,0.25);' : 'background: #ffffff; color: #334155; border: 1.5px solid #cbd5e1; font-weight: 600;'}
        `;
        btn.onclick = () => toggleMonth(m.id);
        container.appendChild(btn);
    });
}

function toggleMonth(id) {
    if (activeMonthIds.has(id)) {
        activeMonthIds.delete(id);
    } else {
        activeMonthIds.add(id);
    }
    renderMonthButtons();
    syncHarvestInputs();
}

function presetHarvestMonths(type) {
    activeMonthIds.clear();
    if (type === 'all') {
        for (let i = 1; i <= 12; i++) activeMonthIds.add(i);
    } else if (type === 'citrus') {
        [10, 11, 12, 1, 2, 3, 4].forEach(i => activeMonthIds.add(i));
    } else if (type === 'summer') {
        [5, 6, 7, 8, 9].forEach(i => activeMonthIds.add(i));
    }
    renderMonthButtons();
    syncHarvestInputs();
}

function clearHarvestMonths() {
    activeMonthIds.clear();
    renderMonthButtons();
    syncHarvestInputs();
}

function syncHarvestInputs() {
    const inp = document.getElementById('inp_harvest_season_tr');
    if (!inp) return;

    if (activeMonthIds.size === 0) {
        inp.value = 'Dönemsel';
        return;
    }
    if (activeMonthIds.size === 12) {
        inp.value = 'Tüm Yıl';
        return;
    }
    
    const sorted = Array.from(activeMonthIds).sort((a,b) => a - b);
    let startMonth = sorted[0];
    let endMonth = sorted[sorted.length - 1];
    
    if (activeMonthIds.has(12) && activeMonthIds.has(1)) {
        for (let i = 1; i <= 12; i++) {
            if (!activeMonthIds.has(i) && activeMonthIds.has((i % 12) + 1)) {
                startMonth = (i % 12) + 1;
            }
            if (activeMonthIds.has(i) && !activeMonthIds.has((i % 12) + 1)) {
                endMonth = i;
            }
        }
    }
    
    const startObj = MONTH_NAMES.find(m => m.id === startMonth);
    const endObj = MONTH_NAMES.find(m => m.id === endMonth);
    
    if (startObj && endObj && startObj.id !== endObj.id) {
        inp.value = startObj.tr + ' - ' + endObj.tr;
    } else if (startObj) {
        inp.value = startObj.tr;
    }
}

function parseAndSelectMonths(text) {
    activeMonthIds.clear();
    if (!text) {
        renderMonthButtons();
        return;
    }
    const lower = text.toLowerCase();
    if (lower.includes('tüm yıl') || lower.includes('all year') || lower.includes('dört mevsim')) {
        for (let i = 1; i <= 12; i++) activeMonthIds.add(i);
        renderMonthButtons();
        return;
    }
    
    let matchedStart = null;
    let matchedEnd = null;
    
    MONTH_NAMES.forEach(m => {
        if (lower.includes(m.tr.toLowerCase())) {
            activeMonthIds.add(m.id);
        }
    });
    
    if (lower.includes('-') || lower.includes('–')) {
        const parts = lower.split(/[-–]/).map(s => s.trim());
        if (parts.length === 2) {
            MONTH_NAMES.forEach(m => {
                if (parts[0].includes(m.tr.toLowerCase())) matchedStart = m.id;
                if (parts[1].includes(m.tr.toLowerCase())) matchedEnd = m.id;
            });
            if (matchedStart && matchedEnd) {
                activeMonthIds.clear();
                let cur = matchedStart;
                while (true) {
                    activeMonthIds.add(cur);
                    if (cur === matchedEnd) break;
                    cur = (cur % 12) + 1;
                }
            }
        }
    }
    renderMonthButtons();
}

// Ana Görsel Önizleme
function previewMainProductImage(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('main_prod_preview');
            if (preview) preview.src = e.target.result;
            const label = document.getElementById('main_img_label');
            if (label) label.textContent = file.name + ' (Yeni Yüklenecek ➔ .webp)';
        };
        reader.readAsDataURL(file);
    }
}

// Kutu Görseli Önizleme
function previewBoxFile(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const target = document.getElementById(previewId);
            if (target) target.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Dinamik Kutu Görseli Önizleme
function previewPkgFile(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const target = document.getElementById(previewId);
            if (target) target.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Kutu Ambalajı Bölümü Aç/Kapa
function togglePackagingSection(show) {
    const container = document.getElementById('packagingItemsContainer');
    if (container) {
        container.style.display = show ? 'flex' : 'none';
    }
}

// Kutu Kartını Sil
function removePackagingBox(btn) {
    const card = btn.closest('.packaging-item-card');
    if (card) {
        card.remove();
        reindexPackagingBoxes();
    }
}

// Kutu Kartlarını Yeniden İndeksle
function reindexPackagingBoxes() {
    const container = document.getElementById('packagingItemsContainer');
    if (!container) return;
    const cards = container.querySelectorAll('.packaging-item-card');
    
    const notice = document.getElementById('emptyPackagingNotice');
    if (cards.length === 0) {
        if (!notice) {
            const emptyDiv = document.createElement('div');
            emptyDiv.id = 'emptyPackagingNotice';
            emptyDiv.style.cssText = 'text-align: center; padding: 30px; background: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: 12px; color: #64748b;';
            emptyDiv.innerHTML = 'Henüz kutu/ambalaj seçeneği eklenmemiş. Yukarıdaki <strong>"Yeni Kutu / Ambalaj Ekle"</strong> butonuna tıklayarak kutu ekleyebilirsiniz.';
            container.appendChild(emptyDiv);
        }
        return;
    } else if (notice) {
        notice.remove();
    }

    cards.forEach((card, idx) => {
        card.setAttribute('data-box-index', idx);
        const titleSpan = card.querySelector('.box-card-title');
        if (titleSpan) {
            titleSpan.textContent = `📦 Kutu / Ambalaj #${idx + 1}`;
        }
        const img = card.querySelector('img');
        if (img) img.id = `pkg_box_preview_${idx}`;

        const titleInp = card.querySelector('input[name*="[title]"]');
        if (titleInp) titleInp.name = `extra[packaging_items][${idx}][title]`;

        const hiddenImgInp = card.querySelector('input[name*="[image]"]');
        if (hiddenImgInp) hiddenImgInp.name = `extra[packaging_items][${idx}][image]`;

        const fileInp = card.querySelector('input[type="file"]');
        if (fileInp) {
            fileInp.name = `pkg_box_file_${idx}`;
            fileInp.setAttribute('onchange', `previewPkgFile(this, 'pkg_box_preview_${idx}')`);
        }

        const weightInp = card.querySelector('input[name*="[weight]"]');
        if (weightInp) weightInp.name = `extra[packaging_items][${idx}][weight]`;

        const dimInp = card.querySelector('input[name*="[dim]"]');
        if (dimInp) dimInp.name = `extra[packaging_items][${idx}][dim]`;
    });
}

// Yeni Kutu / Ambalaj Ekle
function addNewPackagingBox() {
    const container = document.getElementById('packagingItemsContainer');
    if (!container) return;

    // Görünür yap ve kutucuğu işaretle
    const chk = document.getElementById('chk_show_packaging');
    if (chk && !chk.checked) {
        chk.checked = true;
        togglePackagingSection(true);
    }

    const notice = document.getElementById('emptyPackagingNotice');
    if (notice) notice.remove();

    const currentCount = container.querySelectorAll('.packaging-item-card').length;
    const newIdx = currentCount;
    const defaultBoxes = [
        { title: 'Karton Kutu', weight: '15 Kg', dim: '600 x 400 x 160', img: 'packaging_carton_2.png' },
        { title: 'Teleskopik Koli', weight: '15 Kg', dim: '600 x 400 x 180', img: 'packaging_telescopic.png' },
        { title: 'Plastik Kasa', weight: '10 Kg', dim: '400 x 300 x 150', img: 'packaging_carton_1.png' },
        { title: 'File Ambalaj', weight: '2 Kg', dim: 'Perakende Market', img: 'packaging_mesh.png' }
    ];
    const def = defaultBoxes[newIdx % defaultBoxes.length];

    const card = document.createElement('div');
    card.className = 'packaging-item-card';
    card.setAttribute('data-box-index', newIdx);
    card.style.cssText = 'background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 16px; animation: fadeIn 0.2s ease-out;';

    card.innerHTML = `
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
            <span style="font-weight: 800; font-size: 13px; color: #0f172a;" class="box-card-title">
                📦 Kutu / Ambalaj #${newIdx + 1}
            </span>
            <button type="button" onclick="removePackagingBox(this)" class="btn btn-sm btn-danger" style="padding: 4px 10px; font-size: 11.5px; border-radius: 6px; background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; cursor: pointer; font-weight: 700;">
                🗑️ Bu Kutuyu Sil
            </button>
        </div>

        <div style="display: grid; grid-template-columns: 80px 1.5fr 1fr 1.5fr; gap: 14px; align-items: center;">
            <div>
                <img id="pkg_box_preview_${newIdx}" src="<?= BASE_URL ?>/public/images/${def.img}" style="width: 70px; height: 60px; object-fit: contain; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 4px;">
            </div>
            <div>
                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 2px;">Ambalaj Tipi / Başlık</label>
                <input type="text" name="extra[packaging_items][${newIdx}][title]" value="${def.title}" class="form-control" style="font-size: 12.5px;">
                <input type="hidden" name="extra[packaging_items][${newIdx}][image]" value="${def.img}" class="pkg-img-input">
                <input type="file" name="pkg_box_file_${newIdx}" accept="image/*" style="font-size: 11px; margin-top: 4px;" onchange="previewPkgFile(this, 'pkg_box_preview_${newIdx}')">
            </div>
            <div>
                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 2px;">Net Ağırlık</label>
                <input type="text" name="extra[packaging_items][${newIdx}][weight]" value="${def.weight}" class="form-control" style="font-size: 12.5px;">
            </div>
            <div>
                <label style="font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 2px;">Ölçüler / Kullanım Alanı</label>
                <input type="text" name="extra[packaging_items][${newIdx}][dim]" value="${def.dim}" class="form-control" style="font-size: 12.5px;">
            </div>
        </div>
    `;

    container.appendChild(card);
    reindexPackagingBoxes();
}

// Cins İsmi Yazıldığında Başlığı Güncelle
function updateVarietyBadge(input) {
    const card = input.closest('.variety-item-card');
    if (!card) return;
    const preview = card.querySelector('.variety-title-preview');
    if (preview) {
        preview.textContent = input.value.trim() ? input.value : 'İsimsiz Cins';
    }
}

// Cins Kartlarını Yeniden İndeksle
function reindexVarieties() {
    const container = document.getElementById('varietiesContainer');
    if (!container) return;
    const cards = container.querySelectorAll('.variety-item-card');
    cards.forEach((card, idx) => {
        const badge = card.querySelector('.variety-item-badge');
        if (badge) badge.textContent = `#${idx + 1}. Cins / Alt Çeşit`;

        // Tüm input ve textarea name alanlarını idx ile güncelle
        const inputs = card.querySelectorAll('input, textarea');
        inputs.forEach(el => {
            const currentName = el.getAttribute('name');
            if (currentName && currentName.includes('extra[varieties]')) {
                const newName = currentName.replace(/extra\[varieties\]\[\d+\]/, `extra[varieties][${idx}]`);
                el.setAttribute('name', newName);
            }
        });
    });
}

// Yeni Cins Ekle
function addNewVarietyCard() {
    const container = document.getElementById('varietiesContainer');
    if (!container) return;
    const idx = container.querySelectorAll('.variety-item-card').length;
    const card = document.createElement('div');
    card.className = 'variety-item-card';
    card.style.cssText = 'background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 14px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);';
    card.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span class="variety-item-badge" style="background: #f0fdf4; color: #166534; font-weight: 800; font-size: 12.5px; padding: 4px 10px; border-radius: 8px; border: 1px solid #bbf7d0;">
                    #${idx + 1}. Cins / Alt Çeşit
                </span>
                <span class="variety-title-preview" style="font-weight: 700; font-size: 14px; color: #1e293b;">
                    Yeni Cins
                </span>
            </div>
            <button type="button" onclick="this.closest('.variety-item-card').remove(); reindexVarieties();" class="btn btn-sm btn-danger" style="padding: 4px 12px; font-size: 12px; border-radius: 8px; font-weight: 700;" title="Bu Cinsi Kaldır">
                🗑️ Cinsi Sil
            </button>
        </div>

        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 14px; margin-bottom: 12px;">
            <div>
                <label class="form-label" style="font-size: 12px; font-weight: 700; margin-bottom: 4px;">Cins / Çeşit Adı *</label>
                <input type="text" name="extra[varieties][${idx}][name]" value="" class="form-control variety-name-input" style="font-weight: 700; font-size: 13.5px;" required oninput="updateVarietyBadge(this)">
            </div>
            <div>
                <label class="form-label" style="font-size: 12px; font-weight: 700; margin-bottom: 4px;">Hasat Sezonu Ayları</label>
                <input type="text" name="extra[varieties][${idx}][months]" value="" class="form-control" style="font-size: 13px;">
            </div>
        </div>

        <div style="margin-bottom: 12px;">
            <label class="form-label" style="font-size: 12px; font-weight: 700; margin-bottom: 4px;">Cins Açıklaması & Kalite Notları</label>
            <textarea name="extra[varieties][${idx}][desc]" rows="2" class="form-control" style="font-size: 12.5px;"></textarea>
        </div>

        <div style="margin-bottom: 14px;">
            <label class="form-label" style="font-size: 12px; font-weight: 700; margin-bottom: 4px;">Öne Çıkan Karakteristik / Kalite Özelliği</label>
            <input type="text" name="extra[varieties][${idx}][char_note]" value="" class="form-control" style="font-size: 12.5px;">
        </div>

        <details style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px;">
            <summary style="font-size: 12.5px; font-weight: 700; color: #15803d; cursor: pointer; user-select: none;">
                ⚙️ Bu Cinse Özel Besin Değerleri ve Karakteristikleri Özelleştir (Opsiyonel)
            </summary>
            <div style="margin-top: 14px; padding-top: 12px; border-top: 1px dashed #cbd5e1;">
                <div style="margin-bottom: 14px;">
                    <div style="font-size: 12px; font-weight: 800; color: #334155; margin-bottom: 8px;">🍽️ Besin Değerleri (100g için)</div>
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;">
                        <div>
                            <label style="font-size: 11px; font-weight: 700; color: #64748b;">Kalori (kcal)</label>
                            <input type="text" name="extra[varieties][${idx}][nutrition][kcal]" value="" class="form-control" style="font-size: 12px;">
                        </div>
                        <div>
                            <label style="font-size: 11px; font-weight: 700; color: #64748b;">Protein</label>
                            <input type="text" name="extra[varieties][${idx}][nutrition][protein]" value="" class="form-control" style="font-size: 12px;">
                        </div>
                        <div>
                            <label style="font-size: 11px; font-weight: 700; color: #64748b;">Karbonhidrat</label>
                            <input type="text" name="extra[varieties][${idx}][nutrition][carb]" value="" class="form-control" style="font-size: 12px;">
                        </div>
                        <div>
                            <label style="font-size: 11px; font-weight: 700; color: #64748b;">Yağ</label>
                            <input type="text" name="extra[varieties][${idx}][nutrition][fat]" value="" class="form-control" style="font-size: 12px;">
                        </div>
                    </div>
                </div>

                <div>
                    <div style="font-size: 12px; font-weight: 800; color: #334155; margin-bottom: 8px;">🌿 Karakteristik Özellikleri (Tablo Tıklanınca Sağda Gözükür)</div>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 10px;">
                        <div>
                            <label style="font-size: 11px; font-weight: 700; color: #64748b;">Et / Tane Rengi</label>
                            <input type="text" name="extra[varieties][${idx}][char][et]" value="" class="form-control" style="font-size: 12px;">
                        </div>
                        <div>
                            <label style="font-size: 11px; font-weight: 700; color: #64748b;">Çekirdek / Sertlik</label>
                            <input type="text" name="extra[varieties][${idx}][char][cekirdek]" value="" class="form-control" style="font-size: 12px;">
                        </div>
                        <div>
                            <label style="font-size: 11px; font-weight: 700; color: #64748b;">Kabuk Rengi / Kepek</label>
                            <input type="text" name="extra[varieties][${idx}][char][kabuk]" value="" class="form-control" style="font-size: 12px;">
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                        <div>
                            <label style="font-size: 11px; font-weight: 700; color: #64748b;">Dokusu & Verim</label>
                            <input type="text" name="extra[varieties][${idx}][char][doku]" value="" class="form-control" style="font-size: 12px;">
                        </div>
                        <div>
                            <label style="font-size: 11px; font-weight: 700; color: #64748b;">Tane / Meyve Şekli</label>
                            <input type="text" name="extra[varieties][${idx}][char][sekil]" value="" class="form-control" style="font-size: 12px;">
                        </div>
                        <div>
                            <label style="font-size: 11px; font-weight: 700; color: #64748b;">Tadı & Aroma</label>
                            <input type="text" name="extra[varieties][${idx}][char][tat]" value="" class="form-control" style="font-size: 12px;">
                        </div>
                    </div>
                </div>
            </div>
        </details>
    `;
    container.appendChild(card);
    card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    const nameInput = card.querySelector('.variety-name-input');
    if (nameInput) nameInput.focus();
}

// Form Gönderme (stay veya list)
function submitProductForm(target) {
    const form = document.getElementById('productMainForm');
    if (!form) return;
    
    // Doğrulama kontrolü
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const redirectField = document.getElementById('redirect_to_field');
    if (redirectField) {
        redirectField.value = target;
    }
    form.submit();
}

// Kategori Modal Fonksiyonları
function openNewCategoryModal() {
    document.getElementById('cat_id').value = '';
    document.getElementById('cat_name_tr').value = '';
    document.getElementById('cat_slug').value = '';
    document.getElementById('cat_badge_color').value = '#15803d';
    document.getElementById('cat_badge_color_picker').value = '#15803d';
    document.getElementById('cat_sort_order').value = '1';
    document.getElementById('cat_is_active').checked = true;

    document.getElementById('categoryModalTitle').textContent = '📁 + Yeni Kategori Ekle';
    document.getElementById('catSubmitBtn').textContent = '💾 Kategoriyi Ekle';
    openModal('categoryModal');
}

function openEditCategoryModal(c) {
    document.getElementById('cat_id').value = c.id;
    document.getElementById('cat_name_tr').value = c.name_tr || '';
    document.getElementById('cat_slug').value = c.slug || '';
    const color = c.badge_color || '#15803d';
    document.getElementById('cat_badge_color').value = color;
    document.getElementById('cat_badge_color_picker').value = color;
    document.getElementById('cat_sort_order').value = c.sort_order || 0;
    document.getElementById('cat_is_active').checked = parseInt(c.is_active) === 1;

    document.getElementById('categoryModalTitle').textContent = '✏️ Kategoriyi Düzenle: ' + (c.name_tr || '');
    document.getElementById('catSubmitBtn').textContent = '💾 Değişiklikleri Güncelle';
    openModal('categoryModal');
}

// Sayfa Yüklendiğinde Ay Seçiciyi Başlat
document.addEventListener('DOMContentLoaded', () => {
    initHarvestMonths();
});
</script>
