<?php
/**
 * BİKGRUP - BİK GRUP Yönetim Paneli Blog Yönetimi
 */

declare(strict_types=1);

$blogHero = PageSection::get('blog_page_hero') ?? [];
$blogHeroExtra = $blogHero['extra'] ?? (!empty($blogHero['extra_json']) ? json_decode($blogHero['extra_json'], true) : []);
?>

<!-- Blog Sayfası Hero Afiş & Başlık Alanı -->
<div class="panel-card" style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;" onclick="const b = document.getElementById('blogHeroCardBody'); b.style.display = (b.style.display === 'none' ? 'block' : 'none');">
        <div>
            <h2 class="panel-card-title" style="display: flex; align-items: center; gap: 8px;">
                <span>🌄</span> Blog Sayfası Hero Giriş Afişi & Başlık Alanı
            </h2>
            <p style="color: var(--panel-text-muted); font-size: 13.5px; margin-top: 4px;">
                Sitedeki /blog sayfasının en üstündeki afiş görselini, başlığını ve açıklama metnini buradan yönetin. (Açıp/kapatmak için tıklayın)
            </p>
        </div>
        <button type="button" class="btn btn-secondary" style="padding: 6px 14px; font-size: 12.5px; font-weight: 700; border-radius: 8px;">
            Afiş Alanını Düzenle ⚙️
        </button>
    </div>

    <div id="blogHeroCardBody" style="display: none; margin-top: 20px; padding-top: 20px; border-top: 1.5px solid #f1f5f9;">
        <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-page" method="POST" enctype="multipart/form-data">
            <?= Security::csrfField() ?>
            <input type="hidden" name="section_key" value="blog_page_hero">
            <input type="hidden" name="page_key" value="blog">
            <input type="hidden" name="redirect_to" value="<?= PANEL_SLUG ?>/blog">

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 18px;">
                <label class="form-label" style="font-weight: 700; color: #0f172a;">Blog Sayfası Arka Plan Görseli</label>
                <div style="display: flex; align-items: center; gap: 20px; margin-top: 10px;">
                    <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($blogHeroExtra['bg_image'] ?? 'blog_hero_new.jpg') ?>" 
                         alt="Blog Arka Plan" style="width: 160px; height: 90px; object-fit: cover; border-radius: 8px; border: 2px solid #cbd5e1;">
                    <div>
                        <span style="font-size: 13px; color: #475569; display: block; margin-bottom: 6px;">Mevcut Görsel: <strong><?= htmlspecialchars($blogHeroExtra['bg_image'] ?? 'blog_hero_new.jpg') ?></strong></span>
                        <input type="file" name="bg_image" accept="image/*" class="form-control" style="font-size: 13px;">
                        <input type="hidden" name="extra[bg_image]" value="<?= htmlspecialchars($blogHeroExtra['bg_image'] ?? 'blog_hero_new.jpg') ?>">
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 16px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700;">Büyük Başlık</label>
                    <input type="text" name="title_tr" value="<?= htmlspecialchars($blogHero['title_tr'] ?? 'Tarımsal Gelişmeler & Sektörel Haberler') ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700;">Alt Slogan / Açıklama</label>
                    <input type="text" name="subtitle_tr" value="<?= htmlspecialchars($blogHero['subtitle_tr'] ?? 'BİK GRUP | Sürdürülebilir Tarım ve İhracat Dünyası') ?>" class="form-control">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label class="form-label" style="font-weight: 700;">Açıklama Metni</label>
                <textarea name="body_tr" rows="2" class="form-control"><?= htmlspecialchars($blogHero['body_tr'] ?? 'Tarımsal inovasyon, ihracat trendleri, sürdürülebilir üretim ve sektörümüzden en güncel gelişmeleri takip edin.') ?></textarea>
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
            <h2 class="panel-card-title">📰 Blog ve Haber Yönetimi</h2>
            <p style="color: var(--panel-text-muted); font-size: 14px; margin-top: 4px;">
                Sektörel analizler, soğuk zincir rehberleri ve şirket duyurularını yönetin.
            </p>
        </div>
        <div>
            <button onclick="openNewBlogModal()" class="btn btn-primary" style="padding: 10px 22px;">
                + Yeni Yazı Ekle
            </button>
        </div>
    </div>

    <table class="panel-table">
        <thead>
            <tr>
                <th>Görsel</th>
                <th>Başlık (TR)</th>
                <th>Okuma Süresi</th>
                <th>Durum</th>
                <th>Tarih</th>
                <th style="text-align: right;">İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td style="width: 60px;">
                        <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($post['image'] ?: 'facility_automation.jpg') ?>" style="width: 48px; height: 48px; border-radius: 10px; object-fit: cover; border: 1.5px solid #e2e8f0; background: #ffffff;">
                    </td>
                    <td style="font-weight: 700; font-size: 14.5px;">
                        <?= htmlspecialchars($post['title_tr']) ?>
                    </td>
                    <td style="font-size: 13px; color: #475569;">
                        ⏱ <?= htmlspecialchars($post['read_time'] ?: '4 dk') ?>
                    </td>
                    <td>
                        <?= !empty($post['is_published']) ? '<span style="background:#f0fdf4; color:#166534; font-size:11.5px; font-weight:700; padding:3px 9px; border-radius:20px; border:1px solid #bbf7d0;">Yayında</span>' : '<span style="background:#fef2f2; color:#991b1b; font-size:11.5px; font-weight:700; padding:3px 9px; border-radius:20px;">Taslak</span>' ?>
                    </td>
                    <td style="font-size: 13px; color: #64748b; white-space: nowrap;">
                        <?= date('d.m.Y', strtotime($post['created_at'])) ?>
                    </td>
                    <td style="text-align: right; white-space: nowrap;">
                        <a href="<?= BASE_URL ?>/blog/<?= urlencode($post['slug']) ?>" target="_blank" class="btn btn-sm btn-outline" style="margin-right: 4px;">
                            Gör ↗
                        </a>
                        <button type="button" class="btn btn-sm btn-outline" style="color: #15803d !important; border-color: #86efac; margin-right: 4px;" onclick='openEditBlogModal(<?= json_encode($post, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'>
                            Düzenle ✏️
                        </button>
                        <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/delete-blog?id=<?= $post['id'] ?>" 
                           data-confirm-delete="<?= htmlspecialchars($post['title_tr']) ?> başlıklı yazıyı silmek istediğinize emin misiniz?" 
                           class="btn btn-sm btn-danger">
                            Sil 🗑️
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Blog Ekleme & Düzenleme Modalı -->
<div id="blogModal" style="display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #ffffff; border-radius: 20px; width: 100%; max-width: 760px; max-height: 90vh; overflow-y: auto; padding: 32px; box-shadow: 0 25px 60px rgba(0,0,0,0.25);">
        
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 24px; border-bottom: 1px solid #e2e8f0; padding-bottom: 16px;">
            <h3 id="blogModalTitle" style="font-family:'Outfit', sans-serif; font-size: 21px; font-weight: 800; color:#0f172a; margin:0;">
                + Yeni Blog Yazısı
            </h3>
            <button type="button" onclick="closeModal('blogModal')" style="background:none; border:none; font-size: 22px; cursor: pointer; color:#94a3b8;">✕</button>
        </div>

        <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-blog" method="POST" enctype="multipart/form-data">
            <?= Security::csrfField() ?>
            <input type="hidden" name="id" id="blog_id">

            <div style="margin-bottom: 18px;">
                <label class="form-label">Başlık *</label>
                <input type="text" name="title_tr" id="blog_title_tr" required placeholder="Yazı başlığı..." class="form-control">
                <input type="hidden" name="title_en" id="blog_title_en" value="">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 18px;">
                <div>
                    <label class="form-label">SEO URL (Slug - Otomatik)</label>
                    <input type="text" name="slug" id="blog_slug" placeholder="yazi-url-adresi" class="form-control">
                </div>
                <div>
                    <label class="form-label">Okuma Süresi</label>
                    <input type="text" name="read_time" id="blog_read_time" value="4 dk" class="form-control">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 18px;">
                <div>
                    <label class="form-label">Kapak Görseli (Dosya Seçin)</label>
                    <input type="hidden" name="image" id="blog_image" value="facility_automation.jpg">
                    <input type="file" name="image_file" id="blog_image_file" accept="image/png, image/jpeg, image/webp, image/gif" class="form-control" onchange="previewBlogImage(this)">
                    <div style="display: flex; align-items: center; gap: 12px; margin-top: 10px; background: #f8fafc; padding: 10px 14px; border-radius: 12px; border: 1.5px solid #e2e8f0;">
                        <img id="blog_preview_img" src="<?= BASE_URL ?>/public/images/facility_automation.jpg" style="width: 52px; height: 52px; border-radius: 10px; object-fit: cover; border: 1.5px solid #cbd5e1; background: #ffffff;">
                        <div style="overflow: hidden; flex: 1;">
                            <div id="blog_current_img_label" style="font-weight: 700; font-size: 13px; color: #1e293b; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">facility_automation.jpg</div>
                            <div style="color: #15803d; font-weight: 700; font-size: 11.5px; margin-top: 2px;">⚡ Otomatik WebP Dönüştürücü Aktif</div>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="form-label">Yazar Adı</label>
                    <input type="text" name="author" id="blog_author" value="BİKGRUP Basın & Medya" class="form-control">
                </div>
            </div>

            <div style="margin-bottom: 18px;">
                <label class="form-label">Kısa Özet</label>
                <textarea name="excerpt_tr" id="blog_excerpt_tr" rows="2" class="form-control" placeholder="Yazının kısa girişi veya spot metni..."></textarea>
            </div>

            <div style="margin-bottom: 22px;">
                <label class="form-label">Detaylı Makale İçeriği</label>
                <textarea name="content_tr" id="blog_content_tr" rows="7" class="form-control" placeholder="Yazının tam içeriği (paragraflar, başlıklar destekler)..."></textarea>
            </div>

            <div style="display: flex; gap: 24px; align-items: center; margin-bottom: 26px; background:#f8fafc; padding:14px 18px; border-radius:12px; border:1px solid #e2e8f0;">
                <label style="display:flex; align-items:center; gap: 8px; font-weight: 700; cursor: pointer; color:#0f172a;">
                    <input type="checkbox" name="is_published" id="blog_is_published" value="1" checked> 🟢 Yayında Göster
                </label>
            </div>

            <div style="text-align: right; display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" onclick="closeModal('blogModal')" class="btn btn-outline">İptal</button>
                <button type="submit" id="blogSubmitBtn" class="btn btn-primary">📝 Yazıyı Yayınla</button>
            </div>
        </form>

    </div>
</div>

<script>
function previewBlogImage(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('blog_preview_img').src = e.target.result;
            document.getElementById('blog_current_img_label').textContent = file.name + ' (Yeni Yüklenecek ➔ .webp)';
        };
        reader.readAsDataURL(file);
    }
}

function openNewBlogModal() {
    document.getElementById('blog_id').value = '';
    document.getElementById('blog_title_tr').value = '';
    document.getElementById('blog_title_en').value = '';
    document.getElementById('blog_slug').value = '';
    document.getElementById('blog_read_time').value = '4 dk';
    
    document.getElementById('blog_image').value = 'facility_automation.jpg';
    document.getElementById('blog_image_file').value = '';
    document.getElementById('blog_preview_img').src = '<?= BASE_URL ?>/public/images/facility_automation.jpg';
    document.getElementById('blog_current_img_label').textContent = 'facility_automation.jpg';
    
    document.getElementById('blog_author').value = 'BİKGRUP Basın & Medya';
    document.getElementById('blog_excerpt_tr').value = '';
    document.getElementById('blog_content_tr').value = '';
    document.getElementById('blog_is_published').checked = true;
    document.getElementById('blogModalTitle').textContent = '+ Yeni Blog Yazısı';
    document.getElementById('blogSubmitBtn').textContent = '📝 Yazıyı Yayınla';
    openModal('blogModal');
}

function openEditBlogModal(p) {
    document.getElementById('blog_id').value = p.id;
    document.getElementById('blog_title_tr').value = p.title_tr || '';
    document.getElementById('blog_title_en').value = p.title_en || '';
    document.getElementById('blog_slug').value = p.slug || '';
    document.getElementById('blog_read_time').value = p.read_time || '4 dk';
    
    const currentImg = p.image || 'facility_automation.jpg';
    document.getElementById('blog_image').value = currentImg;
    document.getElementById('blog_image_file').value = '';
    document.getElementById('blog_preview_img').src = '<?= BASE_URL ?>/public/images/' + currentImg;
    document.getElementById('blog_current_img_label').textContent = currentImg;
    
    document.getElementById('blog_author').value = p.author || 'BİKGRUP Basın & Medya';
    document.getElementById('blog_excerpt_tr').value = p.excerpt_tr || '';
    document.getElementById('blog_content_tr').value = p.content_tr || '';
    document.getElementById('blog_is_published').checked = parseInt(p.is_published) === 1;
    document.getElementById('blogModalTitle').textContent = '✏️ Blog Yazısını Düzenle: ' + p.title_tr;
    document.getElementById('blogSubmitBtn').textContent = '💾 Değişiklikleri Güncelle';
    openModal('blogModal');
}
</script>
