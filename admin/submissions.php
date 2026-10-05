<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../models/FormSubmission.php';

$pageTitle = 'Formulier Aanvragen';
$activePage = 'submissions';

if (isset($_GET['delete'])) {
    FormSubmission::delete((int)$_GET['delete']);
    header('Location: /admin/submissions.php?msg=deleted');
    exit;
}

if (isset($_GET['status']) && isset($_GET['id'])) {
    FormSubmission::markStatus((int)$_GET['id'], $_GET['status']);
    header('Location: /admin/submissions.php');
    exit;
}

$viewId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$viewSubmission = $viewId ? FormSubmission::findById($viewId) : null;
if ($viewSubmission && $viewSubmission['status'] === 'new') {
    FormSubmission::markStatus($viewId, 'read');
}

$submissions = FormSubmission::all(50);

require_once __DIR__ . '/includes/header.php';
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
    <h2 style="font-size:1.5rem; font-weight:700;">Ingekomen Aanvragen & Berichten (<?= count($submissions) ?>)</h2>
</div>

<?php if ($viewSubmission):
    $d = json_decode($viewSubmission['data_json'], true) ?? [];
?>
<div class="admin-panel" style="border-left:4px solid var(--admin-primary); margin-bottom:2rem;">
    <div class="panel-header">
        <h3 style="font-size:1.15rem; font-weight:700;">Details Aanvraag #<?= $viewSubmission['id'] ?> (<?= e($viewSubmission['form_type']) ?>)</h3>
        <a href="/admin/submissions.php" class="btn-adm btn-adm-secondary btn-sm">&times; Sluiten</a>
    </div>
    <div class="panel-body">
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:1.5rem; margin-bottom:1.5rem;">
            <div>
                <span class="form-label">Datum aanvraag</span>
                <strong><?= date('d-m-Y H:i:s', strtotime($viewSubmission['created_at'])) ?></strong>
            </div>
            <div>
                <span class="form-label">Naam</span>
                <strong><?= e($d['name'] ?? '-') ?></strong>
            </div>
            <div>
                <span class="form-label">E-mailadres</span>
                <strong><a href="mailto:<?= e($d['email'] ?? '') ?>"><?= e($d['email'] ?? '-') ?></a></strong>
            </div>
            <div>
                <span class="form-label">Telefoonnummer</span>
                <strong><a href="tel:<?= e($d['phone'] ?? '') ?>"><?= e($d['phone'] ?? '-') ?></a></strong>
            </div>
            <?php if (!empty($d['date'])): ?>
            <div>
                <span class="form-label">Gewenste evenementdatum</span>
                <strong style="color:var(--admin-primary);"><?= e($d['date']) ?> om <?= e($d['time'] ?? '-') ?></strong>
            </div>
            <?php endif; ?>
            <?php if (!empty($d['guests'])): ?>
            <div>
                <span class="form-label">Aantal gasten</span>
                <strong><?= (int)$d['guests'] ?> personen</strong>
            </div>
            <?php endif; ?>
            <?php if (!empty($d['buffet_type'])): ?>
            <div>
                <span class="form-label">Buffet type</span>
                <strong style="color:#b45309;"><?= e($d['buffet_type']) ?></strong>
            </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($d['notes']) || !empty($d['message'])): ?>
        <div style="margin-top:1.5rem; padding:1.25rem; background:#f8fafc; border-radius:8px; border:1px solid var(--admin-border);">
            <span class="form-label">Bericht / Opmerkingen</span>
            <p style="white-space:pre-wrap; margin-bottom:0; color:#334155; font-size:1rem;"><?= e($d['notes'] ?? $d['message']) ?></p>
        </div>
        <?php endif; ?>

        <div style="margin-top:1.5rem; display:flex; gap:1rem; justify-content:flex-end;">
            <a href="/admin/submissions.php?status=archived&id=<?= $viewSubmission['id'] ?>" class="btn-adm btn-adm-secondary">Archiveren</a>
            <a href="/admin/submissions.php?delete=<?= $viewSubmission['id'] ?>" onclick="return confirmDelete()" class="btn-adm btn-adm-danger">Verwijderen</a>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="admin-panel">
    <div class="panel-body" style="padding:0;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Datum</th>
                    <th>Type</th>
                    <th>Naam</th>
                    <th>Contact</th>
                    <th>Evenement</th>
                    <th>Status</th>
                    <th style="text-align:right;">Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($submissions)): ?>
                <tr>
                    <td colspan="7" style="text-align:center; padding:3rem; color:var(--admin-muted);">Nog geen aanvragen binnengekomen.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($submissions as $sub):
                        $d = json_decode($sub['data_json'], true) ?? [];
                    ?>
                    <tr style="<?= $sub['status'] === 'new' ? 'background:#fffbeb; font-weight:600;' : '' ?>">
                        <td><?= date('d-m-Y H:i', strtotime($sub['created_at'])) ?></td>
                        <td><span class="badge badge-warning"><?= e($sub['form_type']) ?></span></td>
                        <td><?= e($d['name'] ?? '-') ?></td>
                        <td>
                            <div><?= e($d['email'] ?? '-') ?></div>
                            <div style="font-size:0.8rem; color:#64748b;"><?= e($d['phone'] ?? '-') ?></div>
                        </td>
                        <td>
                            <?= !empty($d['date']) ? e($d['date']) . ' (' . (int)($d['guests'] ?? 0) . ' p.)' : '-' ?>
                        </td>
                        <td>
                            <span class="badge <?= $sub['status'] === 'new' ? 'badge-danger' : ($sub['status'] === 'read' ? 'badge-warning' : 'badge-success') ?>">
                                <?= e($sub['status']) ?>
                            </span>
                        </td>
                        <td style="text-align:right; white-space:nowrap;">
                            <a href="/admin/submissions.php?id=<?= $sub['id'] ?>" class="btn-adm btn-adm-primary btn-sm">Bekijken</a>
                            <a href="/admin/submissions.php?delete=<?= $sub['id'] ?>" onclick="return confirmDelete()" class="btn-adm btn-adm-danger btn-sm">Verwijder</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
