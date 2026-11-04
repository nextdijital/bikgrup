<?php
/**
 * BİKGRUP - BİK GRUP Yönetim Paneli Gelen İletişim Mesajları
 */

declare(strict_types=1);
?>

<div class="panel-card">
    <div class="panel-card-header">
        <div>
            <h2 class="panel-card-title">✉️ Gelen İletişim ve Teklif Mesajları</h2>
            <p style="color: var(--panel-text-muted); font-size: 14px; margin-top: 4px;">
                Web sitesi iletişim formundan gelen müşteri ve tedarikçi talepleri.
            </p>
        </div>
    </div>

    <?php if (empty($messages)): ?>
        <div style="text-align: center; padding: 50px 20px; color: var(--panel-text-muted);">
            <div style="font-size: 40px; margin-bottom: 10px;">📭</div>
            <p style="font-size: 16px;">Henüz gelen bir iletişim mesajı bulunmamaktadır.</p>
        </div>
    <?php else: ?>
        <table class="panel-table">
            <thead>
                <tr>
                    <th>Durum</th>
                    <th>Gönderen & İletişim</th>
                    <th>Konu & Mesaj</th>
                    <th>Tarih</th>
                    <th style="text-align: right;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $msg): ?>
                    <tr style="<?= empty($msg['is_read']) ? 'background: rgba(34, 197, 94, 0.04); font-weight: 600;' : '' ?>">
                        <td style="width: 80px;">
                            <?php if (empty($msg['is_read'])): ?>
                                <span style="background: #fef3c7; color: #92400e; font-size: 11px; padding: 3px 8px; border-radius: 12px; font-weight: 800;">
                                    YENİ
                                </span>
                            <?php else: ?>
                                <span style="color: #94a3b8; font-size: 12px;">Okundu</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="font-size: 15px; color: var(--panel-text);"><?= htmlspecialchars($msg['name']) ?></div>
                            <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                                ✉️ <?= htmlspecialchars($msg['email']) ?>
                                <?php if (!empty($msg['phone'])): ?>
                                    | 📞 <?= htmlspecialchars($msg['phone']) ?>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: var(--panel-text); margin-bottom: 4px;">
                                <?= htmlspecialchars($msg['subject']) ?>
                            </div>
                            <div style="font-size: 13px; color: #475569; line-height: 1.5; max-width: 450px;">
                                <?= nl2br(htmlspecialchars($msg['message'])) ?>
                            </div>
                        </td>
                        <td style="font-size: 12px; color: #64748b; white-space: nowrap;">
                            <?= date('d.m.Y H:i', strtotime($msg['created_at'])) ?>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <?php if (empty($msg['is_read'])): ?>
                                <a href="<?= BASE_URL ?>/index.php?url=<?= PANEL_SLUG ?>/read-message&id=<?= $msg['id'] ?>" class="btn btn-sm btn-outline">
                                    ✓ Okundu Yap
                                </a>
                            <?php endif; ?>
                            <a href="<?= BASE_URL ?>/index.php?url=<?= PANEL_SLUG ?>/delete-message&id=<?= $msg['id'] ?>" 
                               data-confirm-delete="Bu mesajı silmek istediğinize emin misiniz?" 
                               class="btn btn-sm btn-danger">
                                Sil 🗑️
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

