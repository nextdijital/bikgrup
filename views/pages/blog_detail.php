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

<div class="section blog-detail-section">
    <div class="container" style="max-width: 900px;">
        
        <!-- Breadcrumb -->
        <div class="blog-detail-breadcrumb">
            <a href="<?= BASE_URL ?>/"><?= __('nav_home') ?></a> &gt; 
            <a href="<?= BASE_URL ?>/blog"><?= __('nav_blog') ?></a> &gt; 
            <span style="color: var(--theme-text); font-weight: 600;"><?= htmlspecialchars($postTitle) ?></span>
        </div>

        <div class="blog-detail-meta">
            <span class="blog-category-badge">
                <?= $currentLang === 'en' ? 'Industry Review' : 'Sektörel İnceleme' ?>
            </span>
            <span>⏱ <?= htmlspecialchars($post['read_time']) ?></span>
            <span>•</span>
            <span><?= date('d.m.Y', strtotime($post['created_at'])) ?></span>
            <span>•</span>
            <span><?= htmlspecialchars($post['author']) ?></span>
        </div>

        <h1 class="blog-detail-title">
            <?= htmlspecialchars($postTitle) ?>
        </h1>

        <p class="blog-detail-excerpt">
            <?= htmlspecialchars($postExcerpt) ?>
        </p>

        <!-- Kapak Görseli -->
        <?php
        $detailImg = $post['image'] ?: 'blog_cold_chain.png';
        $detailImgSrc = (str_starts_with($detailImg, 'http://') || str_starts_with($detailImg, 'https://'))
            ? $detailImg
            : BASE_URL . '/public/images/' . $detailImg;
        ?>
        <div class="blog-detail-hero-img-box">
            <img src="<?= htmlspecialchars($detailImgSrc) ?>" alt="<?= htmlspecialchars($postTitle) ?>" loading="eager">
        </div>

        <!-- Gövde İçeriği -->
        <div class="blog-detail-body">
            <?= $postContent ?>
        </div>

        <!-- Yazar Kutusu & Paylaşım -->
        <div class="blog-author-box">
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

<style>
.blog-detail-section {
    padding-top: 140px;
    padding-bottom: 70px;
}
.blog-detail-breadcrumb {
    font-size: 14px;
    color: var(--theme-text-muted);
    margin-bottom: 22px;
    line-height: 1.5;
    word-break: break-word;
}
.blog-detail-breadcrumb a {
    color: var(--theme-text-muted);
    text-decoration: none;
    transition: color 0.2s;
}
.blog-detail-breadcrumb a:hover {
    color: var(--theme-primary, #15803d);
}
.blog-detail-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
    font-size: 13.5px;
    color: var(--theme-text-muted);
}
.blog-detail-title {
    font-family: var(--font-heading);
    font-size: clamp(24px, 4.2vw, 40px);
    font-weight: 800;
    line-height: 1.3;
    color: var(--theme-text);
    margin-bottom: 22px;
    word-break: break-word;
    letter-spacing: -0.5px;
}
.blog-detail-excerpt {
    font-size: clamp(15.5px, 2.2vw, 19px);
    color: var(--theme-text-muted);
    line-height: 1.65;
    margin-bottom: 32px;
    font-weight: 500;
    word-break: break-word;
}
.blog-detail-hero-img-box {
    border-radius: clamp(16px, 3vw, 26px);
    overflow: hidden;
    margin-bottom: 38px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    width: 100%;
    aspect-ratio: 16 / 9;
    max-height: 480px;
    background: #f1f5f9;
}
.blog-detail-hero-img-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.blog-detail-body {
    font-size: clamp(15.5px, 2vw, 17.5px);
    line-height: 1.85;
    color: #334155;
    margin-bottom: 55px;
    word-break: break-word;
}
.blog-detail-body p {
    margin-bottom: 20px;
}
.blog-detail-body img {
    max-width: 100%;
    height: auto;
    border-radius: 14px;
    margin: 20px 0;
}
.blog-detail-body .lead {
    font-size: clamp(16.5px, 2.4vw, 19px) !important;
    font-weight: 600 !important;
    color: #0f172a !important;
    line-height: 1.6 !important;
    margin-bottom: 24px !important;
}
.blog-detail-body .bikgrup-sector-note {
    background: #f0fdf4;
    border-left: 4px solid #16a34a;
    border-radius: 14px;
    padding: clamp(16px, 3.5vw, 24px);
    margin: 30px 0;
    box-shadow: 0 4px 14px rgba(22, 163, 74, 0.06);
}
.blog-detail-body .bikgrup-sector-note h4 {
    margin: 0 0 10px 0;
    font-size: clamp(15.5px, 2.5vw, 17px);
    font-weight: 800;
    color: #166534;
    display: flex;
    align-items: center;
    gap: 8px;
}
.blog-detail-body .bikgrup-sector-note p {
    margin: 0;
    font-size: clamp(14px, 2vw, 15px);
    color: #1e3a2b;
    line-height: 1.65;
}
.blog-detail-body .news-verification-footer {
    margin-top: 32px;
    padding-top: 18px;
    border-top: 1.5px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 13px;
    color: #64748b;
}
.blog-author-box {
    background: #f8fafc;
    border-radius: 20px;
    padding: clamp(20px, 3vw, 30px);
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 1px solid #e2e8f0;
    flex-wrap: wrap;
    gap: 16px;
}

@media (max-width: 768px) {
    .blog-detail-section {
        padding-top: 85px !important;
        padding-bottom: 35px !important;
    }
    .blog-detail-breadcrumb {
        font-size: 12px !important;
        margin-bottom: 14px !important;
    }
    .blog-detail-meta {
        font-size: 12px !important;
        gap: 8px !important;
        margin-bottom: 16px !important;
    }
    .blog-detail-title {
        font-size: 22px !important;
        line-height: 1.34 !important;
        margin-bottom: 14px !important;
        letter-spacing: -0.3px !important;
    }
    .blog-detail-excerpt {
        font-size: 14.5px !important;
        line-height: 1.55 !important;
        margin-bottom: 20px !important;
    }
    .blog-detail-hero-img-box {
        border-radius: 14px !important;
        margin-bottom: 24px !important;
        aspect-ratio: 16 / 10 !important;
        max-height: 250px !important;
    }
    .blog-detail-body {
        font-size: 15px !important;
        line-height: 1.75 !important;
        margin-bottom: 35px !important;
    }
    .blog-detail-body .news-verification-footer {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 8px !important;
    }
    .blog-author-box {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 14px !important;
        padding: 18px !important;
        border-radius: 14px !important;
    }
    .blog-author-box .btn {
        width: 100% !important;
        text-align: center !important;
        justify-content: center !important;
        display: flex !important;
    }
}
</style>
