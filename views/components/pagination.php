<?php
/**
 * Pagination Component
 * $page, $totalPages, $baseUrl (e.g. '/blog/')
 */
if ($totalPages > 1):
    $baseUrl = rtrim($baseUrl ?? '/blog/', '/') . '/';
?>
<nav aria-label="Paginering">
    <ul class="pagination">
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a href="<?= $baseUrl ?>?p=<?= $page - 1 ?>" aria-label="Vorige pagina">&larr;</a>
            </li>
        <?php endif; ?>

        <?php
        $start = max(1, $page - 2);
        $end = min($totalPages, $page + 2);
        if ($start > 1) {
            echo '<li class="page-item"><a href="' . $baseUrl . '?p=1">1</a></li>';
            if ($start > 2) echo '<li class="page-item"><span>...</span></li>';
        }
        for ($i = $start; $i <= $end; $i++):
        ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                <?php if ($i === $page): ?>
                    <span aria-current="page"><?= $i ?></span>
                <?php else: ?>
                    <a href="<?= $baseUrl ?>?p=<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            </li>
        <?php endfor; ?>

        <?php
        if ($end < $totalPages) {
            if ($end < $totalPages - 1) echo '<li class="page-item"><span>...</span></li>';
            echo '<li class="page-item"><a href="' . $baseUrl . '?p=' . $totalPages . '">' . $totalPages . '</a></li>';
        }
        ?>

        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a href="<?= $baseUrl ?>?p=<?= $page + 1 ?>" aria-label="Volgende pagina">&rarr;</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<?php endif; ?>
