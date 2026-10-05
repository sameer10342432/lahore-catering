<?php
/**
 * Individual Blog Post View
 * Requires $post, $prevPost, $nextPost, $relatedPosts
 */

$metaTitle = ($seo['meta_title'] ?? '') ?: ($post['title'] . ' | Lahore Catering');
$metaDesc = ($seo['meta_description'] ?? '') ?: ($post['excerpt'] ?: substr(strip_tags($post['content']), 0, 160));
$canonicalUrl = ($seo['canonical_url'] ?? '') ?: (CANONICAL_DOMAIN . '/' . $post['slug'] . '/');
$ogImage = $post['featured_image'] ?: (SITE_URL . '/uploads/2026/10/Pakistani-Feast-with-Frisian-Charm.png');
$ogType = 'article';
$articleSchema = Seo::generateArticleSchema($post);

$dateFormatted = date('d F Y', strtotime($post['created_at']));
$dutchMonths = ['January' => 'januari', 'February' => 'februari', 'March' => 'maart', 'April' => 'april', 'May' => 'mei', 'June' => 'juni', 'July' => 'juli', 'August' => 'augustus', 'September' => 'september', 'October' => 'oktober', 'November' => 'november', 'December' => 'december'];
$dateFormatted = strtr($dateFormatted, $dutchMonths);

require_once VIEW_PATH . '/components/header.php';
?>

<div style="background:var(--bg-alt); padding:2.5rem 0; border-bottom:1px solid var(--border-light);">
    <div class="container container-narrow">
        <?php
        $crumbs = [
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'Blog', 'url' => '/blog/'],
            ['title' => $post['title']]
        ];
        require VIEW_PATH . '/components/breadcrumbs.php';
        ?>

        <!-- Categories -->
        <?php if (!empty($post['categories'])): ?>
        <div style="display:flex; gap:0.5rem; margin-bottom:1rem;">
            <?php foreach ($post['categories'] as $c): ?>
            <a href="/category/<?= e($c['slug']) ?>/" class="section-badge" style="margin-bottom:0; font-size:0.75rem;">
                <?= e($c['name']) ?>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <h1 style="font-size:clamp(2.2rem, 4vw, 3.2rem); line-height:1.2; margin-bottom:1rem; letter-spacing:-0.02em;">
            <?= e($post['title']) ?>
        </h1>

        <div style="display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap; font-size:0.9rem; color:var(--text-light); padding-top:0.5rem;">
            <span>📅 Gepubliceerd op <?= e($dateFormatted) ?></span>
            <span>✍️ Auteur: <?= e($post['author'] ?: 'Lahore Catering') ?></span>
            <?php if (!empty($post['views'])): ?>
            <span>👁️ <?= (int)$post['views'] ?> weergaven</span>
            <?php endif; ?>
        </div>
    </div>
</div>

<article class="section" style="padding-top:2.5rem;">
    <div class="container container-narrow">
        <!-- Featured Image -->
        <?php if (!empty($post['featured_image'])): ?>
        <figure style="margin-bottom:2.5rem; border-radius:12px; overflow:hidden; box-shadow:var(--shadow-md);">
            <img src="<?= e($post['featured_image']) ?>" alt="<?= e($post['featured_image_alt'] ?: $post['title']) ?>" style="width:100%; max-height:520px; object-fit:cover;">
            <?php if (!empty($post['featured_image_caption'])): ?>
            <figcaption style="padding:0.75rem 1rem; background:#f4efe6; font-size:0.85rem; color:#78716c; text-align:center;">
                <?= e($post['featured_image_caption']) ?>
            </figcaption>
            <?php endif; ?>
        </figure>
        <?php endif; ?>

        <!-- Post Content (100% Preserved Authentic Dutch Text) -->
        <div class="article-content" style="font-size:1.1rem; line-height:1.8; color:#292524;">
            <style>
                .article-content p { font-size: 1.1rem; line-height: 1.85; margin-bottom: 1.5rem; color: #374151; }
                .article-content h2 { font-size: 1.85rem; margin-top: 2.5rem; margin-bottom: 1rem; color: #1c1917; }
                .article-content h3 { font-size: 1.45rem; margin-top: 2rem; margin-bottom: 0.75rem; color: #1c1917; }
                .article-content ul, .article-content ol { margin-bottom: 1.5rem; padding-left: 2rem; color: #374151; }
                .article-content li { margin-bottom: 0.5rem; }
                .article-content img { border-radius: 8px; margin: 1.5rem auto; box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
                .article-content a { color: #d97706; text-decoration: underline; }
                .article-content a:hover { color: #b45309; }
                .article-content blockquote { border-left: 4px solid #d97706; padding: 1rem 1.5rem; margin: 1.5rem 0; background: #faf8f5; border-radius: 0 8px 8px 0; font-style: italic; }
            </style>
            <?= $post['content'] ?>
        </div>

        <!-- Tags -->
        <?php if (!empty($post['tags'])): ?>
        <div style="margin-top:3rem; padding-top:1.5rem; border-top:1px solid var(--border-light); display:flex; flex-wrap:wrap; gap:0.5rem; align-items:center;">
            <span style="font-weight:700; font-size:0.9rem; color:var(--text-muted); margin-right:0.5rem;">Tags:</span>
            <?php foreach ($post['tags'] as $t): ?>
            <a href="/tag/<?= e($t['slug']) ?>/" class="btn btn-sm btn-outline" style="border-radius:9999px; font-size:0.8rem;">
                #<?= e($t['name']) ?>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Social Share Links -->
        <div style="margin-top:2rem; padding:1.5rem; background:#ffffff; border-radius:12px; border:1px solid var(--border-light); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
            <span style="font-weight:700; font-size:0.95rem; color:var(--text-main);">Deel dit artikel:</span>
            <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($canonicalUrl) ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline" style="background:#1877f2; color:#fff; border-color:#1877f2;">
                    Facebook
                </a>
                <a href="https://api.whatsapp.com/send?text=<?= urlencode($post['title'] . ' ' . $canonicalUrl) ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline" style="background:#25d366; color:#fff; border-color:#25d366;">
                    WhatsApp
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode($canonicalUrl) ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline" style="background:#0a66c2; color:#fff; border-color:#0a66c2;">
                    LinkedIn
                </a>
                <a href="https://twitter.com/intent/tweet?text=<?= urlencode($post['title']) ?>&url=<?= urlencode($canonicalUrl) ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline" style="background:#000; color:#fff; border-color:#000;">
                    X
                </a>
            </div>
        </div>

        <!-- Next / Previous Navigation -->
        <div style="margin-top:2.5rem; display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
            <?php if (!empty($prevPost)): ?>
            <a href="/<?= e($prevPost['slug']) ?>/" style="padding:1.25rem; background:#ffffff; border-radius:10px; border:1px solid var(--border-light); display:block; text-decoration:none;">
                <span style="font-size:0.8rem; font-weight:700; text-transform:uppercase; color:var(--primary); display:block; margin-bottom:0.25rem;">&larr; Vorig artikel</span>
                <span style="font-size:0.95rem; font-weight:600; color:var(--text-main); display:block;"><?= e($prevPost['title']) ?></span>
            </a>
            <?php else: ?>
            <div></div>
            <?php endif; ?>

            <?php if (!empty($nextPost)): ?>
            <a href="/<?= e($nextPost['slug']) ?>/" style="padding:1.25rem; background:#ffffff; border-radius:10px; border:1px solid var(--border-light); display:block; text-align:right; text-decoration:none;">
                <span style="font-size:0.8rem; font-weight:700; text-transform:uppercase; color:var(--primary); display:block; margin-bottom:0.25rem;">Volgend artikel &rarr;</span>
                <span style="font-size:0.95rem; font-weight:600; color:var(--text-main); display:block;"><?= e($nextPost['title']) ?></span>
            </a>
            <?php endif; ?>
        </div>

        <!-- In-Article Catering Call to Action -->
        <div style="margin-top:3.5rem; background:linear-gradient(135deg, #fef3c7, #fde68a); border-radius:12px; padding:2.5rem; text-align:center; border:1px solid #fcd34d;">
            <h3 style="font-size:1.6rem; color:#78350f; margin-bottom:0.75rem;">Zelf genieten van onze authentieke gerechten?</h3>
            <p style="color:#92400e; font-size:1.05rem; max-width:600px; margin:0 auto 1.5rem;">
                Of het nu gaat om een buffet voor uw feest, catering op locatie of een heerlijke maaltijd aan huis: Lahore Catering staat voor u klaar.
            </p>
            <div style="display:flex; justify-content:center; gap:1rem; flex-wrap:wrap;">
                <a href="/#reserveren" class="btn btn-primary">Vrijblijvende Offerte Aanvragen</a>
                <a href="<?= e(ORDER_SIDES_URL) ?>" target="_blank" rel="noopener" class="btn btn-secondary">Direct Online Bestellen</a>
            </div>
        </div>
    </div>
</article>

<!-- Related Posts -->
<?php if (!empty($relatedPosts)): ?>
<section class="section section-alt">
    <div class="container">
        <div class="section-header" style="margin-bottom:2.5rem;">
            <span class="section-badge">Meer Lezen</span>
            <h2 class="section-title">Gerelateerde Artikelen</h2>
        </div>
        <div class="blog-grid">
            <?php foreach ($relatedPosts as $post): ?>
                <?php require VIEW_PATH . '/components/blog-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once VIEW_PATH . '/components/footer.php'; ?>
