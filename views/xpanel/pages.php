<?php
/**
 * BİKGRUP - BİK GRUP Yönetim Paneli Sayfa ve Alan Yönetimi
 * Tüm sayfalar ve görsel/metin alanları eksiksiz & Saf Türkçe
 */

declare(strict_types=1);

$activePage = $_GET['page'] ?? 'home';

// Bölümleri anahtara göre haritala ve JSON alanlarını otomatik çöz
$sectionsBySection = [];
foreach ($sections as $s) {
    if (!isset($s['extra'])) {
        $s['extra'] = !empty($s['extra_json']) ? (json_decode($s['extra_json'], true) ?: []) : [];
    }
    $sectionsBySection[$s['section_key']] = $s;
}

if (!function_exists('getSec')) {
    function getSec($key, $sectionsBySection) {
        $item = $sectionsBySection[$key] ?? [];
        if (!isset($item['extra'])) {
            $item['extra'] = !empty($item['extra_json']) ? (json_decode($item['extra_json'], true) ?: []) : [];
        }
        return $item;
    }
}

$categories = $categories ?? Category::all(true);
$allProducts = $allProducts ?? Product::all(true);

if (!function_exists('renderLinkSelector')) {
    function renderLinkSelector(string $name, string $currentValue, array $categories, string $label = 'Buton Linki', string $fontSize = '12px', ?array $allProductsList = null): void {
        global $allProducts;
        $productsList = $allProductsList ?? ($allProducts ?? Product::all(true));

        $presetGroups = [
            '🏢 Kurumsal & Alt Bölümler' => [
                '/kurumsal'              => 'Kurumsal (Genel Sayfa)',
                '/kurumsal#tarihce'      => '↳ Tarihçemiz & Yarım Asırlık Tecrübe',
                '/kurumsal#misyon'       => '↳ Misyonumuz & Vizyonumuz',
                '/kurumsal#sertifikalar' => '↳ Kalite & Gıda Güvenliği Sertifikalarımız',
                '/markalar'              => 'Markalarımız (Genel Vitrin)',
                '/blog'                  => 'Haberler',
                '/iletisim'              => 'İletişim & Lokasyon',
            ],
            '🏷️ Grup Markalarımız' => [
                '/markalar/koylu-tarim'   => '↳ Köylü Tarım (/markalar/koylu-tarim)',
                '/markalar/zeliha-sultan' => '↳ Zeliha Sultan Çiftliği (/markalar/zeliha-sultan)',
                '/markalar/inci-fresh'    => '↳ İnci Fresh (/markalar/inci-fresh)',
                '/markalar/lara-citrus'   => '↳ Lara Citrus (/markalar/lara-citrus)',
            ]
        ];

        // Kategoriler ve Altındaki Tüm Ürünler
        foreach ($categories as $cat) {
            $catSlug = $cat['slug'] ?? '';
            $catName = $cat['name_tr'] ?? $cat['name_en'] ?? $catSlug;
            $catId = (int)($cat['id'] ?? 0);
            
            $catUrl = '/urunler?kategori=' . urlencode($catSlug);
            $groupKey = '📂 ' . $catName . ' (Kategori & Ürünleri)';
            $presetGroups[$groupKey] = [
                $catUrl => "Tüm {$catName} Ürünleri ({$catUrl})"
            ];

            // Bu kategoriye ait ürünleri alt ürün olarak ekle
            if (!empty($productsList)) {
                foreach ($productsList as $p) {
                    if ((int)($p['category_id'] ?? 0) === $catId) {
                        $pSlug = $p['slug'] ?? '';
                        $pUrl = '/urunler/' . urlencode($pSlug);
                        $presetGroups[$groupKey][$pUrl] = "↳ " . ($p['name_tr'] ?? '') . " ({$pUrl})";
                    }
                }
            }
        }

        // Genel / Diğer
        $presetGroups['📄 Diğer & Politikalar'] = [
            '/urunler'             => 'Tüm Ürünler Kataloğu (/urunler)',
            '/cerez-politikasi'    => 'Çerez Politikası (/cerez-politikasi)',
            '/kvkk'                => 'KVKK Aydınlatma Metni (/kvkk)',
            '/gizlilik-politikasi' => 'Gizlilik Politikası (/gizlilik-politikasi)'
        ];
        ?>
        <div style="margin-top: 4px;">
            <label class="form-label" style="font-size: <?= $fontSize ?>; margin-bottom: 3px; font-weight: 700; color: #475569;"><?= htmlspecialchars($label) ?></label>
            <div class="panel-link-selector-row" style="display: flex; gap: 6px; align-items: center;">
                <select class="form-control" style="font-size: <?= $fontSize ?>; background: #ffffff; border-color: #cbd5e1; cursor: pointer; flex: 1.2; padding: 6px 8px;" onchange="const inp = this.parentElement.querySelector('input'); if(this.value){ inp.value = this.value; }">
                    <option value="">📂 Kategori / Ürün / Sayfa Seç</option>
                    <?php foreach ($presetGroups as $groupTitle => $opts): ?>
                        <optgroup label="<?= htmlspecialchars($groupTitle) ?>">
                            <?php foreach ($opts as $url => $title): ?>
                                <option value="<?= htmlspecialchars($url) ?>" <?= ($currentValue === $url) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($title) ?>
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="<?= htmlspecialchars($name) ?>" value="<?= htmlspecialchars($currentValue) ?>" class="form-control" placeholder="/ornek-link" style="font-size: <?= $fontSize ?>; flex: 1; font-family: monospace; padding: 6px 10px;">
            </div>
        </div>
        <?php
    }
}
?>

<div class="panel-card" style="margin-bottom: 24px;">
    <div class="panel-card-header">
        <div>
            <h2 class="panel-card-title">📄 Sayfa ve Alan Yönetimi</h2>
            <p style="color: var(--panel-text-muted); font-size: 14px; margin-top: 4px;">
                Düzenlemek istediğiniz sayfayı seçin. Sitenin tüm sayfalarındaki afiş görsellerini, başlıkları, butonları ve metin içeriklerini buradan eksiksiz yönetebilirsiniz.
            </p>
        </div>
    </div>

    <!-- SAYFA SEÇİM SEKMELERİ (TABS) -->
    <div style="display: flex; gap: 8px; flex-wrap: wrap; border-bottom: 2px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 24px;">
        <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/pages?page=home" 
           style="padding: 10px 18px; border-radius: 12px; font-weight: 700; font-size: 13.5px; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.2s; <?= $activePage === 'home' ? 'background: #15803d; color: #ffffff; box-shadow: 0 4px 12px rgba(21,128,61,0.25);' : 'background: #f1f5f9; color: #475569;' ?>">
            <span>🏠</span> Anasayfa Yönetimi
        </a>

        <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/pages?page=about" 
           style="padding: 10px 18px; border-radius: 12px; font-weight: 700; font-size: 13.5px; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.2s; <?= $activePage === 'about' ? 'background: #15803d; color: #ffffff; box-shadow: 0 4px 12px rgba(21,128,61,0.25);' : 'background: #f1f5f9; color: #475569;' ?>">
            <span>🏢</span> Kurumsal / Hakkımızda
        </a>

        <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/pages?page=contact" 
           style="padding: 10px 18px; border-radius: 12px; font-weight: 700; font-size: 13.5px; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.2s; <?= $activePage === 'contact' ? 'background: #15803d; color: #ffffff; box-shadow: 0 4px 12px rgba(21,128,61,0.25);' : 'background: #f1f5f9; color: #475569;' ?>">
            <span>📞</span> İletişim Sayfası
        </a>

        <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/pages?page=policy" 
           style="padding: 10px 18px; border-radius: 12px; font-weight: 700; font-size: 13.5px; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.2s; <?= $activePage === 'policy' ? 'background: #15803d; color: #ffffff; box-shadow: 0 4px 12px rgba(21,128,61,0.25);' : 'background: #f1f5f9; color: #475569;' ?>">
            <span>⚖️</span> Politikalar (KVKK & Gizlilik)
        </a>
    </div>

    <!-- ==================================================================== -->
    <!-- 1. ANASAYFA YÖNETİMİ (6 BÖLÜM EKSİKSİZ)                               -->
    <!-- ==================================================================== -->
    <?php if ($activePage === 'home'): ?>
        
        <?php 
        $secHero = getSec('home_hero', $sectionsBySection);
        $heroExtra = $secHero['extra'] ?? [];
        ?>
        <!-- BÖLÜM 1: HERO GİRİŞ ALANI & GÖRSELİ & 3 STRATEJİK KART -->
        <div style="background: #ffffff; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px; margin-bottom: 28px;">
            <div style="border-bottom: 2px solid #f1f5f9; padding-bottom: 14px; margin-bottom: 20px;">
                <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <span>🌄</span> 1. Hero Giriş Alanı (Arka Plan Görseli, 3 Stratejik Kart & Büyük Başlık)
                </h3>
            </div>

            <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-page" method="POST" enctype="multipart/form-data">
                <?= Security::csrfField() ?>
                <input type="hidden" name="section_key" value="home_hero">
                <input type="hidden" name="page_key" value="home">

                <!-- Arka Plan Görseli -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 20px;">
                    <label class="form-label" style="font-weight: 700; color: #0f172a;">Giriş Ekranı Arka Plan Afiş Görseli</label>
                    <div class="panel-image-upload-row" style="display: flex; align-items: center; gap: 20px; margin-top: 10px;">
                        <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($heroExtra['bg_image'] ?? 'nature_hero_bg.jpg') ?>" 
                             alt="Hero Arka Plan" style="width: 160px; height: 90px; object-fit: cover; border-radius: 8px; border: 2px solid #cbd5e1;">
                        <div>
                            <span style="font-size: 13px; color: #475569; display: block; margin-bottom: 6px;">Mevcut Görsel: <strong><?= htmlspecialchars($heroExtra['bg_image'] ?? 'nature_hero_bg.jpg') ?></strong></span>
                            <input type="file" name="bg_image" accept="image/*" class="form-control" style="font-size: 13px;">
                            <input type="hidden" name="extra[bg_image]" value="<?= htmlspecialchars($heroExtra['bg_image'] ?? 'nature_hero_bg.jpg') ?>">
                        </div>
                    </div>
                </div>

                <!-- 3 Stratejik Kart -->
                <h4 style="font-size: 15px; font-weight: 800; color: #15803d; margin: 18px 0 12px;">Sol/Orta 3 Stratejik Kart</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-bottom: 22px;">
                    <!-- Kart 1: Üretim -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                        <span style="font-size: 12px; font-weight: 800; color: #ea580c; text-transform: uppercase;">1. Kart (Beyaz Kart)</span>
                        <div class="form-group" style="margin-top: 8px;">
                            <label class="form-label" style="font-size: 12px;">Kart Başlığı</label>
                            <input type="text" name="extra[card1_title]" value="<?= htmlspecialchars($heroExtra['card1_title'] ?? 'Üretim') ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-size: 12px;">Açıklama Metni</label>
                            <textarea name="extra[card1_desc]" rows="2" class="form-control" style="font-size: 12.5px;"><?= htmlspecialchars($heroExtra['card1_desc'] ?? 'Kendi arazilerimizde iyi tarım uygulamalarıyla sürdürülebilir üretim yapıyoruz.') ?></textarea>
                        </div>
                        <div class="form-group" style="margin-top: 8px;">
                            <label class="form-label" style="font-size: 11px;">Buton Yazısı</label>
                            <input type="text" name="extra[card1_btn]" value="<?= htmlspecialchars($heroExtra['card1_btn'] ?? 'KEŞFET') ?>" class="form-control" style="font-size: 12px;">
                        </div>
                        <div class="form-group" style="margin-top: 8px;">
                            <?php renderLinkSelector('extra[card1_link]', $heroExtra['card1_link'] ?? '/kurumsal', $categories, 'Buton Linki', '11px'); ?>
                        </div>
                    </div>

                    <!-- Kart 2: Tedarik & Ticaret -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                        <span style="font-size: 12px; font-weight: 800; color: #ea580c; text-transform: uppercase;">2. Kart (Şeffaf Sütun)</span>
                        <div class="form-group" style="margin-top: 8px;">
                            <label class="form-label" style="font-size: 12px;">Kart Başlığı</label>
                            <input type="text" name="extra[card2_title]" value="<?= htmlspecialchars($heroExtra['card2_title'] ?? 'Tedarik & Ticaret') ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-size: 12px;">Açıklama Metni</label>
                            <textarea name="extra[card2_desc]" rows="2" class="form-control" style="font-size: 12.5px;"><?= htmlspecialchars($heroExtra['card2_desc'] ?? 'Geniş üretici ağımızla yüksek tonajlı, kesintisiz tarımsal hammadde tedariği.') ?></textarea>
                        </div>
                        <div class="form-group" style="margin-top: 8px;">
                            <label class="form-label" style="font-size: 11px;">Buton Yazısı</label>
                            <input type="text" name="extra[card2_btn]" value="<?= htmlspecialchars($heroExtra['card2_btn'] ?? 'İNCELE') ?>" class="form-control" style="font-size: 12px;">
                        </div>
                        <div class="form-group" style="margin-top: 8px;">
                            <?php renderLinkSelector('extra[card2_link]', $heroExtra['card2_link'] ?? '/kurumsal', $categories, 'Buton Linki', '11px'); ?>
                        </div>
                    </div>

                    <!-- Kart 3: Global İhracat -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                        <span style="font-size: 12px; font-weight: 800; color: #ea580c; text-transform: uppercase;">3. Kart (Şeffaf Sütun)</span>
                        <div class="form-group" style="margin-top: 8px;">
                            <label class="form-label" style="font-size: 12px;">Kart Başlığı</label>
                            <input type="text" name="extra[card3_title]" value="<?= htmlspecialchars($heroExtra['card3_title'] ?? 'Global İhracat') ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-size: 12px;">Açıklama Metni</label>
                            <textarea name="extra[card3_desc]" rows="2" class="form-control" style="font-size: 12.5px;"><?= htmlspecialchars($heroExtra['card3_desc'] ?? 'Akdeniz\'den dünya pazarlarına soğuk zincir güvenliğiyle doğrudan ihracat.') ?></textarea>
                        </div>
                        <div class="form-group" style="margin-top: 8px;">
                            <label class="form-label" style="font-size: 11px;">Buton Yazısı</label>
                            <input type="text" name="extra[card3_btn]" value="<?= htmlspecialchars($heroExtra['card3_btn'] ?? 'GÖRÜNTÜLE') ?>" class="form-control" style="font-size: 12px;">
                        </div>
                        <div class="form-group" style="margin-top: 8px;">
                            <?php renderLinkSelector('extra[card3_link]', $heroExtra['card3_link'] ?? '/urunler', $categories, 'Buton Linki', '11px'); ?>
                        </div>
                    </div>
                </div>

                <!-- Sağ Taraf Büyük Tipografi & Buton -->
                <h4 style="font-size: 15px; font-weight: 800; color: #15803d; margin: 18px 0 12px;">Sağ Taraf Büyük Başlık & Buton</h4>
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label">Hero Mega Başlık</label>
                        <input type="text" name="title_tr" value="<?= htmlspecialchars($secHero['title_tr'] ?? 'Topraktan Geleceğe Güvenle.') ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Üst Küçük Slogan</label>
                        <input type="text" name="subtitle_tr" value="<?= htmlspecialchars($secHero['subtitle_tr'] ?? 'BİK GRUP | Tarım, Tedarik & İhracat') ?>" class="form-control">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1.3fr; gap: 16px; margin-top: 10px;">
                    <div class="form-group">
                        <label class="form-label">Açıklama Metni</label>
                        <textarea name="body_tr" rows="2" class="form-control"><?= htmlspecialchars($secHero['body_tr'] ?? 'Akdeniz\'in bereketli topraklarından dünyaya uzanan güvenilir tarım zinciri.') ?></textarea>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 10px;">
                        <div class="form-group">
                            <label class="form-label">Keşfet Buton Yazısı</label>
                            <input type="text" name="extra[hero_btn_text]" value="<?= htmlspecialchars($heroExtra['hero_btn_text'] ?? 'Kurumsal Keşfet') ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <?php renderLinkSelector('extra[hero_btn_link]', $heroExtra['hero_btn_link'] ?? '/kurumsal', $categories, 'Keşfet Buton Linki'); ?>
                        </div>
                    </div>
                </div>

                <div style="text-align: right; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">💾 Hero Giriş Alanını Kaydet</button>
                </div>
            </form>
        </div>

        <?php 
        $secFeat = getSec('home_featured', $sectionsBySection);
        $featExtra = $secFeat['extra'] ?? [];
        ?>
        <!-- BÖLÜM 2: ÖNE ÇIKAN ÜRÜNLER VİTRİNİ -->
        <div style="background: #ffffff; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px; margin-bottom: 28px;">
            <div style="border-bottom: 2px solid #f1f5f9; padding-bottom: 14px; margin-bottom: 20px;">
                <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <span>🍊</span> 2. Öne Çıkan Ürünler Vitrini Başlıkları
                </h3>
            </div>

            <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-page" method="POST">
                <?= Security::csrfField() ?>
                <input type="hidden" name="section_key" value="home_featured">
                <input type="hidden" name="page_key" value="home">

                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-bottom: 16px;">
                    <div class="form-group">
                        <label class="form-label">Üst Etiket (Rozet)</label>
                        <input type="text" name="subtitle_tr" value="<?= htmlspecialchars($secFeat['subtitle_tr'] ?? 'İHRACAT VE TEDARİK') ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bölüm Ana Başlığı</label>
                        <input type="text" name="title_tr" value="<?= htmlspecialchars($secFeat['title_tr'] ?? 'Öne Çıkan Ürünlerimiz') ?>" class="form-control">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label">Açıklama Paragrafı</label>
                    <textarea name="body_tr" rows="2" class="form-control"><?= htmlspecialchars($secFeat['body_tr'] ?? 'Doğadan sofranıza uzanan lezzet ve kalite yolculuğu.') ?></textarea>
                </div>

                <!-- 4 Öne Çıkan Ürün Seçimi (Açılır Liste) -->
                <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 20px;">
                    <h4 style="font-size: 14px; font-weight: 800; color: #15803d; margin: 0 0 6px 0; display: flex; align-items: center; gap: 8px;">
                        <span>🛒</span> Vitrinde Sergilenecek 4 Ürün Seçimi
                    </h4>
                    <p style="font-size: 12.5px; color: #64748b; margin-top: 0; margin-bottom: 14px;">
                        Ana sayfadaki 4 büyük vitrin kartında hangi ürünlerin yer alacağını açılır listeden kolayca belirleyin.
                    </p>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px;">
                        <?php 
                        $defaultPIds = [1 => 9, 2 => 10, 3 => 11, 4 => 12]; // Buğday, Mısır, Ayçekirdeği, Soya
                        for ($slot = 1; $slot <= 4; $slot++):
                            $selectedId = (int)($featExtra['product_id_' . $slot] ?? $defaultPIds[$slot]);
                        ?>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size: 12px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                                    <?= $slot ?>. Kart Ürünü
                                </label>
                                <select name="extra[product_id_<?= $slot ?>]" class="form-control" style="font-size: 12.5px; background: #ffffff; cursor: pointer;">
                                    <option value="">-- Ürün Seçiniz --</option>
                                    <?php foreach ($allProducts as $p): ?>
                                        <option value="<?= $p['id'] ?>" <?= ($selectedId === (int)$p['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($p['name_tr']) ?> (<?= htmlspecialchars($p['category_name_tr'] ?? 'Genel') ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <!-- Vitrin Alt Butonu (Tüm Ürünleri İncele) -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 16px;">
                    <h4 style="font-size: 13.5px; font-weight: 800; color: #334155; margin: 0 0 12px 0;">
                        Vitrin Altı Aksiyon Butonu
                    </h4>
                    <div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 16px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Buton Yazısı</label>
                            <input type="text" name="extra[all_products_text]" value="<?= htmlspecialchars($featExtra['all_products_text'] ?? ($featExtra['btn_text'] ?? 'Tüm Ürünleri İncele')) ?>" class="form-control" placeholder="Tüm Ürünleri İncele">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <?php renderLinkSelector('extra[all_products_link]', $featExtra['all_products_link'] ?? ($featExtra['btn_link'] ?? '/urunler'), $categories, 'Buton Linki (Kategori / Sayfa)'); ?>
                        </div>
                    </div>
                </div>

                <div style="text-align: right; margin-top: 16px;">
                    <button type="submit" class="btn btn-primary">💾 Ürün Vitrini Alanını Kaydet</button>
                </div>
            </form>
        </div>

        <?php 
        $secMap = getSec('home_map', $sectionsBySection);
        $mapExtra = $secMap['extra'] ?? [];
        ?>
        <!-- BÖLÜM 3: DÜNYA İHRACAT HARİTASI & 3 YÜZEN KAPSÜL -->
        <div style="background: #ffffff; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px; margin-bottom: 28px;">
            <div style="border-bottom: 2px solid #f1f5f9; padding-bottom: 14px; margin-bottom: 20px;">
                <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <span>🗺️</span> 3. Dünya İhracat Haritası & 3 Yüzen Kapsül
                </h3>
            </div>

            <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-page" method="POST">
                <?= Security::csrfField() ?>
                <input type="hidden" name="section_key" value="home_map">
                <input type="hidden" name="page_key" value="home">

                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-bottom: 16px;">
                    <div class="form-group">
                        <label class="form-label">Üst Etiket</label>
                        <input type="text" name="subtitle_tr" value="<?= htmlspecialchars($secMap['subtitle_tr'] ?? 'KÜRESEL İHRACAT & GÜVENİLİR LOJİSTİK') ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harita Başlığı</label>
                        <input type="text" name="title_tr" value="<?= htmlspecialchars($secMap['title_tr'] ?? 'Akdeniz\'den Dünyaya Ulaşan Kesintisiz İhracat Ağı') ?>" class="form-control">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label">Açıklama Metni</label>
                    <textarea name="body_tr" rows="2" class="form-control"><?= htmlspecialchars($secMap['body_tr'] ?? 'Türkiye\'nin en bereketli bahçelerinden 4 kıtada 30\'u aşkın ülkenin sofrasına taze lezzet köprüsü kuruyoruz.') ?></textarea>
                </div>

                <h4 style="font-size: 14px; font-weight: 800; color: #15803d; margin: 16px 0 10px;">Harita Üzerindeki 3 Yüzen Kapsül</h4>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
                    <!-- 1. Kapsül -->
                    <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                        <span style="font-size: 12px; font-weight: 800; color: #15803d; text-transform: uppercase;">1. Kapsül (Avrupa & Merkez)</span>
                        <div class="form-group" style="margin-top: 8px;">
                            <label class="form-label" style="font-size: 12px;">Ana Başlık / Rakam</label>
                            <input type="text" name="extra[capsule1_title]" value="<?= htmlspecialchars($mapExtra['capsule1_title'] ?? ($mapExtra['capsule1'] ?? '30+ Ülkeye İhracat')) ?>" class="form-control" style="font-size: 13px; font-weight: 700;">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 12px;">Alt Açıklama Metni</label>
                            <input type="text" name="extra[capsule1_sub]" value="<?= htmlspecialchars($mapExtra['capsule1_sub'] ?? 'Avrupa, Rusya & İskandinavya') ?>" class="form-control" style="font-size: 12.5px;">
                        </div>
                    </div>

                    <!-- 2. Kapsül -->
                    <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                        <span style="font-size: 12px; font-weight: 800; color: #ea580c; text-transform: uppercase;">2. Kapsül (Kapasite & Lojistik)</span>
                        <div class="form-group" style="margin-top: 8px;">
                            <label class="form-label" style="font-size: 12px;">Ana Başlık / Rakam</label>
                            <input type="text" name="extra[capsule2_title]" value="<?= htmlspecialchars($mapExtra['capsule2_title'] ?? ($mapExtra['capsule2'] ?? '150.000+ Ton Kapasite')) ?>" class="form-control" style="font-size: 13px; font-weight: 700;">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 12px;">Alt Açıklama Metni</label>
                            <input type="text" name="extra[capsule2_sub]" value="<?= htmlspecialchars($mapExtra['capsule2_sub'] ?? 'Modern Soğuk Hava Depoları') ?>" class="form-control" style="font-size: 12.5px;">
                        </div>
                    </div>

                    <!-- 3. Kapsül -->
                    <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                        <span style="font-size: 12px; font-weight: 800; color: #059669; text-transform: uppercase;">3. Kapsül (Kalite & Güvence)</span>
                        <div class="form-group" style="margin-top: 8px;">
                            <label class="form-label" style="font-size: 12px;">Ana Başlık / Rakam</label>
                            <input type="text" name="extra[capsule3_title]" value="<?= htmlspecialchars($mapExtra['capsule3_title'] ?? ($mapExtra['capsule3'] ?? '%100 Taze & İzlenebilir')) ?>" class="form-control" style="font-size: 13px; font-weight: 700;">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 12px;">Alt Açıklama Metni</label>
                            <input type="text" name="extra[capsule3_sub]" value="<?= htmlspecialchars($mapExtra['capsule3_sub'] ?? 'GlobalG.A.P. & BRCGS Sertifikalı') ?>" class="form-control" style="font-size: 12.5px;">
                        </div>
                    </div>
                </div>

                <div style="text-align: right; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">💾 Harita Alanını Kaydet</button>
                </div>
            </form>
        </div>

        <?php 
        $secBrands = getSec('home_brands', $sectionsBySection);
        $brandsExtra = $secBrands['extra'] ?? [];
        ?>
        <!-- BÖLÜM 4: GRUP ŞİRKETLERİMİZ & MARKALARIMIZ (4 MARKA GÖRSELLERİ VE DETAYLARI) -->
        <div style="background: #ffffff; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px; margin-bottom: 28px;">
            <div style="border-bottom: 2px solid #f1f5f9; padding-bottom: 14px; margin-bottom: 20px;">
                <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <span>🏢</span> 4. Grup Şirketlerimiz & Markalarımız (Ana Sayfa & Kurumsal Ortak Vitrin)
                </h3>
                <p style="font-size: 13px; color: #64748b; margin: 6px 0 0 0;">
                    Buradan belirleyeceğiniz 4 marka kartı (Görseller, İsimler ve Tıklama Bağlantıları), hem <strong>Ana Sayfa</strong>'da hem de <strong>Kurumsal</strong> sayfasında tek bir merkezden anında güncellenir.
                </p>
            </div>

            <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-page" method="POST" enctype="multipart/form-data">
                <?= Security::csrfField() ?>
                <input type="hidden" name="section_key" value="home_brands">
                <input type="hidden" name="page_key" value="home">

                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-bottom: 16px;">
                    <div class="form-group">
                        <label class="form-label">Üst Etiket</label>
                        <input type="text" name="subtitle_tr" value="<?= htmlspecialchars($secBrands['subtitle_tr'] ?? 'GRUP ŞİRKETLERİMİZ & MARKALARIMIZ') ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bölüm Başlığı</label>
                        <input type="text" name="title_tr" value="<?= htmlspecialchars($secBrands['title_tr'] ?? 'Grup Markalarımız') ?>" class="form-control">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label">Açıklama Metni</label>
                    <textarea name="body_tr" rows="2" class="form-control"><?= htmlspecialchars($secBrands['body_tr'] ?? 'BİK GRUP çatısı altında üretimden tedariğe, ticaretten pazarlamaya kadar tarımın farklı alanlarında faaliyet gösteren markalarımız.') ?></textarea>
                </div>

                <!-- 4 MARKA KARTI DÜZENLEME -->
                <h4 style="font-size: 15px; font-weight: 800; color: #15803d; margin: 18px 0 14px;">4 Marka Kartı Yönetimi</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px;">
                    
                    <!-- 1. Köylü Tarım -->
                    <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                        <span style="font-weight: 800; font-size: 13px; color: #ea580c; text-transform: uppercase;">1. Marka</span>
                        
                        <div class="panel-image-upload-row" style="display: flex; align-items: center; gap: 14px; margin-top: 10px; margin-bottom: 12px;">
                            <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($brandsExtra['brand1_image'] ?? 'fresh_vegetables.jpg') ?>" 
                                 alt="Marka 1" style="width: 80px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1;">
                            <div style="flex: 1;">
                                <label class="form-label" style="font-size: 12px;">Görsel Yükle</label>
                                <input type="file" name="brand1_image" accept="image/*" class="form-control" style="font-size: 12px;">
                                <input type="hidden" name="extra[brand1_image]" value="<?= htmlspecialchars($brandsExtra['brand1_image'] ?? 'fresh_vegetables.jpg') ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" style="font-size: 12px;">Marka Adı</label>
                            <input type="text" name="extra[brand1_name]" value="<?= htmlspecialchars($brandsExtra['brand1_name'] ?? 'KÖYLÜ TARIM') ?>" class="form-control">
                        </div>
                        <div class="form-group" style="margin-top: 12px; margin-bottom: 0;">
                            <?php renderLinkSelector('extra[brand1_link]', $brandsExtra['brand1_link'] ?? '/markalar/koylu-tarim', $categories, 'Kart Tıklama Bağlantısı', '11px'); ?>
                        </div>
                    </div>

                    <!-- 2. Zeliha Sultan -->
                    <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                        <span style="font-weight: 800; font-size: 13px; color: #ea580c; text-transform: uppercase;">2. Marka</span>
                        
                        <div class="panel-image-upload-row" style="display: flex; align-items: center; gap: 14px; margin-top: 10px; margin-bottom: 12px;">
                            <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($brandsExtra['brand2_image'] ?? 'blog_agriculture_hero.jpg') ?>" 
                                 alt="Marka 2" style="width: 80px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1;">
                            <div style="flex: 1;">
                                <label class="form-label" style="font-size: 12px;">Görsel Yükle</label>
                                <input type="file" name="brand2_image" accept="image/*" class="form-control" style="font-size: 12px;">
                                <input type="hidden" name="extra[brand2_image]" value="<?= htmlspecialchars($brandsExtra['brand2_image'] ?? 'blog_agriculture_hero.jpg') ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" style="font-size: 12px;">Marka Adı</label>
                            <input type="text" name="extra[brand2_name]" value="<?= htmlspecialchars($brandsExtra['brand2_name'] ?? 'ZELİHA SULTAN') ?>" class="form-control">
                        </div>
                        <div class="form-group" style="margin-top: 12px; margin-bottom: 0;">
                            <?php renderLinkSelector('extra[brand2_link]', $brandsExtra['brand2_link'] ?? '/markalar/zeliha-sultan', $categories, 'Kart Tıklama Bağlantısı', '11px'); ?>
                        </div>
                    </div>

                    <!-- 3. İnci Fresh -->
                    <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                        <span style="font-weight: 800; font-size: 13px; color: #ea580c; text-transform: uppercase;">3. Marka</span>
                        
                        <div class="panel-image-upload-row" style="display: flex; align-items: center; gap: 14px; margin-top: 10px; margin-bottom: 12px;">
                            <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($brandsExtra['brand3_image'] ?? 'fresh_fruits.jpg') ?>" 
                                 alt="Marka 3" style="width: 80px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1;">
                            <div style="flex: 1;">
                                <label class="form-label" style="font-size: 12px;">Görsel Yükle</label>
                                <input type="file" name="brand3_image" accept="image/*" class="form-control" style="font-size: 12px;">
                                <input type="hidden" name="extra[brand3_image]" value="<?= htmlspecialchars($brandsExtra['brand3_image'] ?? 'fresh_fruits.jpg') ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" style="font-size: 12px;">Marka Adı</label>
                            <input type="text" name="extra[brand3_name]" value="<?= htmlspecialchars($brandsExtra['brand3_name'] ?? 'İNCİ FRESH') ?>" class="form-control">
                        </div>
                        <div class="form-group" style="margin-top: 12px; margin-bottom: 0;">
                            <?php renderLinkSelector('extra[brand3_link]', $brandsExtra['brand3_link'] ?? '/markalar/inci-fresh', $categories, 'Kart Tıklama Bağlantısı', '11px'); ?>
                        </div>
                    </div>

                    <!-- 4. Lara Citrus -->
                    <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                        <span style="font-weight: 800; font-size: 13px; color: #ea580c; text-transform: uppercase;">4. Marka</span>
                        
                        <div class="panel-image-upload-row" style="display: flex; align-items: center; gap: 14px; margin-top: 10px; margin-bottom: 12px;">
                            <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($brandsExtra['brand4_image'] ?? 'citrus_showcase.jpg') ?>" 
                                 alt="Marka 4" style="width: 80px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1;">
                            <div style="flex: 1;">
                                <label class="form-label" style="font-size: 12px;">Görsel Yükle</label>
                                <input type="file" name="brand4_image" accept="image/*" class="form-control" style="font-size: 12px;">
                                <input type="hidden" name="extra[brand4_image]" value="<?= htmlspecialchars($brandsExtra['brand4_image'] ?? 'citrus_showcase.jpg') ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" style="font-size: 12px;">Marka Adı</label>
                            <input type="text" name="extra[brand4_name]" value="<?= htmlspecialchars($brandsExtra['brand4_name'] ?? 'LARA CITRUS') ?>" class="form-control">
                        </div>
                        <div class="form-group" style="margin-top: 12px; margin-bottom: 0;">
                            <?php renderLinkSelector('extra[brand4_link]', $brandsExtra['brand4_link'] ?? '/markalar/lara-citrus', $categories, 'Kart Tıklama Bağlantısı', '11px'); ?>
                        </div>
                    </div>
                </div>

                <div style="text-align: right; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">💾 Grup Markaları Alanını Kaydet</button>
                </div>
            </form>
        </div>

        <?php 
        $secFac = getSec('home_facility', $sectionsBySection);
        $facExtra = $secFac['extra'] ?? [];
        ?>
        <!-- BÖLÜM 5: TESİS VE OTOMASYON ALANI -->
        <div style="background: #ffffff; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px; margin-bottom: 28px;">
            <div style="border-bottom: 2px solid #f1f5f9; padding-bottom: 14px; margin-bottom: 20px;">
                <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <span>🏭</span> 5. Tesis & Otomasyon Alanı Metinleri & Buton
                </h3>
            </div>

            <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-page" method="POST">
                <?= Security::csrfField() ?>
                <input type="hidden" name="section_key" value="home_facility">
                <input type="hidden" name="page_key" value="home">

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label">Üst Etiket Metni</label>
                    <input type="text" name="subtitle_tr" value="<?= htmlspecialchars($secFac['subtitle_tr'] ?? 'Tek Dünya, Tek Pazar') ?>" class="form-control">
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label">Büyük Başlık</label>
                    <textarea name="title_tr" rows="2" class="form-control"><?= htmlspecialchars($secFac['title_tr'] ?? 'Sıradan bir işleme tesisi değiliz; otomasyon, izlenebilirlik ve kalite kontrol süreçlerimizle ürünlerimizi daima güvence altında tutuyoruz.') ?></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label">Detaylı Açıklama Metni</label>
                    <textarea name="body_tr" rows="3" class="form-control"><?= htmlspecialchars($secFac['body_tr'] ?? 'Optik kalibrasyon, yapay zekalı leke tarama ve sıfır kalıntı ilkemiz ile tarladan toplanan her bir ürünü uluslararası hijyen standartlarında dünya sofralarına hazırlıyoruz.') ?></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label">Buton Yazısı</label>
                        <input type="text" name="extra[btn_text]" value="<?= htmlspecialchars($facExtra['btn_text'] ?? 'Bize Ulaşın') ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <?php renderLinkSelector('extra[btn_link]', $facExtra['btn_link'] ?? '/iletisim', $categories, 'Buton Linki (Kategori / Sayfa)'); ?>
                    </div>
                </div>

                <div style="text-align: right; margin-top: 16px;">
                    <button type="submit" class="btn btn-primary">💾 Tesis & Otomasyon Alanını Kaydet</button>
                </div>
            </form>
        </div>

        <?php 
        $secBlog = getSec('home_blog', $sectionsBySection);
        $blogExtra = $secBlog['extra'] ?? [];
        ?>
        <!-- BÖLÜM 6: HABERLER BÖLÜM BAŞLIKLARI -->
        <div style="background: #ffffff; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px;">
            <div style="border-bottom: 2px solid #f1f5f9; padding-bottom: 14px; margin-bottom: 20px;">
                <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <span>📰</span> 6. Haberler Alanı Başlıkları
                </h3>
            </div>

            <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-page" method="POST">
                <?= Security::csrfField() ?>
                <input type="hidden" name="section_key" value="home_blog">
                <input type="hidden" name="page_key" value="home">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 16px;">
                    <div class="form-group">
                        <label class="form-label">Üst Küçük Rozet</label>
                        <input type="text" name="subtitle_tr" value="<?= htmlspecialchars($secBlog['subtitle_tr'] ?? 'Sektörel Haberler') ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bölüm Başlığı</label>
                        <input type="text" name="title_tr" value="<?= htmlspecialchars($secBlog['title_tr'] ?? 'Haberler') ?>" class="form-control">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label">Buton Yazısı</label>
                        <input type="text" name="extra[btn_text]" value="<?= htmlspecialchars($blogExtra['btn_text'] ?? 'Tüm Haberler') ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <?php renderLinkSelector('extra[btn_link]', $blogExtra['btn_link'] ?? '/blog', $categories, 'Buton Linki (Kategori / Sayfa)'); ?>
                    </div>
                </div>

                <div style="text-align: right; margin-top: 16px;">
                    <button type="submit" class="btn btn-primary">💾 Haberler Başlık Alanını Kaydet</button>
                </div>
            </form>
        </div>

        <?php 
        $secRef = getSec('home_references', $sectionsBySection);
        $refExtra = $secRef['extra'] ?? [];
        $refItems = $refExtra['items'] ?? [];
        ?>
        <!-- BÖLÜM 7: KÜRESEL İŞ ORTAKLARI & GÜVENİLİR REFERANS LOGOLARI (INFINITE MARQUEE) -->
        <div style="background: #ffffff; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px; margin-bottom: 28px;">
            <div style="border-bottom: 2px solid #f1f5f9; padding-bottom: 14px; margin-bottom: 20px;">
                <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <span>🤝</span> 7. Küresel İş Ortakları & Referans Logoları (Kayan Bant / Marquee)
                </h3>
                <p style="font-size: 13px; color: #64748b; margin: 6px 0 0 0;">
                    Anasayfada yer alan sonsuz döngülü referans logo bandını buradan yönetebilirsiniz. Tüm kartlar eşit ve saf beyaz arka planlıdır.
                </p>
            </div>

            <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-page" method="POST" enctype="multipart/form-data">
                <?= Security::csrfField() ?>
                <input type="hidden" name="section_key" value="home_references">
                <input type="hidden" name="page_key" value="home">

                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-bottom: 16px;">
                    <div class="form-group">
                        <label class="form-label">Üst Etiket</label>
                        <input type="text" name="subtitle_tr" value="<?= htmlspecialchars($secRef['subtitle_tr'] ?? 'KÜRESEL İŞ ORTAKLARI & GÜVENİLİR REFERANSLAR') ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bölüm Başlığı</label>
                        <input type="text" name="title_tr" value="<?= htmlspecialchars($secRef['title_tr'] ?? 'BİK GRUP Güvencesiyle Dünya Standartlarında Ticaret') ?>" class="form-control">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label">Açıklama Metni</label>
                    <textarea name="body_tr" rows="2" class="form-control"><?= htmlspecialchars($secRef['body_tr'] ?? 'Tarımsal üretim ve ihracatta Türkiye\'nin ve dünyanın lider gıda, yem ve sanayi kuruluşlarıyla güçlü iş birliği.') ?></textarea>
                </div>

                <!-- Mevcut Referans Logoları Izgarası -->
                <h4 style="font-size: 15px; font-weight: 800; color: #15803d; margin: 18px 0 14px; display: flex; justify-content: space-between; align-items: center;">
                    <span>Aktif Referans Logoları (<?= count($refItems) ?> Adet)</span>
                    <span style="font-size: 12.5px; color: #64748b; font-weight: 500;">Tüm logolar standart 400x180 beyaz zemin üzerinde optimize edilmiştir.</span>
                </h4>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 16px; margin-bottom: 24px;">
                    <?php foreach ($refItems as $idx => $item): ?>
                        <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 14px; display: flex; flex-direction: column; align-items: center; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                            <div style="width: 100%; height: 75px; background: #ffffff; border: 1px solid #f1f5f9; border-radius: 10px; display: flex; align-items: center; justify-content: center; padding: 6px; box-sizing: border-box; margin-bottom: 10px;">
                                <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="max-height: 48px; max-width: 90%; object-fit: contain;">
                            </div>
                            <input type="text" name="ref_items[<?= $idx ?>][name]" value="<?= htmlspecialchars($item['name']) ?>" class="form-control" style="font-size: 12.5px; font-weight: 700; text-align: center; margin-bottom: 8px; padding: 6px 8px;">
                            <input type="hidden" name="ref_items[<?= $idx ?>][image]" value="<?= htmlspecialchars($item['image']) ?>">
                            <div style="width: 100%;">
                                <label style="font-size: 11px; color: #64748b; display: block; margin-bottom: 2px;">Logoyu Değiştir</label>
                                <input type="file" name="ref_logo_<?= $idx ?>" accept="image/*" class="form-control" style="font-size: 11px; padding: 4px;">
                            </div>
                            <div style="width: 100%; margin-top: 10px; border-top: 1px dashed #e2e8f0; padding-top: 8px;">
                                <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/delete-reference?idx=<?= $idx ?>" 
                                   onclick="return confirm('<?= htmlspecialchars(addslashes($item['name'])) ?> referans logosunu silmek istediğinizden emin misiniz?');" 
                                   class="btn" 
                                   style="background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; font-size: 11px; font-weight: 700; padding: 6px 12px; border-radius: 8px; width: 100%; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 5px; box-sizing: border-box; transition: background 0.15s;">
                                    🗑️ Bu Logoyu Sil
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Yeni Referans Logosu Ekleme Alanı -->
                <div style="background: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: 14px; padding: 18px; margin-bottom: 20px;">
                    <h5 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 0 0 12px 0;">➕ Yeni Referans / İş Ortağı Ekle</h5>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <div>
                            <label class="form-label" style="font-size: 12px;">Referans / Firma Adı</label>
                            <input type="text" name="new_ref_name" placeholder="Örn: Yeni İş Ortağı" class="form-control" style="font-size: 13px;">
                        </div>
                        <div>
                            <label class="form-label" style="font-size: 12px;">Logo Görseli Seç (PNG, JPG, WebP)</label>
                            <input type="file" name="new_ref_logo" accept="image/*" class="form-control" style="font-size: 12px;">
                        </div>
                    </div>
                </div>

                <div style="text-align: right; margin-top: 16px;">
                    <button type="submit" class="btn btn-primary" style="background: #15803d; border-color: #15803d; font-weight: 700; padding: 11px 26px;">💾 Referans Logo Alanını & Değişiklikleri Kaydet</button>
                </div>
            </form>
        </div>

    <!-- ==================================================================== -->
    <!-- 2. KURUMSAL / HAKKIMIZDA SAYFASI                                      -->
    <!-- ==================================================================== -->
    <?php elseif ($activePage === 'about'): ?>
        
        <?php 
        $secAboutHero = getSec('about_hero', $sectionsBySection);
        $aboutHeroExtra = $secAboutHero['extra'] ?? [];
        $secStory = getSec('about_story', $sectionsBySection);
        $secMv = getSec('mission_vision', $sectionsBySection);
        $mvExtra = $secMv['extra'] ?? [];
        ?>

        <!-- 1. KURUMSAL HERO GİRİŞ ALANI & ARKA PLAN GÖRSELİ -->
        <div style="background: #ffffff; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px; margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <span>🌄</span> 1. Kurumsal Hero Giriş Alanı & Arka Plan Afişi
            </h3>
            <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-page" method="POST" enctype="multipart/form-data">
                <?= Security::csrfField() ?>
                <input type="hidden" name="section_key" value="about_hero">
                <input type="hidden" name="page_key" value="about">

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 18px;">
                    <label class="form-label" style="font-weight: 700; color: #0f172a;">Giriş Ekranı Arka Plan Görseli</label>
                    <div class="panel-image-upload-row" style="display: flex; align-items: center; gap: 20px; margin-top: 10px;">
                        <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($aboutHeroExtra['bg_image'] ?? 'facility_automation.jpg') ?>" 
                             alt="Kurumsal Arka Plan" style="width: 160px; height: 90px; object-fit: cover; border-radius: 8px; border: 2px solid #cbd5e1;">
                        <div>
                            <span style="font-size: 13px; color: #475569; display: block; margin-bottom: 6px;">Mevcut Görsel: <strong><?= htmlspecialchars($aboutHeroExtra['bg_image'] ?? 'facility_automation.jpg') ?></strong></span>
                            <input type="file" name="bg_image" accept="image/*" class="form-control" style="font-size: 13px;">
                            <input type="hidden" name="extra[bg_image]" value="<?= htmlspecialchars($aboutHeroExtra['bg_image'] ?? 'facility_automation.jpg') ?>">
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 16px;">
                    <div class="form-group">
                        <label class="form-label">Büyük Başlık</label>
                        <input type="text" name="title_tr" value="<?= htmlspecialchars($secAboutHero['title_tr'] ?? 'Köklerimiz Toprakta, Hedefimiz Dünyada.') ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alt Slogan / Açıklama</label>
                        <input type="text" name="subtitle_tr" value="<?= htmlspecialchars($secAboutHero['subtitle_tr'] ?? 'BİK GRUP | Topraktan Geleceğe Güvenle.') ?>" class="form-control">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label">Buton Yazısı</label>
                        <input type="text" name="extra[btn_text]" value="<?= htmlspecialchars($aboutHeroExtra['btn_text'] ?? 'Tarihçemiz') ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <?php renderLinkSelector('extra[btn_link]', $aboutHeroExtra['btn_link'] ?? '#tarihce', $categories, 'Buton Hedefi / Linki'); ?>
                    </div>
                </div>

                <div style="text-align: right; margin-top: 16px;">
                    <button type="submit" class="btn btn-primary">💾 Kurumsal Hero Alanını Kaydet</button>
                </div>
            </form>
        </div>

        <!-- 2. KURUMSAL HİKAYE & TARİHÇE -->
        <div style="background: #ffffff; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px; margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <span>📜</span> 2. Kurumsal Hikaye & Tarihçe Alanı
            </h3>
            <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-page" method="POST">
                <?= Security::csrfField() ?>
                <input type="hidden" name="section_key" value="about_story">
                <input type="hidden" name="page_key" value="about">

                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-bottom: 16px;">
                    <div class="form-group">
                        <label class="form-label">Üst Etiket</label>
                        <input type="text" name="subtitle_tr" value="<?= htmlspecialchars($secStory['subtitle_tr'] ?? 'Tarihçemiz') ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ana Vurgu Başlığı</label>
                        <input type="text" name="title_tr" value="<?= htmlspecialchars($secStory['title_tr'] ?? 'Yarım Asırlık Tarımsal Tecrübe ve Modern İhracat Vizyonu') ?>" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tarihçe & Kurumsal Metin (Ön Yüzdeki Büyük Açıklama Paragrafı)</label>
                    <textarea name="body_tr" rows="6" class="form-control"><?= htmlspecialchars($secStory['body_tr'] ?? 'BİK GRUP olarak, yarım asırlık köklü tarımsal birikimimiz ve modern vizyonumuzla Akdeniz ile Ege’nin en verimli topraklarında başladığımız yolculuğu, bugün küresel pazarlara taşımanın gururunu yaşıyoruz. Doğallığı, kaliteyi ve insan sağlığını odağımıza alarak; tarladan sofraya uzanan her aşamada güvenilir, sürdürülebilir ve yüksek standartlı bir değer zinciri inşa ediyoruz.') ?></textarea>
                </div>

                <div style="text-align: right; margin-top: 16px;">
                    <button type="submit" class="btn btn-primary">💾 Tarihçe Alanını Kaydet</button>
                </div>
            </form>
        </div>

        <!-- 3. MİSYONUMUZ & VİZYONUMUZ -->
        <div style="background: #ffffff; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <span>🎯</span> 3. Misyonumuz & Vizyonumuz (Ön Yüzdeki 2 Büyük Yeşil Kart)
            </h3>
            <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-page" method="POST">
                <?= Security::csrfField() ?>
                <input type="hidden" name="section_key" value="mission_vision">
                <input type="hidden" name="page_key" value="about">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <!-- Misyon Kartı -->
                    <div style="background: #f0fdf4; border: 1.5px solid #bbf7d0; border-radius: 12px; padding: 18px;">
                        <h4 style="font-size: 14px; font-weight: 800; color: #166534; margin-top: 0; margin-bottom: 12px;">🌱 MİSYONUMUZ</h4>
                        <div class="form-group">
                            <label class="form-label">Misyon Başlığı</label>
                            <input type="text" name="extra[mission_title]" value="<?= htmlspecialchars($mvExtra['mission_title'] ?? 'MİSYONUMUZ') ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Misyon Açıklaması</label>
                            <textarea name="extra[mission_desc]" rows="4" class="form-control"><?= htmlspecialchars($mvExtra['mission_desc'] ?? 'Toprağın bereketini teknoloji, kalite ve güvenle buluşturarak, üreticiden tüketiciye uzanan sürdürülebilir bir değer zinciri oluşturmak.') ?></textarea>
                        </div>
                    </div>

                    <!-- Vizyon Kartı -->
                    <div style="background: #f0fdf4; border: 1.5px solid #bbf7d0; border-radius: 12px; padding: 18px;">
                        <h4 style="font-size: 14px; font-weight: 800; color: #166534; margin-top: 0; margin-bottom: 12px;">🌟 VİZYONUMUZ</h4>
                        <div class="form-group">
                            <label class="form-label">Vizyon Başlığı</label>
                            <input type="text" name="extra[vision_title]" value="<?= htmlspecialchars($mvExtra['vision_title'] ?? 'VİZYONUMUZ') ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Vizyon Açıklaması</label>
                            <textarea name="extra[vision_desc]" rows="4" class="form-control"><?= htmlspecialchars($mvExtra['vision_desc'] ?? 'Tarımın gücünü modern ticaret anlayışıyla birleştirerek, ulusal ve uluslararası pazarlarda güvenilir, güçlü ve öncü bir grup şirketi olmak.') ?></textarea>
                        </div>
                    </div>
                </div>

                <div style="text-align: right; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">💾 Misyon & Vizyon Kartlarını Kaydet</button>
                </div>
            </form>
        </div>

        <?php 
        $secCerts = getSec('about_certs', $sectionsBySection);
        $certsExtra = $secCerts['extra'] ?? [];
        ?>
        <!-- 4. ULUSLARARASI KALİTE VE GIDA GÜVENLİĞİ SERTİFİKALARI -->
        <div style="background: #ffffff; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px;">
            <div style="border-bottom: 2px solid #f1f5f9; padding-bottom: 14px; margin-bottom: 20px;">
                <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <span>🛡️</span> 4. Uluslararası Kalite & Gıda Güvenliği Sertifikaları (4 Belge Kartı Yönetimi)
                </h3>
                <p style="font-size: 13px; color: #64748b; margin: 6px 0 0 0;">
                    Kurumsal sayfasındaki resmi akreditasyon, standart ve gıda güvenliği belgelerini buradan güncelleyebilirsiniz.
                </p>
            </div>

            <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-page" method="POST">
                <?= Security::csrfField() ?>
                <input type="hidden" name="section_key" value="about_certs">
                <input type="hidden" name="page_key" value="about">

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label">Bölüm Ana Başlığı</label>
                    <input type="text" name="title_tr" value="<?= htmlspecialchars($secCerts['title_tr'] ?? 'Uluslararası Kalite & Gıda Güvenliği Sertifikalarımız') ?>" class="form-control">
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label">Açıklama Metni</label>
                    <textarea name="body_tr" rows="2" class="form-control"><?= htmlspecialchars($secCerts['body_tr'] ?? 'Uluslararası gıda güvenliği, izlenebilirlik ve sürdürülebilir tarım standartlarına tam uyumla belgelendirilmiş üretim tesislerimiz.') ?></textarea>
                </div>

                <!-- 4 Sertifika Kartı -->
                <h4 style="font-size: 14.5px; font-weight: 800; color: #15803d; margin: 16px 0 14px;">4 Sertifika / Belge Kartı</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px;">
                    
                    <!-- Sertifika 1 -->
                    <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                        <span style="font-weight: 800; font-size: 12.5px; color: #15803d; text-transform: uppercase;">1. Sertifika Belgesi</span>
                        <div class="form-group" style="margin-top: 10px;">
                            <label class="form-label" style="font-size: 12px;">Sertifika Adı</label>
                            <input type="text" name="extra[cert1_name]" value="<?= htmlspecialchars($certsExtra['cert1_name'] ?? 'GlobalG.A.P. IFA') ?>" class="form-control" style="font-weight: 700;">
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 10px;">
                            <div>
                                <label class="form-label" style="font-size: 11px;">Belge Başlığı</label>
                                <input type="text" name="extra[cert1_title]" value="<?= htmlspecialchars($certsExtra['cert1_title'] ?? 'GLOBALG.A.P.') ?>" class="form-control" style="font-size: 12px;">
                            </div>
                            <div>
                                <label class="form-label" style="font-size: 11px;">Damga Metni</label>
                                <input type="text" name="extra[cert1_stamp]" value="<?= htmlspecialchars($certsExtra['cert1_stamp'] ?? '✓ VERIFIED') ?>" class="form-control" style="font-size: 12px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-size: 12px;">Standart Açıklaması</label>
                            <textarea name="extra[cert1_standard]" rows="2" class="form-control" style="font-size: 12.5px;"><?= htmlspecialchars($certsExtra['cert1_standard'] ?? 'İyi Tarım Uygulamaları ve Uluslararası Çiftlik Güvenliği Standardı') ?></textarea>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 12px;">Rozet / Durum Metni</label>
                            <input type="text" name="extra[cert1_status]" value="<?= htmlspecialchars($certsExtra['cert1_status'] ?? '🛡️ Akredite İhracat Sertifikalı') ?>" class="form-control" style="font-size: 12.5px;">
                        </div>
                    </div>

                    <!-- Sertifika 2 -->
                    <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                        <span style="font-weight: 800; font-size: 12.5px; color: #15803d; text-transform: uppercase;">2. Sertifika Belgesi</span>
                        <div class="form-group" style="margin-top: 10px;">
                            <label class="form-label" style="font-size: 12px;">Sertifika Adı</label>
                            <input type="text" name="extra[cert2_name]" value="<?= htmlspecialchars($certsExtra['cert2_name'] ?? 'BRCGS Food Safety') ?>" class="form-control" style="font-weight: 700;">
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 10px;">
                            <div>
                                <label class="form-label" style="font-size: 11px;">Belge Başlığı</label>
                                <input type="text" name="extra[cert2_title]" value="<?= htmlspecialchars($certsExtra['cert2_title'] ?? 'BRCGS FOOD') ?>" class="form-control" style="font-size: 12px;">
                            </div>
                            <div>
                                <label class="form-label" style="font-size: 11px;">Damga Metni</label>
                                <input type="text" name="extra[cert2_stamp]" value="<?= htmlspecialchars($certsExtra['cert2_stamp'] ?? 'GRADE AA') ?>" class="form-control" style="font-size: 12px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-size: 12px;">Standart Açıklaması</label>
                            <textarea name="extra[cert2_standard]" rows="2" class="form-control" style="font-size: 12.5px;"><?= htmlspecialchars($certsExtra['cert2_standard'] ?? 'Küresel Gıda Güvenliği Girişimi (GFSI) Onaylı Paketleme & Hijyen') ?></textarea>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 12px;">Rozet / Durum Metni</label>
                            <input type="text" name="extra[cert2_status]" value="<?= htmlspecialchars($certsExtra['cert2_status'] ?? '🛡️ A Sınıfı Tesis Akreditasyonu') ?>" class="form-control" style="font-size: 12.5px;">
                        </div>
                    </div>

                    <!-- Sertifika 3 -->
                    <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                        <span style="font-weight: 800; font-size: 12.5px; color: #15803d; text-transform: uppercase;">3. Sertifika Belgesi</span>
                        <div class="form-group" style="margin-top: 10px;">
                            <label class="form-label" style="font-size: 12px;">Sertifika Adı</label>
                            <input type="text" name="extra[cert3_name]" value="<?= htmlspecialchars($certsExtra['cert3_name'] ?? 'IFS Food Standard') ?>" class="form-control" style="font-weight: 700;">
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 10px;">
                            <div>
                                <label class="form-label" style="font-size: 11px;">Belge Başlığı</label>
                                <input type="text" name="extra[cert3_title]" value="<?= htmlspecialchars($certsExtra['cert3_title'] ?? 'IFS FOOD v7') ?>" class="form-control" style="font-size: 12px;">
                            </div>
                            <div>
                                <label class="form-label" style="font-size: 11px;">Damga Metni</label>
                                <input type="text" name="extra[cert3_stamp]" value="<?= htmlspecialchars($certsExtra['cert3_stamp'] ?? 'HIGHER LEVEL') ?>" class="form-control" style="font-size: 12px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-size: 12px;">Standart Açıklaması</label>
                            <textarea name="extra[cert3_standard]" rows="2" class="form-control" style="font-size: 12.5px;"><?= htmlspecialchars($certsExtra['cert3_standard'] ?? 'Avrupa Perakende Birliği Yüksek Seviye Kalite ve İzlenebilirlik') ?></textarea>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 12px;">Rozet / Durum Metni</label>
                            <input type="text" name="extra[cert3_status]" value="<?= htmlspecialchars($certsExtra['cert3_status'] ?? '🛡️ Süpermarket Onaylı Tedarikçi') ?>" class="form-control" style="font-size: 12.5px;">
                        </div>
                    </div>

                    <!-- Sertifika 4 -->
                    <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                        <span style="font-weight: 800; font-size: 12.5px; color: #15803d; text-transform: uppercase;">4. Sertifika Belgesi</span>
                        <div class="form-group" style="margin-top: 10px;">
                            <label class="form-label" style="font-size: 12px;">Sertifika Adı</label>
                            <input type="text" name="extra[cert4_name]" value="<?= htmlspecialchars($certsExtra['cert4_name'] ?? 'ISO 22000:2018') ?>" class="form-control" style="font-weight: 700;">
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 10px;">
                            <div>
                                <label class="form-label" style="font-size: 11px;">Belge Başlığı</label>
                                <input type="text" name="extra[cert4_title]" value="<?= htmlspecialchars($certsExtra['cert4_title'] ?? 'ISO 22000') ?>" class="form-control" style="font-size: 12px;">
                            </div>
                            <div>
                                <label class="form-label" style="font-size: 11px;">Damga Metni</label>
                                <input type="text" name="extra[cert4_stamp]" value="<?= htmlspecialchars($certsExtra['cert4_stamp'] ?? 'ISO 22000:2018') ?>" class="form-control" style="font-size: 12px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-size: 12px;">Standart Açıklaması</label>
                            <textarea name="extra[cert4_standard]" rows="2" class="form-control" style="font-size: 12.5px;"><?= htmlspecialchars($certsExtra['cert4_standard'] ?? 'Tarladan Tüketime Gıda Güvenliği Yönetim Sistemi Standardı') ?></textarea>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 12px;">Rozet / Durum Metni</label>
                            <input type="text" name="extra[cert4_status]" value="<?= htmlspecialchars($certsExtra['cert4_status'] ?? '🛡️ Entegre Kalite Yönetimi') ?>" class="form-control" style="font-size: 12.5px;">
                        </div>
                    </div>
                </div>

                <div style="text-align: right; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">💾 Sertifikalar Alanını Kaydet</button>
                </div>
            </form>
        </div>

    <!-- ==================================================================== -->
    <!-- 3. İLETİŞİM SAYFASI                                                  -->
    <!-- ==================================================================== -->
    <?php elseif ($activePage === 'contact'): ?>
        <?php 
        $secContact = getSec('contact_hero', $sectionsBySection); 
        $contactExtra = $secContact['extra'] ?? [];
        ?>
        <div style="background: #ffffff; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <span>📞</span> İletişim Sayfası Giriş & Açıklama Metinleri
            </h3>
            <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-page" method="POST" enctype="multipart/form-data">
                <?= Security::csrfField() ?>
                <input type="hidden" name="section_key" value="contact_hero">
                <input type="hidden" name="page_key" value="contact">

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 18px;">
                    <label class="form-label" style="font-weight: 700; color: #0f172a;">İletişim Sayfası Arka Plan Görseli</label>
                    <div class="panel-image-upload-row" style="display: flex; align-items: center; gap: 20px; margin-top: 10px;">
                        <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($contactExtra['bg_image'] ?? 'citrus_showcase.jpg') ?>" 
                             alt="İletişim Arka Plan" style="width: 160px; height: 90px; object-fit: cover; border-radius: 8px; border: 2px solid #cbd5e1;">
                        <div>
                            <span style="font-size: 13px; color: #475569; display: block; margin-bottom: 6px;">Mevcut Görsel: <strong><?= htmlspecialchars($contactExtra['bg_image'] ?? 'citrus_showcase.jpg') ?></strong></span>
                            <input type="file" name="bg_image" accept="image/*" class="form-control" style="font-size: 13px;">
                            <input type="hidden" name="extra[bg_image]" value="<?= htmlspecialchars($contactExtra['bg_image'] ?? 'citrus_showcase.jpg') ?>">
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 16px;">
                    <div class="form-group">
                        <label class="form-label">İletişim Başlığı</label>
                        <input type="text" name="title_tr" value="<?= htmlspecialchars($secContact['title_tr'] ?? 'Bizimle İletişime Geçin.') ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Üst Küçük Slogan</label>
                        <input type="text" name="subtitle_tr" value="<?= htmlspecialchars($secContact['subtitle_tr'] ?? 'BİK GRUP | İletişim & Global Ofisler') ?>" class="form-control">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label">Alt Açıklama Metni</label>
                    <textarea name="body_tr" rows="3" class="form-control"><?= htmlspecialchars($secContact['body_tr'] ?? 'Taze ürün tedariği, ihracat ortaklığı veya tesis ziyaretleri için uzman ekibimizle dilediğiniz zaman iletişime geçebilirsiniz.') ?></textarea>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 20px;">
                    <h4 style="font-size: 14px; font-weight: 800; color: #15803d; margin-top: 0; margin-bottom: 12px;">Çalışma Saatleri & Harita Bilgisi</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Hafta İçi Çalışma Saatleri</label>
                            <input type="text" name="extra[working_hours]" value="<?= htmlspecialchars($contactExtra['working_hours'] ?? 'Pazartesi - Cuma: 08:30 - 18:00') ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Hafta Sonu Çalışma Saatleri</label>
                            <input type="text" name="extra[weekend_hours]" value="<?= htmlspecialchars($contactExtra['weekend_hours'] ?? 'Cumartesi: 09:00 - 14:00') ?>" class="form-control">
                        </div>
                    </div>
                </div>

                <div style="text-align: right; margin-top: 16px;">
                    <button type="submit" class="btn btn-primary">💾 İletişim Sayfasını Kaydet</button>
                </div>
            </form>
        </div>

    <!-- ==================================================================== -->
    <!-- 6. POLİTİKALAR (KVKK, GİZLİLİK, ÇEREZ)                                -->
    <!-- ==================================================================== -->
    <?php elseif ($activePage === 'policy'): ?>
        <?php 
        $secPriv = getSec('policy_privacy', $sectionsBySection);
        $secKvkk = getSec('policy_kvkk', $sectionsBySection);
        $secCookie = getSec('policy_cookie', $sectionsBySection);
        ?>
        <div style="background: #ffffff; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px; margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <span>🔒</span> 1. Gizlilik Politikası Metni
            </h3>
            <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-page" method="POST">
                <?= Security::csrfField() ?>
                <input type="hidden" name="section_key" value="policy_privacy">
                <input type="hidden" name="page_key" value="policy">

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 700;">Başlık</label>
                    <input type="text" name="title_tr" value="<?= htmlspecialchars($secPriv['title_tr'] ?? 'Gizlilik Politikası') ?>" class="form-control" style="font-weight: 700;">
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700;">Metin İçeriği</label>
                    <textarea name="body_tr" rows="12" class="form-control" style="font-size: 13.5px; line-height: 1.6;"><?= htmlspecialchars($secPriv['body_tr'] ?? '') ?></textarea>
                </div>
                <div style="text-align: right; margin-top: 16px;">
                    <button type="submit" class="btn btn-primary" style="padding: 10px 24px; font-weight: 700;">💾 Gizlilik Politikasını Kaydet</button>
                </div>
            </form>
        </div>

        <div style="background: #ffffff; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px; margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <span>📑</span> 2. KVKK Aydınlatma Metni
            </h3>
            <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-page" method="POST">
                <?= Security::csrfField() ?>
                <input type="hidden" name="section_key" value="policy_kvkk">
                <input type="hidden" name="page_key" value="policy">

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 700;">Başlık</label>
                    <input type="text" name="title_tr" value="<?= htmlspecialchars($secKvkk['title_tr'] ?? '6698 Sayılı KVKK Uyarınca Aydınlatma Metni') ?>" class="form-control" style="font-weight: 700;">
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700;">Metin İçeriği</label>
                    <textarea name="body_tr" rows="12" class="form-control" style="font-size: 13.5px; line-height: 1.6;"><?= htmlspecialchars($secKvkk['body_tr'] ?? '') ?></textarea>
                </div>
                <div style="text-align: right; margin-top: 16px;">
                    <button type="submit" class="btn btn-primary" style="padding: 10px 24px; font-weight: 700;">💾 KVKK Metnini Kaydet</button>
                </div>
            </form>
        </div>

        <div style="background: #ffffff; border: 1.5px solid var(--panel-border); border-radius: 16px; padding: 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <span>🍪</span> 3. Çerez (Cookie) Politikası Metni
            </h3>
            <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-page" method="POST">
                <?= Security::csrfField() ?>
                <input type="hidden" name="section_key" value="policy_cookie">
                <input type="hidden" name="page_key" value="policy">

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 700;">Başlık</label>
                    <input type="text" name="title_tr" value="<?= htmlspecialchars($secCookie['title_tr'] ?? 'Çerez (Cookie) Politikası') ?>" class="form-control" style="font-weight: 700;">
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700;">Metin İçeriği</label>
                    <textarea name="body_tr" rows="12" class="form-control" style="font-size: 13.5px; line-height: 1.6;"><?= htmlspecialchars($secCookie['body_tr'] ?? '') ?></textarea>
                </div>
                <div style="text-align: right; margin-top: 16px;">
                    <button type="submit" class="btn btn-primary" style="padding: 10px 24px; font-weight: 700;">💾 Çerez Politikasını Kaydet</button>
                </div>
            </form>
        </div>

    <?php endif; ?>

</div>
