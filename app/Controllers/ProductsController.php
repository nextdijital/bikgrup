<?php
/**
 * BİKGRUP - Ürünler Kontrolcüsü
 */

declare(strict_types=1);

require_once ROOT_PATH . '/core/View.php';
require_once ROOT_PATH . '/app/Models/Product.php';
require_once ROOT_PATH . '/app/Models/Category.php';

class ProductsController
{
    public function index(): void
    {
        $categorySlug = $_GET['kategori'] ?? $_GET['category'] ?? null;
        $selectedCategory = null;
        $categoryId = null;

        if (!empty($categorySlug)) {
            $selectedCategory = Category::findBySlug($categorySlug);
            if ($selectedCategory) {
                $categoryId = (int)$selectedCategory['id'];
            }
        }

        $categories = Category::all(true);
        $products = Product::all(true, $categoryId);

        $lang = Lang::current();
        $titleName = $selectedCategory ? ($selectedCategory['name_' . $lang] ?? $selectedCategory['name_tr']) : __('nav_products');

        View::render('products', [
            'pageTitle'        => $titleName . ' | ' . Settings::get('site_name', 'BİKGRUP'),
            'categories'       => $categories,
            'products'         => $products,
            'selectedCategory' => $selectedCategory,
            'activeNav'        => 'products'
        ]);
    }

    public function detail(string $slug): void
    {
        $product = Product::findBySlug($slug);
        if (!$product) {
            header("Location: " . BASE_URL . "/index.php?url=products");
            exit;
        }

        $relatedProducts = Product::all(true, (int)$product['category_id'], 4);
        // Kendisini hariç tut
        $relatedProducts = array_filter($relatedProducts, fn($p) => (int)$p['id'] !== (int)$product['id']);

        $lang = Lang::current();
        $prodName = $product['name_' . $lang] ?? $product['name_tr'];

        View::render('product_detail', [
            'pageTitle'       => $prodName . ' | ' . Settings::get('site_name', 'BİKGRUP'),
            'product'         => $product,
            'relatedProducts' => $relatedProducts,
            'activeNav'       => 'products'
        ]);
    }
}
