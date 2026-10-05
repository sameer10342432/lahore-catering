<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../models/Page.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Media.php';
require_once __DIR__ . '/../models/FormSubmission.php';

$pageTitle = 'Dashboard';
$activePage = 'dashboard';

$totalPages = Page::count();
$totalPosts = Post::count();
$totalMedia = Media::count();
$recentSubmissions = FormSubmission::all(5);
$recentPosts = Post::paginate(1, 5)['items'];

require_once __DIR__ . '/includes/header.php';
?>

<!-- Stat Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <span class="label">Totaal Pagina's</span>
        <div class="value"><?= $totalPages ?></div>
        <a href="/admin/pages.php" style="font-size:0.85rem; color:var(--admin-primary); margin-top:0.5rem; display:inline-block;">Beheer pagina's &rarr;</a>
    </div>

    <div class="stat-card">
        <span class="label">Blog Artikelen</span>
        <div class="value"><?= $totalPosts ?></div>
        <a href="/admin/posts.php" style="font-size:0.85rem; color:var(--admin-primary); margin-top:0.5rem; display:inline-block;">Beheer blogs &rarr;</a>
    </div>

    <div class="stat-card">
        <span class="label">Media & Afbeeldingen</span>
        <div class="value"><?= $totalMedia ?></div>
        <a href="/admin/media.php" style="font-size:0.85rem; color:var(--admin-primary); margin-top:0.5rem; display:inline-block;">Bekijk bibliotheek &rarr;</a>
    </div>

    <div class="stat-card">
        <span class="label">Nieuwe Aanvragen</span>
        <div class="value"><?= $newSubmissionsCount ?></div>
        <a href="/admin/submissions.php" style="font-size:0.85rem; color:var(--admin-primary); margin-top:0.5rem; display:inline-block;">Bekijk aanvragen &rarr;</a>
    </div>
</div>

<!-- Quick Actions -->
<div style="display:flex; gap:1rem; margin-bottom:2rem; flex-wrap:wrap;">
    <a href="/admin/post-edit.php" class="btn-adm btn-adm-primary">+ Nieuw Blogartikel Schrijven</a>
    <a href="/admin/page-edit.php" class="btn-adm btn-adm-secondary">+ Nieuwe Pagina Toevoegen</a>
    <a href="/admin/media.php" class="btn-adm btn-adm-secondary">Afbeelding Uploaden</a>
    <a href="/admin/settings.php" class="btn-adm btn-adm-secondary">Website Gegevens Wijzigen</a>
</div>

<!-- Recent Submissions -->
<div class="admin-panel">
    <div class="panel-header">
        <h3 style="font-size:1.15rem; font-weight:700;">Recente Aanvragen & Berichten</h3>
        <a href="/admin/submissions.php" class="btn-adm btn-adm-secondary btn-sm">Bekijk Alle</a>
    </div>
    <div class="panel-body" style="padding:0;">
        <?php if (!empty($recentSubmissions)): ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Datum</th>
                    <th>Type</th>
                    <th>Naam</th>
                    <th>E-mail</th>
                    <th>Status</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentSubmissions as $sub):
                    $data = json_decode($sub['data_json'], true) ?? [];
                ?>
                <tr>
                    <td><?= date('d-m-Y H:i', strtotime($sub['created_at'])) ?></td>
                    <td><span class="badge badge-warning"><?= e($sub['form_type']) ?></span></td>
                    <td><strong><?= e($data['name'] ?? '-') ?></strong></td>
                    <td><?= e($data['email'] ?? '-') ?></td>
                    <td>
                        <span class="badge <?= $sub['status'] === 'new' ? 'badge-danger' : 'badge-success' ?>">
                            <?= e($sub['status']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="/admin/submissions.php?id=<?= $sub['id'] ?>" class="btn-adm btn-adm-secondary btn-sm">Inzien</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div style="padding:2rem; text-align:center; color:var(--admin-muted);">
            Nog geen recente formulieraanvragen binnengekomen. Testaanvragen via het formulier verschijnen hier direct!
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Recent Blog Posts -->
<div class="admin-panel">
    <div class="panel-header">
        <h3 style="font-size:1.15rem; font-weight:700;">Recent Gepubliceerde Artikelen</h3>
        <a href="/admin/posts.php" class="btn-adm btn-adm-secondary btn-sm">Alle Artikelen (<?= $totalPosts ?>)</a>
    </div>
    <div class="panel-body" style="padding:0;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Titel</th>
                    <th>Slug / URL</th>
                    <th>Datum</th>
                    <th>Status</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentPosts as $p): ?>
                <tr>
                    <td><strong><?= e($p['title']) ?></strong></td>
                    <td style="font-family:monospace; font-size:0.85rem; color:#64748b;">/<?= e($p['slug']) ?>/</td>
                    <td><?= date('d-m-Y', strtotime($p['created_at'])) ?></td>
                    <td><span class="badge badge-success"><?= e($p['status']) ?></span></td>
                    <td>
                        <a href="/admin/post-edit.php?id=<?= $p['id'] ?>" class="btn-adm btn-adm-secondary btn-sm">Bewerken</a>
                        <a href="/<?= e($p['slug']) ?>/" target="_blank" class="btn-adm btn-adm-secondary btn-sm">Bekijk</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
