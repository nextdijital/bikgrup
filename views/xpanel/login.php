<?php
/**
 * BİK GRUP - Yönetim Paneli Güvenli Giriş Ekranı
 * PHP 8.2 / 8.3 / 8.4 Uyumlu
 */

declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BİK GRUP | Yönetim Paneli Girişi</title>
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/panel.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body.login-body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f6fa;
            margin: 0;
            padding: 20px;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            box-sizing: border-box;
        }

        .login-card {
            width: 100%;
            max-width: 390px;
            background: #ffffff;
            border-radius: 20px;
            padding: 38px 32px 30px;
            box-shadow: 0 15px 35px rgba(15, 23, 42, 0.06), 0 1px 3px rgba(15, 23, 42, 0.04);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-sizing: border-box;
            text-align: left;
        }

        .login-logo-wrap {
            text-align: center;
            margin-bottom: 26px;
        }

        .login-logo-title {
            font-family: 'Outfit', sans-serif;
            font-size: 27px;
            font-weight: 900;
            letter-spacing: -0.5px;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
        }

        .login-logo-title .brand-accent {
            color: #15803d;
        }

        .login-logo-sub {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
            margin-top: 4px;
        }

        .login-form-group {
            margin-bottom: 18px;
        }

        .login-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 6px;
        }

        .login-input {
            width: 100%;
            padding: 13px 16px;
            border-radius: 8px;
            border: 1.5px solid transparent;
            background: #eef2f9;
            color: #1e293b;
            font-size: 14.5px;
            font-family: inherit;
            box-sizing: border-box;
            transition: all 0.2s ease;
        }

        .login-input:focus {
            background: #ffffff;
            border-color: #dc2626;
            outline: none;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
        }

        .login-btn {
            width: 100%;
            padding: 13px 20px;
            border-radius: 8px;
            background: #c92a2a;
            color: #ffffff;
            font-size: 14.5px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            box-shadow: 0 4px 14px rgba(201, 42, 42, 0.25);
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
            font-family: inherit;
        }

        .login-btn:hover {
            background: #b02121;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(201, 42, 42, 0.35);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .login-footer {
            margin-top: 26px;
            text-align: center;
            border-top: 1px solid #f1f5f9;
            padding-top: 18px;
        }

        .login-back-link {
            color: #64748b;
            font-size: 12.5px;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .login-back-link:hover {
            color: #0f172a;
        }

        .login-copy {
            color: #94a3b8;
            font-size: 11.5px;
            margin-top: 8px;
        }

        .login-alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 18px;
            text-align: center;
            font-weight: 600;
        }
    </style>
</head>
<body class="login-body">

    <div class="login-card">
        
        <div class="login-logo-wrap">
            <div class="login-logo-title">
                <span style="font-size: 22px;">🌿</span>
                <span>BİK<span class="brand-accent">GRUP</span></span>
            </div>
            <div class="login-logo-sub">Yönetim Paneli</div>
        </div>

        <?php if (!empty($_SESSION['panel_error'])): ?>
            <div class="login-alert-error">
                <?= htmlspecialchars($_SESSION['panel_error']) ?>
            </div>
            <?php unset($_SESSION['panel_error']); ?>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/<?= PANEL_SLUG ?>/do-login" method="POST" autocomplete="off">
            <?= Security::csrfField() ?>

            <div class="login-form-group">
                <label class="login-label">E-Posta Adresi</label>
                <input type="email" name="email" value="" required class="login-input" placeholder="admin@bikgrup.com" autocomplete="off">
            </div>

            <div class="login-form-group" style="margin-bottom: 0;">
                <label class="login-label">Yönetici Şifresi</label>
                <input type="password" name="password" value="" required class="login-input" placeholder="••••••••" autocomplete="new-password">
            </div>

            <button type="submit" class="login-btn">
                <span style="font-size: 15px;">➜</span>
                <span>Giriş Yap</span>
            </button>
        </form>

        <div class="login-footer">
            <a href="<?= BASE_URL ?>/" class="login-back-link">
                ← Web Sitesine Geri Dön
            </a>
            <div class="login-copy">
                © 2026 BİK GRUP Tüm hakları saklıdır.
            </div>
        </div>

    </div>

</body>
</html>

