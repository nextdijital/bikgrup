<?php
/**
 * BİKGRUP - Blog ve Haberler Kontrolcüsü
 */

declare(strict_types=1);

require_once ROOT_PATH . '/core/View.php';
require_once ROOT_PATH . '/app/Models/BlogPost.php';

class BlogController
{
    public function index(): void
    {
        // Otomatik Güncel Tarım Haberleri Taraması (Günde 1 kez arka planda sessizce kontrol eder)
        try {
            require_once ROOT_PATH . '/app/Services/AgriNewsService.php';
            $syncInfo = AgriNewsService::getLastSyncInfo();
            $lastTime = !empty($syncInfo['last_sync']) ? strtotime($syncInfo['last_sync']) : 0;
            if ($lastTime < (time() - 43200)) { // 12 saat
                AgriNewsService::sync(3);
            }
        } catch (\Throwable $e) {}

        $posts = BlogPost::all(true);

        View::render('blog', [
            'pageTitle' => __('nav_blog') . ' | ' . Settings::get('site_name', 'BİKGRUP'),
            'posts'     => $posts,
            'activeNav' => 'blog'
        ]);
    }

    public function detail(string $slug): void
    {
        $post = BlogPost::findBySlug($slug);
        if (!$post) {
            header("Location: " . BASE_URL . "/blog");
            exit;
        }

        $recentPosts = BlogPost::all(true, 3);
        $recentPosts = array_filter($recentPosts, fn($p) => (int)$p['id'] !== (int)$post['id']);

        $lang = Lang::current();
        $postTitle = $post['title_' . $lang] ?? $post['title_tr'];

        View::render('blog_detail', [
            'pageTitle'   => $postTitle . ' | ' . Settings::get('site_name', 'BİKGRUP'),
            'post'        => $post,
            'recentPosts' => $recentPosts,
            'activeNav'   => 'blog'
        ]);
    }
}
