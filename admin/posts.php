<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Category.php';

$pageTitle = 'Blog Artikelen';
$activePage = 'posts';

// Handle deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    Post::delete($id);
    header('Location: /admin/posts.php?msg=deleted');
    exit;
}

$page = max(1, (int)($_GET['p'] ?? 1));
$search = trim($_GET['s'] ?? '');
$catId = !empty($_GET['cat']) ? (int)$_GET['cat'] : null;

$pagination = Post::paginate($page, 20, $catId, null, $search ?: null);
$categories = Category::all();

require_once __DIR__ . '/includes/header.php';
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
    <div>
        <h2 style="font-size:1.5rem; font-weight:700;">Blog Artikelen (<?= $pagination['total'] ?>)</h2>
        <span style="font-size:0.85rem; color:var(--admin-muted);">Pagina <?= $page ?> van <?= $pagination['total_pages'] ?></span>
    </div>
    <a href="/admin/post-edit.php" class="btn-adm btn-adm-primary">+ Nieuw Artikel Schrijven</a>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
    <div style="background:#dcfce7; color:#15803d; padding:0.75rem 1rem; border-radius:6px; margin-bottom:1.5rem;">
        Artikel succesvol verwijderd.
    </div>
<?php endif; ?>

<!-- Search & Filter Bar -->
<div style="background:#ffffff; padding:1.25rem; border-radius:8px; border:1px solid var(--admin-border); margin-bottom:1.5rem; display:flex; gap:1rem; align-items:center; flex-wrap:wrap;">
    <form method="get" action="/admin/posts.php" style="display:flex; gap:0.75rem; flex-grow:1; flex-wrap:wrap;">
        <input type="text" name="s" value="<?= e($search) ?>" placeholder="Zoek op titel of tekst..." class="form-input" style="margin-bottom:0; max-width:320px;">
        
        <select name="cat" class="form-input" style="margin-bottom:0; max-width:200px;">
            <option value="">Alle Categorieën</option>
            <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $catId == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="btn-adm btn-adm-secondary">Filteren</button>
        <?php if (!empty($search) || !empty($catId)): ?>
            <a href="/admin/posts.php" class="btn-adm btn-adm-secondary">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="admin-panel">
    <div class="panel-body" style="padding:0;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:50px;">ID</th>
                    <th>Titel</th>
                    <th>Categorie</th>
                    <th>Auteur</th>
                    <th>Datum</th>
                    <th>Status</th>
                    <th style="text-align:right;">Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pagination['items'] as $post): ?>
                <tr>
                    <td><?= $post['id'] ?></td>
                    <td>
                        <strong><a href="/admin/post-edit.php?id=<?= $post['id'] ?>" style="color:var(--admin-text); text-decoration:none;"><?= e($post['title']) ?></a></strong>
                        <div style="font-family:monospace; font-size:0.8rem; color:#64748b; margin-top:0.2rem;">/<?= e($post['slug']) ?>/</div>
                    </td>
                    <td>
                        <?php if (!empty($post['categories'])): ?>
                            <?php foreach ($post['categories'] as $c): ?>
                                <span class="badge badge-warning"><?= e($c['name']) ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span style="color:#94a3b8;">-</span>
                        <?php endif; ?>
                    </td>
                    <td><?= e($post['author'] ?: 'Lahore Catering') ?></td>
                    <td><?= date('d-m-Y', strtotime($post['created_at'])) ?></td>
                    <td><span class="badge badge-success"><?= e($post['status']) ?></span></td>
                    <td style="text-align:right; white-space:nowrap;">
                        <a href="/admin/post-edit.php?id=<?= $post['id'] ?>" class="btn-adm btn-adm-secondary btn-sm">Bewerken</a>
                        <a href="/<?= e($post['slug']) ?>/" target="_blank" class="btn-adm btn-adm-secondary btn-sm">Bekijk</a>
                        <a href="/admin/posts.php?delete=<?= $post['id'] ?>" onclick="return confirmDelete('Weet u zeker dat u dit blogartikel wilt verwijderen?')" class="btn-adm btn-adm-danger btn-sm">Verwijder</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Admin Pagination -->
<?php if ($pagination['total_pages'] > 1): ?>
<div style="display:flex; justify-content:center; gap:0.5rem; margin-top:2rem;">
    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
        <?php if ($i == 1 || $i == $pagination['total_pages'] || abs($i - $page) <= 2): ?>
            <a href="/admin/posts.php?p=<?= $i ?><?= !empty($search) ? '&s=' . urlencode($search) : '' ?><?= !empty($catId) ? '&cat=' . $catId : '' ?>" class="btn-adm <?= $i == $page ? 'btn-adm-primary' : 'btn-adm-secondary' ?> btn-sm">
                <?= $i ?>
            </a>
        <?php elseif (abs($i - $page) == 3): ?>
            <span style="padding:0.25rem 0.5rem; color:#94a3b8;">...</span>
        <?php endif; ?>
    <?php endfor; ?>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
