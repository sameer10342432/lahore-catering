<?php
/**
 * Lahore Catering - Main Application Front Controller
 * Custom PHP 8.x Architecture (No WordPress Dependency)
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/models/Database.php';
require_once __DIR__ . '/models/Redirect.php';
require_once __DIR__ . '/models/Menu.php';
require_once __DIR__ . '/models/Seo.php';

// Parse Request URI
$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = '/' . trim($requestUri, '/');
if ($path !== '/') {
    $path .= '/';
}

// 1. Check Permanent 301 Redirects Table
$redirect = Redirect::match($requestUri);
if ($redirect) {
    header("Location: {$redirect['target_url']}", true, (int)$redirect['status_code']);
    exit;
}

// 2. Route Static / Direct Endpoints
if ($path === '/') {
    require_once CONTROLLER_PATH . '/HomeController.php';
    (new HomeController())->index();
    exit;
}

if ($path === '/blog/') {
    require_once CONTROLLER_PATH . '/BlogController.php';
    (new BlogController())->index();
    exit;
}

if ($path === '/contact/') {
    require VIEW_PATH . '/pages/contact.php';
    exit;
}

// 3. Category & Tag Routes
if (preg_match('#^/category/([^/]+)/?$#', $path, $matches)) {
    require_once CONTROLLER_PATH . '/BlogController.php';
    (new BlogController())->category($matches[1]);
    exit;
}

if (preg_match('#^/tag/([^/]+)/?$#', $path, $matches)) {
    require_once CONTROLLER_PATH . '/BlogController.php';
    (new BlogController())->tag($matches[1]);
    exit;
}

// 4. Check Direct Page or Post Slugs
$slug = trim($requestUri, '/');

// Check Pages first
require_once MODEL_PATH . '/Page.php';
$page = Page::findBySlug($slug);
if ($page) {
    require_once CONTROLLER_PATH . '/PageController.php';
    (new PageController())->show($slug);
    exit;
}

// Check Blog Posts
require_once MODEL_PATH . '/Post.php';
$post = Post::findBySlug($slug);
if ($post) {
    require_once CONTROLLER_PATH . '/BlogController.php';
    (new BlogController())->single($slug);
    exit;
}

// Check Food items if slug begins with food/
if (str_starts_with($slug, 'food/')) {
    $foodSlug = substr($slug, 5);
    require_once MODEL_PATH . '/FoodItem.php';
    $food = FoodItem::findBySlug($foodSlug);
    if ($food) {
        $metaTitle = $food['name'] . ' | Lahore Catering Friesland';
        $metaDesc = $food['description'];
        $canonicalUrl = CANONICAL_DOMAIN . '/food/' . $food['slug'] . '/';
        $ogImage = $food['image_url'] ?: (SITE_URL . '/uploads/2026/10/Pakistani-Feast-with-Frisian-Charm.png');

        require_once VIEW_PATH . '/components/header.php';
        ?>
        <div style="background:var(--bg-alt); padding:3rem 0; border-bottom:1px solid var(--border-light);">
            <div class="container container-narrow">
                <?php
                $crumbs = [
                    ['title' => 'Home', 'url' => '/'],
                    ['title' => 'Menu', 'url' => '/#buffetten'],
                    ['title' => $food['name']]
                ];
                require VIEW_PATH . '/components/breadcrumbs.php';
                ?>
                <span class="section-badge"><?= e($food['category']) ?></span>
                <h1 style="font-size:clamp(2.2rem, 4vw, 3rem); margin:0.5rem 0;"><?= e($food['name']) ?></h1>
                <p style="font-size:1.1rem; color:var(--text-muted);"><?= e($food['description']) ?></p>
            </div>
        </div>
        <section class="section">
            <div class="container container-narrow">
                <?php if ($food['image_url']): ?>
                <div style="border-radius:12px; overflow:hidden; box-shadow:var(--shadow-md); margin-bottom:2rem;">
                    <img src="<?= e($food['image_url']) ?>" alt="<?= e($food['image_alt'] ?: $food['name']) ?>" style="width:100%; max-height:460px; object-fit:cover;">
                </div>
                <?php endif; ?>
                <div style="background:#ffffff; border-radius:12px; padding:2rem; border:1px solid var(--border-light); text-align:center;">
                    <h3 style="font-size:1.4rem; margin-bottom:0.75rem;">Zin in <?= e($food['name']) ?>?</h3>
                    <p style="color:var(--text-muted); margin-bottom:1.5rem;">Bestel direct online voor bezorging of afhalen, of neem contact op voor cateringmogelijkheden.</p>
                    <div style="display:flex; justify-content:center; gap:1rem; flex-wrap:wrap;">
                        <a href="<?= e(ORDER_SIDES_URL) ?>" target="_blank" rel="noopener" class="btn btn-primary">Direct Bestellen</a>
                        <a href="/#reserveren" class="btn btn-outline">Catering Aanvragen</a>
                    </div>
                </div>
            </div>
        </section>
        <?php
        require_once VIEW_PATH . '/components/footer.php';
        exit;
    }
}

// 5. 404 Fallback
http_response_code(404);
require VIEW_PATH . '/pages/404.php';
