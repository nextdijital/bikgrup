<?php
/**
 * BİKGRUP - BİK GRUP Yönetim Paneli Dashboard Görünümü
 */

declare(strict_types=1);
?>

<!-- 1. İstatistik Sayaç Kartları -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 20px; margin-bottom: 30px;">
    
    <div class="panel-card" style="margin-bottom: 0;">
        <div style="font-size: 13px; font-weight: 700; color: var(--panel-text-muted); margin-bottom: 8px;">TOPLAM ÜRÜN</div>
        <div style="font-family:'Outfit', sans-serif; font-size: 34px; font-weight: 800; color: #15803d;"><?= $stats['product_count'] ?></div>
        <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Aktif Hasat & Ürün</div>
    </div>

    <div class="panel-card" style="margin-bottom: 0;">
        <div style="font-size: 13px; font-weight: 700; color: var(--panel-text-muted); margin-bottom: 8px;">KATEGORİLER</div>
        <div style="font-family:'Outfit', sans-serif; font-size: 34px; font-weight: 800; color: #ea580c;"><?= $stats['category_count'] ?></div>
        <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Narenciye, Meyve, Sebze</div>
    </div>

    <div class="panel-card" style="margin-bottom: 0;">
        <div style="font-size: 13px; font-weight: 700; color: var(--panel-text-muted); margin-bottom: 8px;">GELEN MESAJLAR</div>
        <div style="font-family:'Outfit', sans-serif; font-size: 34px; font-weight: 800; color: <?= $stats['unread_messages'] > 0 ? '#ef4444' : '#15803d' ?>;">
            <?= $stats['unread_messages'] ?> <span style="font-size: 14px; font-weight: 600;">Okunmamış</span>
        </div>
        <div style="font-size: 12px; color: #64748b; margin-top: 4px;">İletişim Formu Talepleri</div>
    </div>

    <div class="panel-card" style="margin-bottom: 0;">
        <div style="font-size: 13px; font-weight: 700; color: var(--panel-text-muted); margin-bottom: 8px;">HIZLI TEKLİFLER</div>
        <div style="font-family:'Outfit', sans-serif; font-size: 34px; font-weight: 800; color: <?= ($stats['pending_quotes'] ?? 0) > 0 ? '#ea580c' : '#15803d' ?>;">
            <?= $stats['pending_quotes'] ?? 0 ?> <span style="font-size: 14px; font-weight: 600;">Bekleyen</span>
        </div>
        <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Toplam <?= $stats['total_quotes'] ?? 0 ?> Teklif Talebi</div>
    </div>

    <div class="panel-card" style="margin-bottom: 0;">
        <div style="font-size: 13px; font-weight: 700; color: var(--panel-text-muted); margin-bottom: 8px;">BLOG YAZILARI</div>
        <div style="font-family:'Outfit', sans-serif; font-size: 34px; font-weight: 800; color: #0284c7;"><?= $stats['blog_count'] ?></div>
        <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Sektörel Haber & İçerik</div>
    </div>

    <div class="panel-card" style="margin-bottom: 0;">
        <div style="font-size: 13px; font-weight: 700; color: var(--panel-text-muted); margin-bottom: 8px;">YÖNETİCİLER</div>
        <div style="font-family:'Outfit', sans-serif; font-size: 34px; font-weight: 800; color: #d97706;"><?= $stats['user_count'] ?></div>
        <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Aktif Yönetici Hesabı</div>
    </div>

</div>

<!-- 2. Hızlı Eylemler Çubuğu -->
<div class="panel-card" style="background: linear-gradient(135deg, #15803d 0%, #166534 100%); color: #ffffff;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h3 style="font-family:'Outfit', sans-serif; font-size: 22px; font-weight: 800; margin-bottom: 4px;">
                🎨 Sitenin Rengini ve Tasarımını Canlı Değiştirin
            </h3>
            <p style="font-size: 14px; opacity: 0.9;">
                Tüm sitedeki ana renkleri, butonları, başlıkları ve arka planı tek tıkla özelleştirin.
            </p>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="<?= BASE_URL ?>/index.php?url=<?= PANEL_SLUG ?>/theme" class="btn btn-outline" style="background: #ffffff !important; color: #15803d !important; font-weight: 800; border-color: #ffffff;">
                Tema & Renk Yöneticisi →
            </a>
            <a href="<?= BASE_URL ?>/index.php?url=<?= PANEL_SLUG ?>/products" class="btn" style="background: rgba(255,255,255,0.2) !important; color: #ffffff !important; border: 1.5px solid rgba(255,255,255,0.4); font-weight: 700;">
                + Yeni Ürün Ekle
            </a>
        </div>
    </div>
</div>

<!-- 3. İki Sütun: Son Mesajlar & Son Ürünler -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
    
    <!-- Son Mesajlar -->
    <div class="panel-card">
        <div class="panel-card-header">
            <h3 class="panel-card-title">✉️ Son İletişim Mesajları</h3>
            <a href="<?= BASE_URL ?>/index.php?url=<?= PANEL_SLUG ?>/messages" style="font-size: 13px; font-weight: 700; color: var(--panel-primary);">Tümünü Gör →</a>
        </div>

        <?php if (empty($recentMessages)): ?>
            <p style="color: var(--panel-text-muted); font-size: 14px; text-align: center; padding: 20px;">Henüz mesaj bulunmuyor.</p>
        <?php else: ?>
            <table class="panel-table">
                <thead>
                    <tr>
                        <th>Gönderen</th>
                        <th>Konu</th>
                        <th>Tarih</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentMessages as $msg): ?>
                        <tr>
                            <td>
                                <div style="font-weight: 700;"><?= htmlspecialchars($msg['name']) ?></div>
                                <div style="font-size: 12px; color: #64748b;"><?= htmlspecialchars($msg['email']) ?></div>
                            </td>
                            <td>
                                <span style="font-size: 13px;"><?= htmlspecialchars(mb_strimwidth($msg['subject'], 0, 25, '...')) ?></span>
                            </td>
                            <td style="font-size: 12px; color: #64748b;">
                                <?= date('d.m H:i', strtotime($msg['created_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Son Ürünler -->
    <div class="panel-card">
        <div class="panel-card-header">
            <h3 class="panel-card-title">🍊 Yayındaki Ürünler</h3>
            <a href="<?= BASE_URL ?>/index.php?url=<?= PANEL_SLUG ?>/products" style="font-size: 13px; font-weight: 700; color: var(--panel-primary);">Yönet →</a>
        </div>

        <table class="panel-table">
            <thead>
                <tr>
                    <th>Görsel</th>
                    <th>Ürün Adı</th>
                    <th>Kategori</th>
                    <th>Durum</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentProducts as $prod): ?>
                    <tr>
                        <td style="width: 50px;">
                            <img src="<?= BASE_URL ?>/public/images/<?= htmlspecialchars($prod['image'] ?: 'citrus_showcase.jpg') ?>" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;">
                        </td>
                        <td style="font-weight: 700;">
                            <?= htmlspecialchars($prod['name_tr']) ?>
                        </td>
                        <td>
                            <span style="font-size: 12px; font-weight: 700; color: <?= htmlspecialchars($prod['badge_color'] ?? '#15803d') ?>;">
                                <?= htmlspecialchars($prod['category_name_tr']) ?>
                            </span>
                        </td>
                        <td>
                            <span style="background: #f0fdf4; color: #166534; font-size: 11px; padding: 3px 8px; border-radius: 12px; font-weight: 700;">
                                Aktif
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- 4. Son Gelen Hızlı Teklif Talepleri -->
<div class="panel-card" style="margin-top: 24px;">
    <div class="panel-card-header">
        <div>
            <h3 class="panel-card-title">⚡ Son Gelen Hızlı Teklif Talepleri</h3>
            <p style="color: var(--panel-text-muted); font-size: 13px; margin: 4px 0 0 0;">Müşterilerin web sitesi teklif modalından gönderdiği en son talepler</p>
        </div>
        <a href="<?= BASE_URL ?>/index.php?url=<?= PANEL_SLUG ?>/quotes" style="font-size: 13px; font-weight: 700; color: var(--panel-primary);">Tüm Teklifleri Yönet →</a>
    </div>

    <?php if (empty($recentQuotes)): ?>
        <p style="color: var(--panel-text-muted); font-size: 14px; text-align: center; padding: 24px;">Henüz teklif talebi bulunmuyor.</p>
    <?php else: ?>
        <table class="panel-table">
            <thead>
                <tr>
                    <th>Durum</th>
                    <th>Müşteri Bilgisi</th>
                    <th>Talep Edilen Ürün</th>
                    <th>Miktar</th>
                    <th>Tarih</th>
                    <th style="text-align: right;">İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentQuotes as $rq): ?>
                    <tr>
                        <td style="width: 100px;">
                            <?php if ($rq['status'] === 'pending'): ?>
                                <span style="background: #fed7aa; color: #9a3412; font-size: 11px; padding: 3px 8px; border-radius: 12px; font-weight: 800;">
                                    BEKLİYOR
                                </span>
                            <?php elseif ($rq['status'] === 'replied'): ?>
                                <span style="background: #bbf7d0; color: #166534; font-size: 11px; padding: 3px 8px; border-radius: 12px; font-weight: 800;">
                                    CEVAPLANDI
                                </span>
                            <?php else: ?>
                                <span style="background: #e2e8f0; color: #475569; font-size: 11px; padding: 3px 8px; border-radius: 12px; font-weight: 800;">
                                    TAMAMLANDI
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="font-weight: 700;"><?= htmlspecialchars($rq['full_name']) ?></div>
                            <div style="font-size: 12px; color: #64748b;"><?= htmlspecialchars($rq['email']) ?> | <?= htmlspecialchars($rq['phone']) ?></div>
                        </td>
                        <td style="font-weight: 600; color: #15803d;">
                            <?= htmlspecialchars($rq['product_category']) ?>
                        </td>
                        <td style="font-weight: 700;">
                            <?= htmlspecialchars($rq['quantity']) ?>
                        </td>
                        <td style="font-size: 12px; color: #64748b;">
                            <?= date('d.m.Y H:i', strtotime($rq['created_at'])) ?>
                        </td>
                        <td style="text-align: right;">
                            <a href="<?= BASE_URL ?>/index.php?url=<?= PANEL_SLUG ?>/quotes" class="btn btn-outline" style="padding: 5px 12px; font-size: 12px;">
                                İncele / Fiyat Ver →
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

