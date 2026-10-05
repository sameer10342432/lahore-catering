<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../models/Category.php';

$pageTitle = 'Categorieën';
$activePage = 'categories';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $error = 'Sessie ongeldig.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $desc = trim($_POST['description'] ?? '');

        if (empty($name)) {
            $error = 'Categorienaam is verplicht.';
        } else {
            if (empty($slug)) {
                $slug = strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '-', $name));
            }
            Category::create($name, $slug, $desc);
            $message = 'Categorie succesvol aangemaakt!';
        }
    }
}

if (isset($_GET['delete'])) {
    Category::delete((int)$_GET['delete']);
    header('Location: /admin/categories.php?msg=deleted');
    exit;
}

if (isset($_GET['msg']) && $_GET['msg'] === 'deleted') {
    $message = 'Categorie verwijderd.';
}

$categories = Category::all();
$csrfToken = generate_csrf_token();
require_once __DIR__ . '/includes/header.php';
?>

<div style="display:grid; grid-template-columns: 1fr 2fr; gap:2rem;">
    <!-- Add Category Form -->
    <div>
        <div class="admin-panel">
            <div class="panel-header">
                <h3 style="font-size:1.1rem; font-weight:700;">Nieuwe Categorie</h3>
            </div>
            <div class="panel-body">
                <?php if ($message): ?>
                    <div style="background:#dcfce7; color:#15803d; padding:0.65rem 0.85rem; border-radius:6px; font-size:0.9rem; margin-bottom:1rem;"><?= e($message) ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div style="background:#fee2e2; color:#b91c1c; padding:0.65rem 0.85rem; border-radius:6px; font-size:0.9rem; margin-bottom:1rem;"><?= e($error) ?></div>
                <?php endif; ?>

                <form method="post" action="">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

                    <div>
                        <label class="form-label" for="name">Naam *</label>
                        <input type="text" class="form-input" id="name" name="name" required placeholder="bijv. Recepten">
                    </div>

                    <div>
                        <label class="form-label" for="slug">Slug (URL)</label>
                        <input type="text" class="form-input" id="slug" name="slug" placeholder="bijv. recepten">
                    </div>

                    <div>
                        <label class="form-label" for="description">Beschrijving</label>
                        <textarea class="form-input" id="description" name="description" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn-adm btn-adm-primary" style="width:100%; justify-content:center;">
                        Categorie Opslaan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Categories List -->
    <div>
        <div class="admin-panel">
            <div class="panel-header">
                <h3 style="font-size:1.1rem; font-weight:700;">Bestaande Categorieën (<?= count($categories) ?>)</h3>
            </div>
            <div class="panel-body" style="padding:0;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Naam</th>
                            <th>Slug</th>
                            <th>Aantal Artikelen</th>
                            <th style="text-align:right;">Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $c): ?>
                        <tr>
                            <td><strong><?= e($c['name']) ?></strong></td>
                            <td style="font-family:monospace; font-size:0.85rem; color:#64748b;">/category/<?= e($c['slug']) ?>/</td>
                            <td><span class="badge badge-warning"><?= (int)$c['post_count'] ?></span></td>
                            <td style="text-align:right;">
                                <a href="/category/<?= e($c['slug']) ?>/" target="_blank" class="btn-adm btn-adm-secondary btn-sm">Bekijk</a>
                                <a href="/admin/categories.php?delete=<?= $c['id'] ?>" onclick="return confirmDelete()" class="btn-adm btn-adm-danger btn-sm">Verwijder</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
