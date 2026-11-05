<?php
/**
 * BİKGRUP - BİK GRUP Yönetim Paneli Medya & Galeri Yönetimi
 * Fotoğraf & Video Yönetimi, Otomatik WebP Dönüştürücü
 */

declare(strict_types=1);

$galleryHero = PageSection::get('gallery_page_hero') ?? [];
$galleryHeroExtra = $galleryHero['extra'] ?? (!empty($galleryHero['extra_json']) ? json_decode($galleryHero['extra_json'], true) : []);

$categoryLabelsTr = [
    'hasat'     => 'Hasat & Bahçeler',
    'tesisler'  => 'Tesislerimiz',
    'paketleme' => 'Paketleme',
    'urunler'   => 'Ürünlerimiz',
    'lojistik'  => 'Soğuk Zincir & Lojistik',
    'tanitim'   => 'Kurumsal Tanıtım',
    'genel'     => 'Genel'
];
?>

<!-- Galeri Sayfası Hero Afiş & Başlık Alanı -->
<div class="panel-card" style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;" onclick="const b = document.getElementById('galleryHeroCardBody'); b.style.display = (b.style.display === 'none' ? 'block' : 'none');">
        <div>
            <h2 class="panel-card-title" style="display: flex; align-items: center; gap: 8px;">
                <span>🌄</span> Galeri Sayfası Hero Giriş Afişi & Başlık Alanı
            </h2>
            <p style="color: var(--panel-text-muted); font-size: 13.5px; margin-top: 4px;">
                Sitedeki /galeri sayfasının en üstündeki afiş görselini, başlığını ve açıklama metnini buradan yönetin. (Açıp/kapatmak için tıklayın)
            </p>
        </div>
        <button type="button" class="btn btn-secondary" style="padding: 6px 14px; font-size: 12.5px; font-weight: 700; border-radius: 8px;">
            Afiş Alanını Düzenle ⚙️
        </button>
    </div>

    <div id="galleryHeroCardBody" style="display: none; margin-top: 20px; padding-top: 20px; border-top: 1.5px solid #f1f5f9;">
        <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-page" method="POST" enctype="multipart/form-data">
            <?= Security::csrfField() ?>
            <input type="hidden" name="section_key" value="gallery_page_hero">
            <input type="hidden" name="page_key" value="gallery">
            <input type="hidden" name="redirect_to" value="<?= PANEL_SLUG ?>/gallery">

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 18px;">
                <label class="form-label" style="font-weight: 700; color: #0f172a;">Galeri Sayfası Arka Plan Görseli</label>
                <div style="display: flex; align-items: center; gap: 20px; margin-top: 10px;">
                    <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($galleryHeroExtra['bg_image'] ?? 'nature_hero_bg.jpg') ?>" 
                         alt="Galeri Arka Plan" style="width: 160px; height: 90px; object-fit: cover; border-radius: 8px; border: 2px solid #cbd5e1;">
                    <div>
                        <span style="font-size: 13px; color: #475569; display: block; margin-bottom: 6px;">Mevcut Görsel: <strong><?= htmlspecialchars($galleryHeroExtra['bg_image'] ?? 'nature_hero_bg.jpg') ?></strong></span>
                        <input type="file" name="bg_image" accept="image/*" class="form-control" style="font-size: 13px;">
                        <input type="hidden" name="extra[bg_image]" value="<?= htmlspecialchars($galleryHeroExtra['bg_image'] ?? 'nature_hero_bg.jpg') ?>">
                        <small style="color: #10b981; font-weight: 600; display: block; margin-top: 4px;">⚡ Yüklenen görsel otomatik olarak WebP formatına dönüştürülür.</small>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 16px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700;">Büyük Başlık</label>
                    <input type="text" name="title_tr" class="form-control" 
                           value="<?= htmlspecialchars($galleryHero['title_tr'] ?? 'Fotoğraf & Video Galerisi') ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700;">Alt Slogan / Açıklama</label>
                    <input type="text" name="extra[badge_text]" class="form-control" 
                           value="<?= htmlspecialchars($galleryHeroExtra['badge_text'] ?? 'BİK GRUP | Sahadan ve Tesislerimizden Kareler') ?>">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" style="font-weight: 700;">Açıklama Metni</label>
                <textarea name="content_tr" class="form-control" rows="2"><?= htmlspecialchars($galleryHero['content_tr'] ?? 'Akdeniz bahçelerimizden hasada, modern paketleme tesislerimizden küresel sevkiyata uzanan üretim ve ihracat yolculuğumuz.') ?></textarea>
            </div>

            <div style="text-align: right;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px; font-weight: 700;">
                    💾 Galeri Hero Alanını Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Medya & Galeri Ana Yönetim Kartı -->
<div class="panel-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 class="panel-card-title" style="font-size: 20px; display: flex; align-items: center; gap: 8px;">
                <span>🖼️</span> Medya & Galeri Yönetimi
            </h1>
            <p style="color: var(--panel-text-muted); font-size: 13.5px; margin-top: 4px;">
                Fotoğrafları ve kurumsal videoları yönetin. Yüklenen tüm fotoğraflar otomatik olarak <strong>WebP</strong> formatına optimize edilir.
            </p>
        </div>
        <button type="button" class="btn btn-primary" onclick="openGalleryModal()" style="display: flex; align-items: center; gap: 8px; font-weight: 700; padding: 10px 20px;">
            <span>➕</span> Yeni Medya Ekle
        </button>
    </div>

    <!-- Filtre Sekmeleri -->
    <div style="display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
        <button type="button" class="btn btn-secondary" onclick="filterMediaType('all')" id="tab-all" style="background: #0f172a; color: #fff; border-color: #0f172a; font-size: 13px; font-weight: 600;">
            Tümü (<?= count($items) ?>)
        </button>
        <button type="button" class="btn btn-secondary" onclick="filterMediaType('image')" id="tab-image" style="font-size: 13px; font-weight: 600;">
            📷 Fotoğraflar (<?= count(array_filter($items, fn($i) => ($i['type'] ?? '') === 'image')) ?>)
        </button>
        <button type="button" class="btn btn-secondary" onclick="filterMediaType('video')" id="tab-video" style="font-size: 13px; font-weight: 600;">
            🎬 Videolar (<?= count(array_filter($items, fn($i) => ($i['type'] ?? '') === 'video')) ?>)
        </button>
    </div>

    <!-- Medya Listesi Tablosu -->
    <div class="table-responsive">
        <table class="panel-table" style="width: 100%; text-align: left; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #e2e8f0; color: #64748b; font-size: 12.5px; text-transform: uppercase;">
                    <th style="padding: 12px 16px; width: 80px;">Önizleme</th>
                    <th style="padding: 12px 16px;">Başlık & Bilgi</th>
                    <th style="padding: 12px 16px; width: 120px;">Tür</th>
                    <th style="padding: 12px 16px; width: 130px;">Kategori</th>
                    <th style="padding: 12px 16px; width: 90px; text-align: center;">Sıra</th>
                    <th style="padding: 12px 16px; width: 100px; text-align: center;">Durum</th>
                    <th style="padding: 12px 16px; width: 140px; text-align: right;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">
                            Henüz galeride medya bulunmuyor. Yeni medya eklemek için yukarıdaki butonu kullanın.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <tr class="gallery-row gallery-type-<?= htmlspecialchars($item['type']) ?>" style="border-bottom: 1px solid #f1f5f9;">
                            <!-- Önizleme -->
                            <td style="padding: 12px 16px;">
                                <?php 
                                $previewImg = !empty($item['image_path']) ? $item['image_path'] : 'nature_hero_bg.jpg';
                                ?>
                                <div style="position: relative; width: 70px; height: 50px; border-radius: 6px; overflow: hidden; background: #e2e8f0; border: 1px solid #cbd5e1;">
                                    <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($previewImg) ?>" 
                                         alt="<?= htmlspecialchars($item['title_tr']) ?>" 
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php if ($item['type'] === 'video'): ?>
                                        <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.35); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 14px;">
                                            ▶
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Başlık & Bilgi -->
                            <td style="padding: 12px 16px;">
                                <strong style="font-size: 14.5px; color: #0f172a; display: block;">
                                    <?= htmlspecialchars($item['title_tr']) ?>
                                </strong>
                                <?php if (!empty($item['title_en'])): ?>
                                    <span style="font-size: 12px; color: #64748b; display: block; margin-top: 2px;">
                                        🌐 EN: <?= htmlspecialchars($item['title_en']) ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($item['type'] === 'video' && !empty($item['video_url'])): ?>
                                    <a href="<?= htmlspecialchars($item['video_url']) ?>" target="_blank" style="font-size: 11.5px; color: #2563eb; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; margin-top: 3px;">
                                        🔗 Video Bağlantısı Aç
                                    </a>
                                <?php endif; ?>
                            </td>

                            <!-- Tür -->
                            <td style="padding: 12px 16px;">
                                <?php if ($item['type'] === 'video'): ?>
                                    <span style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                        🎬 Video
                                    </span>
                                <?php else: ?>
                                    <span style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                        📷 Fotoğraf
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Kategori -->
                            <td style="padding: 12px 16px;">
                                <span style="background: #f1f5f9; color: #334155; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                    <?= htmlspecialchars($categoryLabelsTr[$item['category'] ?? 'genel'] ?? ucfirst($item['category'])) ?>
                                </span>
                            </td>

                            <!-- Sıra -->
                            <td style="padding: 12px 16px; text-align: center; font-weight: 700; color: #475569;">
                                <?= (int)$item['sort_order'] ?>
                            </td>

                            <!-- Durum -->
                            <td style="padding: 12px 16px; text-align: center;">
                                <?php if (!empty($item['is_active'])): ?>
                                    <span style="color: #10b981; font-weight: 700; font-size: 12.5px;">● Yayında</span>
                                <?php else: ?>
                                    <span style="color: #94a3b8; font-weight: 700; font-size: 12.5px;">○ Pasif</span>
                                <?php endif; ?>
                            </td>

                            <!-- İşlemler -->
                            <td style="padding: 12px 16px; text-align: right;">
                                <button type="button" class="btn btn-secondary" 
                                        onclick='editGalleryItem(<?= json_encode($item, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'
                                        style="padding: 6px 12px; font-size: 12px; font-weight: 600; border-radius: 6px; margin-right: 6px;">
                                    Düzenle
                                </button>
                                <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/delete-gallery?id=<?= $item['id'] ?>" 
                                   onclick="return confirm('Bu medya öğesini silmek istediğinize emin misiniz?')"
                                   class="btn btn-danger" 
                                   style="padding: 6px 10px; font-size: 12px; font-weight: 600; border-radius: 6px; text-decoration: none; background: #ef4444; color: #fff;">
                                    Sil
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================================================
     MEDYA EKLEME / DÜZENLEME MODALI (TAM TÜRKÇE & OTOMATİK WEBP)
     ======================================================================== -->
<div id="galleryModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); z-index: 9999; backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 20px; overflow-y: auto;">
    <div style="background: #ffffff; width: 100%; max-width: 600px; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 1px solid #e2e8f0; overflow: hidden; animation: modalIn 0.25s ease-out;">
        
        <!-- Modal Başlık -->
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1.5px solid #f1f5f9; background: #fafafa;">
            <h3 id="modalHeading" style="margin: 0; font-size: 18px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <span>➕</span> Yeni Medya Ekle
            </h3>
            <button type="button" onclick="closeGalleryModal()" style="background: transparent; border: none; font-size: 22px; cursor: pointer; color: #64748b; line-height: 1;">&times;</button>
        </div>

        <!-- Modal Formu -->
        <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-gallery" method="POST" enctype="multipart/form-data" style="padding: 24px;">
            <?= Security::csrfField() ?>
            <input type="hidden" name="id" id="form_id" value="0">

            <!-- Medya Türü Seçimi -->
            <div class="form-group" style="margin-bottom: 18px;">
                <label class="form-label" style="font-weight: 700; color: #0f172a; display: block; margin-bottom: 8px;">Medya Türü *</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <label style="display: flex; align-items: center; gap: 10px; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; cursor: pointer; transition: all 0.2s;" id="type_label_image">
                        <input type="radio" name="type" value="image" id="type_image" checked onchange="handleTypeChange()">
                        <div>
                            <strong style="display: block; font-size: 13.5px; color: #0f172a;">📷 Fotoğraf</strong>
                            <small style="color: #64748b; font-size: 11px;">Otomatik WebP Optimizasyonu</small>
                        </div>
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; cursor: pointer; transition: all 0.2s;" id="type_label_video">
                        <input type="radio" name="type" value="video" id="type_video" onchange="handleTypeChange()">
                        <div>
                            <strong style="display: block; font-size: 13.5px; color: #0f172a;">🎬 Video</strong>
                            <small style="color: #64748b; font-size: 11px;">YouTube, Vimeo veya Video Dosyası</small>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Başlık (Sadece Türkçe, İngilizce Otomatik) -->
            <div class="form-group" style="margin-bottom: 18px;">
                <label class="form-label" style="font-weight: 700; color: #0f172a;">Medya Başlığı *</label>
                <input type="text" name="title_tr" id="form_title_tr" class="form-control" placeholder="Örn: Çukurova Bereketli Narenciye Hasadı" required>
                <small style="color: #64748b; font-size: 11.5px; display: block; margin-top: 4px;">
                    ℹ️ Sistem İngilizce çevirisini otomatik yapacaktır.
                </small>
            </div>

            <!-- Kategori ve Sıralama -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 18px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; color: #0f172a;">Kategori / Etiket</label>
                    <select name="category" id="form_category" class="form-control">
                        <option value="hasat">Hasat & Bahçelerimiz</option>
                        <option value="tesisler">Tesisler & Teknoloji</option>
                        <option value="paketleme">Paketleme & Tasarım</option>
                        <option value="urunler">Ürünlerimiz</option>
                        <option value="lojistik">Soğuk Zincir & Lojistik</option>
                        <option value="tanitim">Kurumsal Tanıtım</option>
                        <option value="genel">Genel</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; color: #0f172a;">Sıralama</label>
                    <input type="number" name="sort_order" id="form_sort_order" class="form-control" value="0">
                </div>
            </div>

            <!-- Fotoğraf / Video Kapak Görseli Yükleme -->
            <div class="form-group" style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 16px; margin-bottom: 18px;">
                <label class="form-label" id="image_label" style="font-weight: 700; color: #0f172a; display: block; margin-bottom: 6px;">
                    Fotoğraf Dosyası (JPG, PNG, WebP)
                </label>
                <input type="file" name="image_file" id="form_image_file" accept="image/*" class="form-control" style="font-size: 13px;">
                <div id="image_preview_box" style="display: none; align-items: center; gap: 12px; margin-top: 10px;">
                    <img id="form_image_preview" src="" alt="Önizleme" style="width: 70px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1;">
                    <span style="font-size: 12px; color: #64748b;" id="form_image_name"></span>
                </div>
                <small style="color: #059669; font-weight: 600; display: block; margin-top: 6px;">
                    ⚡ Otomatik WebP Dönüştürücü Aktiftir. Görsel boyutu kalite kaybı olmadan sıkıştırılır.
                </small>
            </div>

            <!-- Video Özel Alanları (Sadece Video Seçiliyken Görünür) -->
            <div id="video_fields_container" style="display: none; background: #fff1f2; border: 1px solid #fecdd3; border-radius: 12px; padding: 16px; margin-bottom: 18px;">
                <h4 style="margin: 0 0 10px 0; font-size: 13.5px; font-weight: 800; color: #9f1239; display: flex; align-items: center; gap: 6px;">
                    <span>🎬</span> Video Kaynak Bilgisi
                </h4>
                
                <div class="form-group" style="margin-bottom: 12px;">
                    <label class="form-label" style="font-weight: 700; color: #881337; font-size: 12.5px;">Video URL (YouTube, Vimeo veya Direkt Video Bağlantısı)</label>
                    <input type="url" name="video_url" id="form_video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=..." style="font-size: 13px;">
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; color: #881337; font-size: 12.5px;">VEYA Doğrudan Video Dosyası Yükle (MP4 / WebM)</label>
                    <input type="file" name="video_file" id="form_video_file" accept="video/mp4,video/webm" class="form-control" style="font-size: 13px;">
                </div>
                <small style="color: #9f1239; font-weight: 600; display: block; margin-top: 8px;">
                    🔇 Bilgi: Videolar sitede tıklandığında otomatik olarak sessiz (muted) başlar; ziyaretçi dilerse sesi video kumandasından açabilir.
                </small>
            </div>

            <!-- Durum Anahtarı -->
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 24px;">
                <input type="checkbox" name="is_active" id="form_is_active" value="1" checked style="width: 18px; height: 18px; cursor: pointer;">
                <label for="form_is_active" style="cursor: pointer; font-size: 13.5px; font-weight: 600; color: #0f172a;">
                    Bu medyayı sitede aktif ve yayında göster
                </label>
            </div>

            <!-- Aksiyon Butonları -->
            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1.5px solid #f1f5f9; padding-top: 18px;">
                <button type="button" class="btn btn-secondary" onclick="closeGalleryModal()" style="padding: 10px 20px; font-weight: 600;">
                    Vazgeç
                </button>
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px; font-weight: 700;">
                    💾 Kaydet & Yayınla
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function filterMediaType(type) {
    document.querySelectorAll('[id^="tab-"]').forEach(btn => {
        btn.style.background = '';
        btn.style.color = '';
        btn.style.borderColor = '';
    });
    const activeBtn = document.getElementById('tab-' + type);
    if (activeBtn) {
        activeBtn.style.background = '#0f172a';
        activeBtn.style.color = '#fff';
        activeBtn.style.borderColor = '#0f172a';
    }

    const rows = document.querySelectorAll('.gallery-row');
    rows.forEach(row => {
        if (type === 'all') {
            row.style.display = '';
        } else {
            row.style.display = row.classList.contains('gallery-type-' + type) ? '' : 'none';
        }
    });
}

function handleTypeChange() {
    const isVideo = document.getElementById('type_video').checked;
    const videoContainer = document.getElementById('video_fields_container');
    const imageLabel = document.getElementById('image_label');
    const labelImg = document.getElementById('type_label_image');
    const labelVid = document.getElementById('type_label_video');

    if (isVideo) {
        videoContainer.style.display = 'block';
        imageLabel.textContent = 'Video Kapak / Afiş Görseli (Otomatik WebP)';
        labelVid.style.borderColor = 'var(--panel-primary, #059669)';
        labelVid.style.background = '#f0fdf4';
        labelImg.style.borderColor = '#e2e8f0';
        labelImg.style.background = 'transparent';
    } else {
        videoContainer.style.display = 'none';
        imageLabel.textContent = 'Fotoğraf Dosyası (JPG, PNG, WebP)';
        labelImg.style.borderColor = 'var(--panel-primary, #059669)';
        labelImg.style.background = '#f0fdf4';
        labelVid.style.borderColor = '#e2e8f0';
        labelVid.style.background = 'transparent';
    }
}

function openGalleryModal() {
    document.getElementById('form_id').value = '0';
    document.getElementById('form_title_tr').value = '';
    document.getElementById('form_category').value = 'hasat';
    document.getElementById('form_sort_order').value = '0';
    document.getElementById('form_video_url').value = '';
    document.getElementById('form_is_active').checked = true;
    document.getElementById('type_image').checked = true;
    document.getElementById('image_preview_box').style.display = 'none';
    document.getElementById('modalHeading').innerHTML = '<span>➕</span> Yeni Medya Ekle';
    handleTypeChange();
    document.getElementById('galleryModal').style.display = 'flex';
}

function closeGalleryModal() {
    document.getElementById('galleryModal').style.display = 'none';
}

function editGalleryItem(item) {
    document.getElementById('form_id').value = item.id;
    document.getElementById('form_title_tr').value = item.title_tr || '';
    document.getElementById('form_category').value = item.category || 'genel';
    document.getElementById('form_sort_order').value = item.sort_order || '0';
    document.getElementById('form_video_url').value = item.video_url || '';
    document.getElementById('form_is_active').checked = String(item.is_active) === '1';

    if (item.type === 'video') {
        document.getElementById('type_video').checked = true;
    } else {
        document.getElementById('type_image').checked = true;
    }
    handleTypeChange();

    if (item.image_path) {
        document.getElementById('form_image_preview').src = '<?= BASE_URL ?>/public/images/' + item.image_path;
        document.getElementById('form_image_name').textContent = 'Mevcut: ' + item.image_path;
        document.getElementById('image_preview_box').style.display = 'flex';
    } else {
        document.getElementById('image_preview_box').style.display = 'none';
    }

    document.getElementById('modalHeading').innerHTML = '<span>✏️</span> Medya Düzenle: ' + (item.title_tr || '');
    document.getElementById('galleryModal').style.display = 'flex';
}

// Dışarı tıklayınca kapatma
document.getElementById('galleryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeGalleryModal();
    }
});
</script>

<style>
@keyframes modalIn {
    from { opacity: 0; transform: scale(0.96) translateY(-10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
</style>

