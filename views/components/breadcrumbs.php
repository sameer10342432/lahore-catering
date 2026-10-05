<?php
/**
 * Breadcrumbs Component
 * $crumbs = [ ['title' => 'Home', 'url' => '/'], ['title' => 'Blog', 'url' => '/blog/'], ['title' => 'Current'] ]
 */
if (!empty($crumbs)):
?>
<nav class="breadcrumbs" aria-label="Kruimelpad">
    <?php foreach ($crumbs as $index => $crumb): ?>
        <?php if ($index > 0): ?>
            <span class="separator">/</span>
        <?php endif; ?>
        <?php if (!empty($crumb['url']) && $index < count($crumbs) - 1): ?>
            <a href="<?= e($crumb['url']) ?>"><?= e($crumb['title']) ?></a>
        <?php else: ?>
            <span class="current" aria-current="page"><?= e($crumb['title']) ?></span>
        <?php endif; ?>
    <?php endforeach; ?>
</nav>
<?php endif; ?>
