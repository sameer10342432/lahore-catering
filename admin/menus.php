<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../models/Menu.php';

$pageTitle = 'Navigatiemenu Beheer';
$activePage = 'menus';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $menuId = (int)$_POST['menu_id'];
    $title = trim($_POST['title']);
    $url = trim($_POST['url']);
    $target = $_POST['target'] ?? '_self';
    $order = (int)$_POST['order_index'];

    if (!empty($title) && !empty($url)) {
        Menu::addItem($menuId, $title, $url, $target, $order);
        $message = 'Menu-item toegevoegd!';
    }
}

if (isset($_GET['delete'])) {
    Menu::deleteItem((int)$_GET['delete']);
    header('Location: /admin/menus.php?msg=deleted');
    exit;
}

if (isset($_GET['msg']) && $_GET['msg'] === 'deleted') {
    $message = 'Menu-item verwijderd.';
}

$primaryItems = Menu::getByLocation('primary');
$footerItems = Menu::getByLocation('footer');

require_once __DIR__ . '/includes/header.php';
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
    <h2 style="font-size:1.5rem; font-weight:700;">Navigatiemenu's</h2>
</div>

<?php if ($message): ?>
    <div style="background:#dcfce7; color:#15803d; padding:0.75rem 1rem; border-radius:6px; margin-bottom:1.5rem; font-weight:600;"><?= e($message) ?></div>
<?php endif; ?>

<!-- Primary Menu -->
<div class="admin-panel" style="margin-bottom:2rem;">
    <div class="panel-header">
        <h3 style="font-size:1.15rem; font-weight:700;">Hoofdmenu (Header)</h3>
    </div>
    <div class="panel-body" style="padding:0;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:60px;">Volgorde</th>
                    <th>Link Tekst</th>
                    <th>URL</th>
                    <th>Target</th>
                    <th style="text-align:right;">Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($primaryItems as $item): ?>
                <tr>
                    <td><?= $item['order_index'] ?></td>
                    <td><strong><?= e($item['title']) ?></strong></td>
                    <td style="font-family:monospace; font-size:0.85rem; color:#64748b;"><?= e($item['url']) ?></td>
                    <td><?= e($item['target']) ?></td>
                    <td style="text-align:right;">
                        <a href="/admin/menus.php?delete=<?= $item['id'] ?>" onclick="return confirmDelete()" class="btn-adm btn-adm-danger btn-sm">Verwijder</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Item to Menu Form -->
<div class="admin-panel">
    <div class="panel-header">
        <h3 style="font-size:1.15rem; font-weight:700;">Nieuw Menu-item Toevoegen</h3>
    </div>
    <div class="panel-body">
        <form method="post" action="" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)) 100px; gap:1rem; align-items:flex-end;">
            <input type="hidden" name="add_item" value="1">

            <div>
                <label class="form-label">Menu</label>
                <select name="menu_id" class="form-input" style="margin-bottom:0;">
                    <option value="1">Hoofdmenu (Header)</option>
                    <option value="2">Footermenu (Footer)</option>
                </select>
            </div>

            <div>
                <label class="form-label">Link Tekst *</label>
                <input type="text" name="title" required placeholder="bijv. Evenementen" class="form-input" style="margin-bottom:0;">
            </div>

            <div>
                <label class="form-label">URL *</label>
                <input type="text" name="url" required placeholder="/evenementen/" class="form-input" style="margin-bottom:0;">
            </div>

            <div>
                <label class="form-label">Volgorde</label>
                <input type="number" name="order_index" value="10" class="form-input" style="margin-bottom:0;">
            </div>

            <button type="submit" class="btn-adm btn-adm-primary" style="height:42px;">
                Toevoegen
            </button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
