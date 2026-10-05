<?php
/**
 * Blog Card Component
 * Requires $post array
 */
$fImage = $post['featured_image'] ?: '/uploads/2026/10/Pakistani-Feast-with-Frisian-Charm.png';
$fAlt = $post['featured_image_alt'] ?: $post['title'];
$dateFormatted = date('d F Y', strtotime($post['created_at']));
$dutchMonths = ['January' => 'januari', 'February' => 'februari', 'March' => 'maart', 'April' => 'april', 'May' => 'mei', 'June' => 'juni', 'July' => 'juli', 'August' => 'augustus', 'September' => 'september', 'October' => 'oktober', 'November' => 'november', 'December' => 'december'];
$dateFormatted = strtr($dateFormatted, $dutchMonths);
$excerpt = $post['excerpt'] ?: substr(strip_tags($post['content']), 0, 140) . '...';
?>
<article class="blog-card">
    <div class="blog-thumb">
        <a href="/<?= e($post['slug']) ?>/">
            <img src="<?= e($fImage) ?>" alt="<?= e($fAlt) ?>" loading="lazy">
        </a>
    </div>
    <div class="blog-card-body">
        <div class="blog-meta">
            <span>📅 <?= e($dateFormatted) ?></span>
            <span>✍️ <?= e($post['author'] ?: 'Lahore Catering') ?></span>
        </div>
        <h3 class="blog-title">
            <a href="/<?= e($post['slug']) ?>/"><?= e($post['title']) ?></a>
        </h3>
        <p class="blog-excerpt"><?= e($excerpt) ?></p>
        <div class="blog-card-footer">
            <a href="/<?= e($post['slug']) ?>/" class="blog-read-more">
                Lees artikel &rarr;
            </a>
        </div>
    </div>
</article>
