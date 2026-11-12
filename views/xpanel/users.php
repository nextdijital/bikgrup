<?php
/**
 * BİKGRUP - BİK GRUP Yönetim Paneli Yönetici Hesapları ve Yetki Yönetimi
 */

declare(strict_types=1);

$currentUser = Auth::user();
$currentUserId = Auth::id();
?>

<div class="panel-card">
    <div class="panel-card-header">
        <div>
            <h2 class="panel-card-title">👥 Yönetici Hesapları & Yetkiler</h2>
            <p style="color: var(--panel-text-muted); font-size: 14px; margin-top: 4px;">
                Sistem yöneticilerini ve erişim yetkilerini buradan yönetebilirsiniz.
            </p>
        </div>
        <div>
            <button onclick="openNewUserModal()" class="btn btn-primary" style="padding: 10px 22px;">
                + Yeni Yönetici Ekle
            </button>
        </div>
    </div>

    <table class="panel-table">
        <thead>
            <tr>
                <th>Yönetici</th>
                <th>E-Posta</th>
                <th>Rol & Statü</th>
                <th>Son Giriş</th>
                <th style="text-align: right;">İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <?php 
                    $isImmutable = (int)$u['is_immutable'] === 1 || strtolower($u['email']) === 'suleymannozer0@gmail.com'; 
                    $isSelf = ((int)$currentUserId === (int)$u['id']);
                ?>
                <tr style="<?= $isImmutable ? 'background: #fffdf5;' : '' ?>">
                    <td>
                        <div>
                            <div style="font-weight: 700; font-size: 15px; color: var(--panel-text);">
                                <?= htmlspecialchars($u['name']) ?>
                            </div>
                            <div style="font-size: 12px; color: #64748b;">
                                ID: #<?= $u['id'] ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-family: monospace; font-size: 14px; font-weight: 600; color: var(--panel-text);">
                            <?= htmlspecialchars($u['email']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($isImmutable): ?>
                            <span style="background: #f0fdf4; color: #166534; font-size: 12px; padding: 4px 12px; border-radius: 20px; font-weight: 800; border: 1px solid #bbf7d0;">
                                Süper Admin
                            </span>
                        <?php else: ?>
                            <span style="background: #f1f5f9; color: #334155; font-size: 12px; padding: 4px 12px; border-radius: 20px; font-weight: 700; border: 1px solid #e2e8f0;">
                                Standart Yönetici
                            </span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size: 13px; color: #64748b;">
                        <?= $u['last_login'] ? date('d.m.Y H:i', strtotime($u['last_login'])) : '—' ?>
                    </td>
                    <td style="text-align: right; white-space: nowrap;">
                        <?php if ($isImmutable): ?>
                            <?php if ($isSelf): ?>
                                <button type="button" class="btn btn-sm btn-outline" onclick="openEditUserModal(<?= htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8') ?>)">
                                    Düzenle ✏️
                                </button>
                            <?php else: ?>
                                <span style="color: #64748b; font-size: 12px; font-weight: 700; background: #f1f5f9; padding: 5px 12px; border-radius: 8px; border: 1px solid #e2e8f0; display: inline-flex; align-items: center; gap: 4px;">
                                    🔒 Düzenlenemez
                                </span>
                            <?php endif; ?>
                        <?php else: ?>
                            <button type="button" class="btn btn-sm btn-outline" onclick="openEditUserModal(<?= htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8') ?>)">
                                Düzenle ✏️
                            </button>
                            <?php if (!$isSelf): ?>
                                <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/delete-user?id=<?= $u['id'] ?>" 
                                   data-confirm-delete="<?= htmlspecialchars($u['name']) ?> isimli yöneticiyi silmek istediğinize emin misiniz?" 
                                   class="btn btn-sm btn-danger" style="margin-left: 6px;">
                                    Sil 🗑️
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Yönetici Ekleme / Düzenleme Modalı -->
<div id="userModal" style="display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #ffffff; border-radius: 20px; width: 100%; max-width: 520px; padding: 30px; box-shadow: 0 25px 60px rgba(0,0,0,0.25);">
        
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 14px;">
            <h3 id="userModalTitle" style="font-family:'Outfit', sans-serif; font-size: 20px; font-weight: 800; color:#0f172a; margin:0;">+ Yeni Yönetici Ekle</h3>
            <button type="button" onclick="closeModal('userModal')" style="background:none; border:none; font-size: 20px; cursor: pointer; color:#94a3b8;">✕</button>
        </div>

        <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/save-user" method="POST">
            <?= Security::csrfField() ?>
            <input type="hidden" name="id" id="user_id">
            <input type="hidden" name="role" id="user_role" value="admin">

            <div style="margin-bottom: 16px;">
                <label class="form-label">Yönetici Adı Soyadı *</label>
                <input type="text" name="name" id="user_name" required placeholder="Ad Soyad" class="form-control">
            </div>

            <div style="margin-bottom: 16px;">
                <label class="form-label">E-Posta Adresi *</label>
                <input type="email" name="email" id="user_email" required placeholder="admin@bikgrup.com" class="form-control">
            </div>

            <div style="margin-bottom: 16px;">
                <label class="form-label" id="userPasswordLabel">Giriş Şifresi *</label>
                <input type="password" name="password" id="user_password" placeholder="••••••••" class="form-control">
                <small id="userPasswordHelp" style="color: #64748b; font-size: 12px; display: none; margin-top: 4px;">
                    * Şifreyi değiştirmek istemiyorsanız boş bırakınız.
                </small>
            </div>

            <div style="margin-bottom: 24px;">
                <label class="form-label">Yetki Seviyesi</label>
                <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 12px 16px; font-size: 13.5px; color: #334155; display: flex; align-items: center; gap: 10px;">
                    <span style="background: #dbeafe; color: #1e40af; font-size: 11.5px; font-weight: 800; padding: 3px 9px; border-radius: 6px;">
                        YÖNETİCİ
                    </span>
                    <span style="font-size: 13px; color: #64748b;">Standart Yönetici (İçerik, Ürün ve Teklif Yönetimi)</span>
                </div>
            </div>

            <div style="text-align: right; display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                <button type="button" onclick="closeModal('userModal')" class="btn btn-outline">İptal</button>
                <button type="submit" id="userSubmitBtn" class="btn btn-primary">👤 Yöneticiyi Kaydet</button>
            </div>
        </form>

    </div>
</div>

<script>
function openNewUserModal() {
    document.getElementById('user_id').value = '';
    document.getElementById('user_name').value = '';
    document.getElementById('user_email').value = '';
    document.getElementById('user_password').value = '';
    document.getElementById('user_password').required = true;
    document.getElementById('userPasswordLabel').textContent = 'Giriş Şifresi *';
    document.getElementById('userPasswordHelp').style.display = 'none';
    document.getElementById('user_role').value = 'admin';
    document.getElementById('userModalTitle').textContent = '+ Yeni Yönetici Ekle';
    document.getElementById('userSubmitBtn').textContent = '👤 Yöneticiyi Ekle';
    openModal('userModal');
    setTimeout(() => { const el = document.getElementById('user_name'); if (el) el.focus(); }, 60);
}

function openEditUserModal(u) {
    document.getElementById('user_id').value = u.id;
    document.getElementById('user_name').value = u.name;
    document.getElementById('user_email').value = u.email;
    document.getElementById('user_password').value = '';
    document.getElementById('user_password').required = false;
    document.getElementById('userPasswordLabel').textContent = 'Yeni Şifre (Opsiyonel)';
    document.getElementById('userPasswordHelp').style.display = 'block';
    document.getElementById('user_role').value = (u.role === 'superadmin' ? 'superadmin' : 'admin');
    document.getElementById('userModalTitle').textContent = '✏️ Yöneticiyi Düzenle: ' + u.name;
    document.getElementById('userSubmitBtn').textContent = '💾 Değişiklikleri Kaydet';
    openModal('userModal');
}
</script>
