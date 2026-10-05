<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../models/Page.php';

$pageTitle = 'Pagina Beheer';
$activePage = 'pages';

// Handle deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    Page::delete($id);
    header('Location: /admin/pages.php?msg=deleted');
    exit;
}

$pages = Page::all();

require_once __DIR__ . '/includes/header.php';
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
    <h2 style="font-size:1.5rem; font-weight:700;">Alle Pagina's (<?= count($pages) ?>)</h2>
    <a href="/admin/page-edit.php" class="btn-adm btn-adm-primary">+ Nieuwe Pagina Toevoegen</a>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
    <div style="background:#dcfce7; color:#15803d; padding:0.75rem 1rem; border-radius:6px; margin-bottom:1.5rem;">
        Pagina succesvol verwijderd.
    </div>
<?php endif; ?>

<div class="admin-panel">
    <div class="panel-body" style="padding:0;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:60px;">ID</th>
                    <th>Paginatitel</th>
                    <th>Slug / URL</th>
                    <th>Template</th>
                    <th>Status</th>
                    <th>Laatst Gewijzigd</th>
                    <th style="text-align:right;">Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><strong><?= e($p['title']) ?></strong></td>
                    <td style="font-family:monospace; font-size:0.85rem; color:#64748b;">
                        /<?= e($p['slug'] === 'home' ? '' : $p['slug'] . '/') ?>
                    </td>
                    <td><span class="badge badge-warning"><?= e($p['template']) ?></span></td>
                    <td><span class="badge badge-success"><?= e($p['status']) ?></span></td>
                    <td><?= date('d-m-Y H:i', strtotime($p['updated_at'])) ?></td>
                    <td style="text-align:right;">
                        <a href="/admin/page-edit.php?id=<?= $p['id'] ?>" class="btn-adm btn-adm-secondary btn-sm">Bewerken</a>
                        <a href="/<?= e($p['slug'] === 'home' ? '' : $p['slug'] . '/') ?>" target="_blank" class="btn-adm btn-adm-secondary btn-sm">Bekijk</a>
                        <?php if ($p['slug'] !== 'home'): ?>
                        <a href="/admin/pages.php?delete=<?= $p['id'] ?>" onclick="return confirmDelete('Weet u zeker dat u deze pagina wilt verwijderen?')" class="btn-adm btn-adm-danger btn-sm">Verwijder</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
