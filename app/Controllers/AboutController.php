<?php
/**
 * BİKGRUP - Kurumsal / Hakkımızda Kontrolcüsü
 */

declare(strict_types=1);

require_once ROOT_PATH . '/core/View.php';
require_once ROOT_PATH . '/app/Models/PageSection.php';

class AboutController
{
    public function index(): void
    {
        $sections = PageSection::getPageSections('about');

        View::render('about', [
            'pageTitle'  => __('nav_about_sub') . ' | ' . Settings::get('site_name', 'BİKGRUP'),
            'sections'   => $sections,
            'activeNav'  => 'about'
        ]);
    }
}
