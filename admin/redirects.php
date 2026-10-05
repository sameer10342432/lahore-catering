<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../models/Redirect.php';

$pageTitle = '301 Redirects Beheer';
$activePage = 'redirects';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $error = 'Sessie ongeldig.';
    } else {
        $source = trim($_POST['source_url'] ?? '');
        $target = trim($_POST['target_url'] ?? '');

        if (empty($source) || empty($target)) {
            $error = 'Bron en doel URL zijn verplicht.';
        } else {
            if (!str_starts_with($source, '/')) $source = '/' . $source;
            Redirect::create($source, $target);
            $message = 'Redirect succesvol aangemaakt!';
        }
    }
}

if (isset($_GET['delete'])) {
    Redirect::delete((int)$_GET['delete']);
    header('Location: /admin/redirects.php?msg=deleted');
    exit;
}

if (isset($_GET['msg']) && $_GET['msg'] === 'deleted') {
    $message = 'Redirect verwijderd.';
}

$redirects = Redirect::all();
$csrfToken = generate_csrf_token();
require_once __DIR__ . '/includes/header.php';
?>

<div style="display:grid; grid-template-columns: 1fr 2fr; gap:2rem;">
    <!-- Add Redirect -->
    <div>
        <div class="admin-panel">
            <div class="panel-header">
                <h3 style="font-size:1.1rem; font-weight:700;">Nieuwe 301 Redirect</h3>
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
                        <label class="form-label" for="source_url">Oude URL (Bron) *</label>
                        <input type="text" class="form-input" id="source_url" name="source_url" required placeholder="/oude-pagina/" style="font-family:monospace;">
                    </div>

                    <div>
                        <label class="form-label" for="target_url">Nieuwe URL (Doel) *</label>
                        <input type="text" class="form-input" id="target_url" name="target_url" required placeholder="/nieuwe-pagina/" style="font-family:monospace;">
                    </div>

                    <button type="submit" class="btn-adm btn-adm-primary" style="width:100%; justify-content:center;">
                        301 Redirect Aanmaken
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Redirects Table -->
    <div>
        <div class="admin-panel">
            <div class="panel-header">
                <h3 style="font-size:1.1rem; font-weight:700;">Actieve Redirects (<?= count($redirects) ?>)</h3>
            </div>
            <div class="panel-body" style="padding:0;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Oude URL (Bron)</th>
                            <th>Nieuwe URL (Doel)</th>
                            <th>Hits</th>
                            <th style="text-align:right;">Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($redirects as $r): ?>
                        <tr>
                            <td style="font-family:monospace; font-size:0.85rem; color:#dc2626;"><?= e($r['source_url']) ?></td>
                            <td style="font-family:monospace; font-size:0.85rem; color:#16a34a;">&rarr; <?= e($r['target_url']) ?></td>
                            <td><span class="badge badge-warning"><?= (int)$r['hits'] ?></span></td>
                            <td style="text-align:right;">
                                <a href="/admin/redirects.php?delete=<?= $r['id'] ?>" onclick="return confirmDelete()" class="btn-adm btn-adm-danger btn-sm">Verwijder</a>
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
