<?php
require_once MODEL_PATH . '/Page.php';
require_once MODEL_PATH . '/Seo.php';

class PageController {
    public function show(string $slug): void {
        $page = Page::findBySlug($slug);

        if (!$page) {
            http_response_code(404);
            require VIEW_PATH . '/pages/404.php';
            return;
        }

        $seo = Seo::get('page', $page['id']);
        require VIEW_PATH . '/pages/page.php';
    }
}
