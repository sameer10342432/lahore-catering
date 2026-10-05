<?php
/**
 * Blog Listing View
 */

$metaTitle = 'Blog & Artikelen | Lahore Catering Friesland';
$metaDesc = 'Ontdek inspirerende artikelen, authentieke recepten en handige catering tips voor feesten en evenementen in Friesland.';
$canonicalUrl = CANONICAL_DOMAIN . '/blog/';

require_once VIEW_PATH . '/components/header.php';
?>

<div style="background:var(--bg-alt); padding:3rem 0; border-bottom:1px solid var(--border-light);">
    <div class="container">
        <?php
        $crumbs = [
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'Blog']
        ];
        require VIEW_PATH . '/components/breadcrumbs.php';
        ?>
        <h1 style="font-size:clamp(2rem, 3.5vw, 2.75rem); margin-bottom:0.5rem;">
            <?= !empty($category) ? 'Categorie: ' . e($category['name']) : (!empty($tag) ? 'Tag: ' . e($tag['name']) : (!empty($search) ? 'Zoekresultaten voor: "' . e($search) . '"' : 'Blog & Nieuws')) ?>
        </h1>
        <p style="font-size:1.1rem; color:var(--text-light); max-width:700px; margin-bottom:1.5rem;">
            Alles over de Pakistaanse en Indiase keuken, cateringmogelijkheden, feesten en culinaire tradities in Friesland.
        </p>

        <!-- Search Bar -->
        <form method="get" action="/blog/" style="max-width:540px; display:flex; gap:0.5rem;">
            <input type="text" name="s" value="<?= e($search ?? '') ?>" placeholder="Zoek in alle artikelen..." class="form-control" style="background:#ffffff; color:#1c1917; border-color:#d6d3d1;">
            <button type="submit" class="btn btn-primary" style="padding:0.75rem 1.5rem;">Zoeken</button>
            <?php if (!empty($search)): ?>
                <a href="/blog/" class="btn btn-outline" style="padding:0.75rem 1rem;">Wissen</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<section class="section">
    <div class="container">
        <?php if (!empty($categories)): ?>
        <!-- Category Filter Pills -->
        <div style="display:flex; flex-wrap:wrap; gap:0.5rem; margin-bottom:2.5rem; align-items:center;">
            <span style="font-size:0.9rem; font-weight:700; color:var(--text-muted); margin-right:0.5rem;">Categorieën:</span>
            <a href="/blog/" class="btn btn-sm <?= empty($category) ? 'btn-primary' : 'btn-outline' ?>" style="border-radius:9999px;">
                Alle (<?= $pagination['total'] ?? 258 ?>)
            </a>
            <?php foreach ($categories as $cat): ?>
                <?php if ($cat['post_count'] > 0): ?>
                <a href="/category/<?= e($cat['slug']) ?>/" class="btn btn-sm <?= (!empty($category) && $category['id'] == $cat['id']) ? 'btn-primary' : 'btn-outline' ?>" style="border-radius:9999px;">
                    <?= e($cat['name']) ?> (<?= $cat['post_count'] ?>)
                </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($pagination['items'])): ?>
            <div class="blog-grid">
                <?php foreach ($pagination['items'] as $post): ?>
                    <?php require VIEW_PATH . '/components/blog-card.php'; ?>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php
            $page = $pagination['page'];
            $totalPages = $pagination['total_pages'];
            $baseUrl = !empty($category) ? '/category/' . $category['slug'] . '/' : (!empty($tag) ? '/tag/' . $tag['slug'] . '/' : '/blog/');
            require VIEW_PATH . '/components/pagination.php';
            ?>

        <?php else: ?>
            <div style="text-align:center; padding:4rem 1rem; background:#ffffff; border-radius:12px; border:1px solid var(--border-light);">
                <h3 style="font-size:1.5rem; margin-bottom:0.75rem;">Geen artikelen gevonden</h3>
                <p style="color:var(--text-muted);">Er zijn geen artikelen gevonden die overeenkomen met uw zoekopdracht.</p>
                <a href="/blog/" class="btn btn-primary" style="margin-top:1rem;">Bekijk alle artikelen</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once VIEW_PATH . '/components/footer.php'; ?>
