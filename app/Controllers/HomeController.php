<?php
/**
 * BİKGRUP - Anasayfa Kontrolcüsü
 */

declare(strict_types=1);

require_once ROOT_PATH . '/core/View.php';
require_once ROOT_PATH . '/app/Models/Product.php';
require_once ROOT_PATH . '/app/Models/Category.php';
require_once ROOT_PATH . '/app/Models/BlogPost.php';
require_once ROOT_PATH . '/app/Models/PageSection.php';

class HomeController
{
    public function index(): void
    {
        $categories = Category::all(true);
        $featuredProducts = Product::featured(12);
        $recentPosts = BlogPost::all(true, 3);
        $sections = PageSection::getPageSections('home');

        View::render('home', [
            'pageTitle'        => __('nav_home') . ' | ' . Settings::get('site_name', 'BİKGRUP'),
            'categories'       => $categories,
            'featuredProducts' => $featuredProducts,
            'recentPosts'      => $recentPosts,
            'sections'         => $sections,
            'activeNav'        => 'home'
        ]);
    }
}
