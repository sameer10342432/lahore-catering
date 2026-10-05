<?php
require_once MODEL_PATH . '/Post.php';
require_once MODEL_PATH . '/Category.php';
require_once MODEL_PATH . '/Tag.php';
require_once MODEL_PATH . '/Seo.php';

class BlogController {
    public function index(): void {
        $page = max(1, (int)($_GET['p'] ?? 1));
        $search = trim($_GET['s'] ?? '');
        $categories = Category::all();

        $pagination = Post::paginate($page, 9, null, null, $search ?: null);

        require VIEW_PATH . '/blog/index.php';
    }

    public function category(string $slug): void {
        $category = Category::findBySlug($slug);
        if (!$category) {
            http_response_code(404);
            require VIEW_PATH . '/pages/404.php';
            return;
        }

        $page = max(1, (int)($_GET['p'] ?? 1));
        $categories = Category::all();
        $pagination = Post::paginate($page, 9, (int)$category['id']);

        $metaTitle = $category['name'] . ' Artikelen | Lahore Catering Blog';
        $metaDesc = 'Bekijk alle blogartikelen in de categorie ' . $category['name'] . ' van Lahore Catering.';
        $canonicalUrl = CANONICAL_DOMAIN . '/category/' . $category['slug'] . '/';

        require VIEW_PATH . '/blog/index.php';
    }

    public function tag(string $slug): void {
        $tag = Tag::findBySlug($slug);
        if (!$tag) {
            http_response_code(404);
            require VIEW_PATH . '/pages/404.php';
            return;
        }

        $page = max(1, (int)($_GET['p'] ?? 1));
        $categories = Category::all();
        $pagination = Post::paginate($page, 9, null, (int)$tag['id']);

        $metaTitle = '#' . $tag['name'] . ' Artikelen | Lahore Catering';
        $metaDesc = 'Artikelen en tips over ' . $tag['name'] . ' bij Lahore Catering Friesland.';
        $canonicalUrl = CANONICAL_DOMAIN . '/tag/' . $tag['slug'] . '/';

        require VIEW_PATH . '/blog/index.php';
    }

    public function single(string $slug): void {
        $post = Post::findBySlug($slug);

        if (!$post) {
            // Check if it's a page instead
            require_once MODEL_PATH . '/Page.php';
            $page = Page::findBySlug($slug);
            if ($page) {
                $seo = Seo::get('page', $page['id']);
                require VIEW_PATH . '/pages/page.php';
                return;
            }

            http_response_code(404);
            require VIEW_PATH . '/pages/404.php';
            return;
        }

        $seo = Seo::get('post', $post['id']);
        $prevPost = Post::getPrevPost($post['created_at']);
        $nextPost = Post::getNextPost($post['created_at']);
        $relatedPosts = Post::getRelatedPosts($post['id'], 3);

        require VIEW_PATH . '/blog/single.php';
    }
}
