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
            header("Location: " . BASE_URL . "/index.php?url=blog");
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
