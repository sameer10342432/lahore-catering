<?php
/**
 * Generic Page View
 * Preserves 100% Dutch content for all pages
 */

$metaTitle = ($seo['meta_title'] ?? '') ?: ($page['title'] . ' | Lahore Catering Friesland');
$metaDesc = ($seo['meta_description'] ?? '') ?: ($page['excerpt'] ?: substr(strip_tags($page['content']), 0, 160));
$canonicalUrl = ($seo['canonical_url'] ?? '') ?: (CANONICAL_DOMAIN . '/' . ($page['slug'] === 'home' ? '' : $page['slug'] . '/'));
$ogImage = $page['featured_image'] ?: (SITE_URL . '/uploads/2026/10/Pakistani-Feast-with-Frisian-Charm.png');

require_once VIEW_PATH . '/components/header.php';
?>

<div style="background:var(--bg-alt); padding:3rem 0; border-bottom:1px solid var(--border-light);">
    <div class="container container-narrow">
        <?php
        $crumbs = [
            ['title' => 'Home', 'url' => '/'],
            ['title' => $page['title']]
        ];
        require VIEW_PATH . '/components/breadcrumbs.php';
        ?>
        <h1 style="font-size:clamp(2.2rem, 4vw, 3.2rem); margin-bottom:0.75rem; letter-spacing:-0.02em;">
            <?= e($page['title']) ?>
        </h1>
        <?php if (!empty($page['excerpt'])): ?>
        <p style="font-size:1.15rem; color:var(--text-light); line-height:1.6; margin-bottom:0;">
            <?= e($page['excerpt']) ?>
        </p>
        <?php endif; ?>
    </div>
</div>

<div class="section" style="padding-top:3rem;">
    <div class="container container-narrow">
        <?php if (!empty($page['featured_image'])): ?>
        <div style="margin-bottom:2.5rem; border-radius:12px; overflow:hidden; box-shadow:var(--shadow-md);">
            <img src="<?= e($page['featured_image']) ?>" alt="<?= e($page['featured_image_alt'] ?: $page['title']) ?>" style="width:100%; max-height:460px; object-fit:cover;">
        </div>
        <?php endif; ?>

        <div class="article-content" style="font-size:1.1rem; line-height:1.8; color:#292524;">
            <style>
                .article-content p { font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.5rem; color: #374151; }
                .article-content h2 { font-size: 1.85rem; margin-top: 2.5rem; margin-bottom: 1rem; color: #1c1917; }
                .article-content h3 { font-size: 1.45rem; margin-top: 2rem; margin-bottom: 0.75rem; color: #1c1917; }
                .article-content ul, .article-content ol { margin-bottom: 1.5rem; padding-left: 2rem; color: #374151; }
                .article-content li { margin-bottom: 0.5rem; }
                .article-content img { border-radius: 8px; margin: 1.5rem auto; box-shadow: 0 4px 12px rgba(0,0,0,0.06); max-width: 100%; height: auto; }
                .article-content a { color: #d97706; text-decoration: underline; font-weight: 600; }
                .article-content a:hover { color: #b45309; }
            </style>
            <?= $page['content'] ?>
        </div>

        <!-- Embedded Reservation Form for Catering / Buffet Pages -->
        <?php if (preg_match('/catering|buffet|eten|feest|reserver/i', $page['slug'])): ?>
        <div style="margin-top:4rem;">
            <?php require VIEW_PATH . '/components/contact-form.php'; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once VIEW_PATH . '/components/footer.php'; ?>
