<?php
/**
 * BİKGRUP - Blog Detay Sayfası
 * SEO Uyumlu Temiz URL'ler
 */

declare(strict_types=1);

$currentLang = Lang::current();
$postTitle = $post['title_' . $currentLang] ?? $post['title_tr'];
$postExcerpt = $post['excerpt_' . $currentLang] ?? $post['excerpt_tr'];
$postContent = $post['content_' . $currentLang] ?? $post['content_tr'];
?>

<div class="section" style="padding-top: 150px;">
    <div class="container" style="max-width: 900px;">
        
        <!-- Breadcrumb -->
        <div style="font-size: 14px; color: var(--theme-text-muted); margin-bottom: 24px;">
            <a href="<?= BASE_URL ?>/"><?= __('nav_home') ?></a> &gt; 
            <a href="<?= BASE_URL ?>/blog"><?= __('nav_blog') ?></a> &gt; 
            <span style="color: var(--theme-text); font-weight: 600;"><?= htmlspecialchars($postTitle) ?></span>
        </div>

        <div class="blog-meta" style="margin-bottom: 20px; align-items: center; flex-wrap: wrap; gap: 14px;">
            <span class="blog-category-badge">
                <?= $currentLang === 'en' ? 'Industry Review' : 'Sektörel İnceleme' ?>
            </span>
            <span>⏱ <?= htmlspecialchars($post['read_time']) ?></span>
            <span>•</span>
            <span><?= date('d.m.Y', strtotime($post['created_at'])) ?></span>
            <span>•</span>
            <span><?= htmlspecialchars($post['author']) ?></span>
        </div>

        <h1 style="font-family: var(--font-heading); font-size: 42px; font-weight: 800; line-height: 1.25; color: var(--theme-text); margin-bottom: 24px;">
            <?= htmlspecialchars($postTitle) ?>
        </h1>

        <p style="font-size: 20px; color: var(--theme-text-muted); line-height: 1.6; margin-bottom: 36px; font-weight: 500;">
            <?= htmlspecialchars($postExcerpt) ?>
        </p>

        <!-- Kapak Görseli -->
        <?php
        $detailImg = $post['image'] ?: 'blog_cold_chain.png';
        $detailImgSrc = (str_starts_with($detailImg, 'http://') || str_starts_with($detailImg, 'https://'))
            ? $detailImg
            : BASE_URL . '/public/images/' . $detailImg;
        ?>
        <div style="border-radius: 28px; overflow: hidden; margin-bottom: 40px; box-shadow: var(--shadow-md); height: 440px;">
            <img src="<?= htmlspecialchars($detailImgSrc) ?>" alt="<?= htmlspecialchars($postTitle) ?>" style="width: 100%; height: 100%; object-fit: cover;">
        </div>

        <!-- Gövde İçeriği -->
        <div style="font-size: 17px; line-height: 1.85; color: #334155; margin-bottom: 60px;">
            <?= $postContent ?>
        </div>

        <!-- Yazar Kutusu & Paylaşım -->
        <div style="background: #f8fafc; border-radius: 20px; padding: 30px; display: flex; align-items: center; justify-content: space-between; border: 1px solid #e2e8f0; flex-wrap: wrap; gap: 16px;">
            <div>
                <div style="font-size: 13px; color: var(--theme-text-muted); font-weight: 600;"><?= $currentLang === 'en' ? 'Published by' : 'Yayınlayan' ?></div>
                <div style="font-size: 18px; font-weight: 800; color: var(--theme-text);"><?= htmlspecialchars($post['author']) ?></div>
            </div>
            <a href="<?= BASE_URL ?>/blog" class="btn btn-outline" style="border-radius: 9999px;">
                <?= $currentLang === 'en' ? 'Back to All Articles' : 'Tüm Yazılara Dön' ?>
            </a>
        </div>

    </div>
</div>
