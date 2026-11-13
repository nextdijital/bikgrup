<?php
/**
 * BİKGRUP - Görünüm (View) Render Motoru
 * PHP 8.2 / 8.3 / 8.4 Uyumlu
 */

declare(strict_types=1);

class View
{
    /**
     * Ön Yüz Şablonu Render Eder
     */
    public static function render(string $viewName, array $data = [], string $layout = 'main'): void
    {
        // Temel model bağımlılıklarını garantile (Linux sunucularda autoloader eksikliği kaynaklı 500 hatalarını önler)
        require_once ROOT_PATH . '/app/Models/PageSection.php';
        require_once ROOT_PATH . '/app/Models/Category.php';
        require_once ROOT_PATH . '/app/Models/Product.php';
        require_once ROOT_PATH . '/app/Models/GalleryItem.php';
        require_once ROOT_PATH . '/app/Models/BlogPost.php';

        $data['currentView'] = $viewName;
        $GLOBALS['currentView'] = $viewName;

        // Değişkenleri görünümün erişebileceği şekilde aç
        extract($data);

        $viewFile = VIEWS_PATH . '/pages/' . $viewName . '.php';

        if (!file_exists($viewFile)) {
            die("Görünüm dosyası bulunamadı: " . htmlspecialchars($viewName));
        }

        // Çıktı tamponunu başlat
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Eğer layout istenmiyorsa direkt çıktıyı bas
        if ($layout === 'none') {
            echo $content;
            return;
        }

        // Layout dosyalarını dahil et
        require VIEWS_PATH . '/layouts/header.php';
        echo $content;
        require VIEWS_PATH . '/layouts/footer.php';
    }

    /**
     * Yönetim Paneli (xpanel) Şablonu Render Eder
     */
    public static function renderPanel(string $viewName, array $data = [], bool $withLayout = true): void
    {
        // Yönetim panelinde tarayıcı önbelleklemesini tamamen engelle (Veriler anında yansısın)
        if (!headers_sent()) {
            header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
            header('Pragma: no-cache');
            header('Expires: 0');
        }

        extract($data);

        $viewFile = VIEWS_PATH . '/xpanel/' . $viewName . '.php';

        if (!file_exists($viewFile)) {
            die("Yönetim paneli görünümü bulunamadı: " . htmlspecialchars($viewName));
        }

        if (!$withLayout) {
            require $viewFile;
            return;
        }

        ob_start();
        require $viewFile;
        $panelContent = ob_get_clean();

        require VIEWS_PATH . '/xpanel/layout.php';
    }
}
