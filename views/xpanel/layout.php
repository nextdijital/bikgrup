<?php
/**
 * BİKGRUP - BİK GRUP Yönetim Paneli Ana Arayüz Şablonu (Layout)
 */

declare(strict_types=1);

$user = Auth::user();
$unreadMsgCount = Message::unreadCount();
$pendingQuoteCount = class_exists('QuoteRequest') ? QuoteRequest::pendingCount() : 0;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'BİK GRUP | Yönetim Paneli') ?></title>
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/panel.css?v=<?= file_exists(PUBLIC_PATH . '/css/panel.css') ? filemtime(PUBLIC_PATH . '/css/panel.css') : time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/animations.css?v=<?= file_exists(PUBLIC_PATH . '/css/animations.css') ? filemtime(PUBLIC_PATH . '/css/animations.css') : time() ?>">
</head>
<body class="panel-body">

    <!-- Sol Menü (Sidebar) -->
    <aside class="panel-sidebar">
        <div class="panel-brand">
            <span class="logo-text">BİKGRUP</span>
        </div>

        <nav class="panel-nav">
            <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>" class="panel-nav-link <?= ($activeMenu ?? '') === 'dashboard' ? 'active' : '' ?>">
                <span class="nav-icon">📊</span>
                <span>Genel Bakış</span>
            </a>

            <!-- Dinamik Tema ve Canlı Renk Yönetimi -->
            <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/theme" class="panel-nav-link <?= ($activeMenu ?? '') === 'theme' ? 'active' : '' ?>">
                <span class="nav-icon">🎨</span>
                <span>Dinamik Tema & Renkler</span>
            </a>

            <!-- Genel Site Ayarları & Logo & İletişim -->
            <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/settings" class="panel-nav-link <?= ($activeMenu ?? '') === 'settings' ? 'active' : '' ?>">
                <span class="nav-icon">⚙️</span>
                <span>Genel Ayarlar & Logo</span>
            </a>

            <!-- Dinamik Sayfa Bölümleri -->
            <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/pages" class="panel-nav-link <?= ($activeMenu ?? '') === 'pages' ? 'active' : '' ?>">
                <span class="nav-icon">📄</span>
                <span>Sayfa ve Alan Yönetimi</span>
            </a>

            <!-- Footer Yönetimi -->
            <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/footer" class="panel-nav-link <?= ($activeMenu ?? '') === 'footer' ? 'active' : '' ?>">
                <span class="nav-icon">📑</span>
                <span>Footer Yönetimi</span>
            </a>

            <!-- Ürünler -->
            <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/products" class="panel-nav-link <?= ($activeMenu ?? '') === 'products' ? 'active' : '' ?>">
                <span class="nav-icon">🍊</span>
                <span>Ürünler & Hasat</span>
            </a>

            <!-- Blog -->
            <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/blog" class="panel-nav-link <?= ($activeMenu ?? '') === 'blog' ? 'active' : '' ?>">
                <span class="nav-icon">📰</span>
                <span>Blog & Haberler</span>
            </a>

            <!-- Medya & Galeri -->
            <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/gallery" class="panel-nav-link <?= ($activeMenu ?? '') === 'gallery' ? 'active' : '' ?>">
                <span class="nav-icon">🖼️</span>
                <span>Medya & Galeri</span>
            </a>

            <!-- KULLANICI TALEBİ: Hızlı Teklif Talepleri & Gelen Mesajlar -->
            <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/quotes" class="panel-nav-link <?= ($activeMenu ?? '') === 'quotes' ? 'active' : '' ?>">
                <span class="nav-icon">⚡</span>
                <span>Hızlı Teklifler</span>
                <?php if ($pendingQuoteCount > 0): ?>
                    <span style="background:#ea580c; color:#fff; font-size:11px; padding:2px 7px; border-radius:10px; margin-left:auto; font-weight:800;">
                        <?= $pendingQuoteCount ?>
                    </span>
                <?php endif; ?>
            </a>

            <!-- Yöneticiler & Yetkiler -->
            <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/users" class="panel-nav-link <?= ($activeMenu ?? '') === 'users' ? 'active' : '' ?>">
                <span class="nav-icon">👥</span>
                <span>Yöneticiler & Yetki</span>
            </a>

            <div style="border-top: 1px solid rgba(255,255,255,0.08); margin: 10px 0;"></div>

            <!-- Siteyi Canlı Gör -->
            <a href="<?= BASE_URL ?>/index.php" target="_blank" class="panel-nav-link">
                <span class="nav-icon">🌐</span>
                <span>Siteyi Canlı Gör ↗</span>
            </a>

            <!-- Çıkış -->
            <a href="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/logout" class="panel-nav-link" style="color: #f87171;">
                <span class="nav-icon">🚪</span>
                <span>Güvenli Çıkış</span>
            </a>
        </nav>

        <!-- Oturum Açmış Yönetici Bilgisi -->
        <div class="panel-user-card">
            <div class="panel-user-avatar">
                <?= strtoupper(substr($user['name'] ?? 'A', 0, 1)) ?>
            </div>
            <div style="overflow: hidden;">
                <div style="font-weight: 700; font-size: 14px; color: #ffffff; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">
                    <?= htmlspecialchars($user['name'] ?? 'Yönetici') ?>
                </div>
                <div style="font-size: 12px; color: #94a3b8; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">
                    <?= htmlspecialchars($user['email'] ?? '') ?>
                </div>
            </div>
        </div>
    </aside>

    <!-- Ana Panel İçeriği -->
    <div class="panel-main">
        
        <!-- Topbar -->
        <header class="panel-topbar">
            <div style="font-family: 'Outfit', sans-serif; font-size: 20px; font-weight: 700;">
                <?= htmlspecialchars($pageTitle ?? 'Yönetim Paneli') ?>
            </div>

            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 13.5px; font-weight: 700; color: #334155;">
                    <?= htmlspecialchars($user['name'] ?? 'Yönetici') ?>
                </span>
            </div>
        </header>

        <!-- İçerik Alanı -->
        <main class="panel-container">
            
            <!-- Başarı Mesajı -->
            <?php if (!empty($_SESSION['panel_success'])): ?>
                <div style="background: #f0fdf4; border: 1.5px solid #86efac; color: #166534; padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; display:flex; justify-content:space-between; align-items:center;">
                    <span>✓ <?= htmlspecialchars($_SESSION['panel_success']) ?></span>
                    <button onclick="this.parentElement.remove();" style="background:none; border:none; cursor:pointer; color:#166534; font-size:18px;">✕</button>
                </div>
                <?php unset($_SESSION['panel_success']); ?>
            <?php endif; ?>

            <!-- Hata Mesajı -->
            <?php if (!empty($_SESSION['panel_error'])): ?>
                <div style="background: #fef2f2; border: 1.5px solid #fca5a5; color: #991b1b; padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; display:flex; justify-content:space-between; align-items:center;">
                    <span>⚠️ <?= htmlspecialchars($_SESSION['panel_error']) ?></span>
                    <button onclick="this.parentElement.remove();" style="background:none; border:none; cursor:pointer; color:#991b1b; font-size:18px;">✕</button>
                </div>
                <?php unset($_SESSION['panel_error']); ?>
            <?php endif; ?>

            <!-- Görünüm Çıktısı -->
            <?= $panelContent ?? '' ?>

        </main>
    </div>

    <!-- Panel JavaScript -->
    <script src="<?= BASE_URL ?>/public/js/panel.js?v=<?= file_exists(PUBLIC_PATH . '/js/panel.js') ? filemtime(PUBLIC_PATH . '/js/panel.js') : time() ?>"></script>
</body>
</html>

