<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../models/Media.php';

$pageTitle = 'Media Bibliotheek';
$activePage = 'media';

$message = '';
$error = '';

// Handle Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['media_file'])) {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $error = 'Sessiebeveiliging ongeldig.';
    } else {
        $file = $_FILES['media_file'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $error = 'Fout bij uploaden (code: ' . $file['error'] . ')';
        } else {
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'application/pdf'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mime, $allowedMimes)) {
                $error = 'Niet-toegestaan bestandstype. Alleen JPG, PNG, WEBP, GIF, SVG en PDF zijn toegestaan.';
            } else {
                $year = date('Y');
                $month = date('m');
                $targetDir = UPLOAD_PATH . "/$year/$month";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $filename = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', basename($file['name']));
                $targetPath = "$targetDir/$filename";

                // Ensure unique filename
                $counter = 1;
                $pathInfo = pathinfo($filename);
                while (file_exists($targetPath)) {
                    $filename = $pathInfo['filename'] . "_$counter." . $pathInfo['extension'];
                    $targetPath = "$targetDir/$filename";
                    $counter++;
                }

                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    $relPath = "uploads/$year/$month/$filename";
                    $url = "/$relPath";
                    $size = filesize($targetPath);
                    $dims = @getimagesize($targetPath);
                    $width = $dims ? $dims[0] : 0;
                    $height = $dims ? $dims[1] : 0;

                    Media::create([
                        'filename' => $filename,
                        'filepath' => $relPath,
                        'url' => $url,
                        'alt_text' => trim($_POST['alt_text'] ?? $pathInfo['filename']),
                        'mime_type' => $mime,
                        'file_size' => $size,
                        'width' => $width,
                        'height' => $height
                    ]);

                    $message = "Afbeelding $filename succesvol geüpload!";
                } else {
                    $error = 'Kon bestand niet verplaatsen naar uploads directory.';
                }
            }
        }
    }
}

// Handle ALT text update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_alt'])) {
    $mediaId = (int)$_POST['media_id'];
    $newAlt = trim($_POST['alt_text'] ?? '');
    Media::updateAlt($mediaId, $newAlt);
    $message = 'ALT-tekst bijgewerkt.';
}

// Handle deletion
if (isset($_GET['delete'])) {
    Media::delete((int)$_GET['delete']);
    header('Location: /admin/media.php?msg=deleted');
    exit;
}

if (isset($_GET['msg']) && $_GET['msg'] === 'deleted') {
    $message = 'Bestand verwijderd.';
}

$page = max(1, (int)($_GET['p'] ?? 1));
$perPage = 24;
$totalMedia = Media::count();
$totalPages = ceil($totalMedia / $perPage);
$items = Media::all($perPage, ($page - 1) * $perPage);

$csrfToken = generate_csrf_token();
require_once __DIR__ . '/includes/header.php';
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
    <h2 style="font-size:1.5rem; font-weight:700;">Media Bibliotheek (<?= $totalMedia ?> bestanden)</h2>
</div>

<?php if ($message): ?>
    <div style="background:#dcfce7; color:#15803d; padding:0.75rem 1rem; border-radius:6px; margin-bottom:1.5rem; font-weight:600;"><?= e($message) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div style="background:#fee2e2; color:#b91c1c; padding:0.75rem 1rem; border-radius:6px; margin-bottom:1.5rem; font-weight:600;"><?= e($error) ?></div>
<?php endif; ?>

<!-- Upload Box -->
<div class="admin-panel" style="margin-bottom:2rem;">
    <div class="panel-header">
        <h3 style="font-size:1.1rem; font-weight:700;">Nieuw Bestand Uploaden</h3>
    </div>
    <div class="panel-body">
        <form method="post" action="" enctype="multipart/form-data" style="display:flex; gap:1.5rem; align-items:flex-end; flex-wrap:wrap;">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

            <div style="flex:1; min-width:260px;">
                <label class="form-label" for="media_file">Bestand kiezen (JPG, PNG, WEBP, GIF, SVG)</label>
                <input type="file" class="form-input" id="media_file" name="media_file" required style="margin-bottom:0;">
            </div>

            <div style="flex:1; min-width:260px;">
                <label class="form-label" for="alt_text">ALT Tekst (voor SEO)</label>
                <input type="text" class="form-input" id="alt_text" name="alt_text" placeholder="Beschrijving van afbeelding" style="margin-bottom:0;">
            </div>

            <button type="submit" class="btn-adm btn-adm-primary" style="height:42px;">
                Uploaden
            </button>
        </form>
    </div>
</div>

<!-- Media Grid -->
<div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:1.25rem;">
    <?php foreach ($items as $m):
        $thumbUrl = '/' . ltrim($m['filepath'], '/');
    ?>
    <div style="background:#ffffff; border-radius:8px; border:1px solid var(--admin-border); overflow:hidden; display:flex; flex-direction:column;">
        <div style="height:140px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; overflow:hidden;">
            <?php if (str_starts_with($m['mime_type'], 'image/')): ?>
                <img src="<?= e($thumbUrl) ?>" alt="<?= e($m['alt_text']) ?>" style="width:100%; height:100%; object-fit:cover;">
            <?php else: ?>
                <span style="font-size:2.5rem;">📄</span>
            <?php endif; ?>
        </div>
        <div style="padding:0.75rem; flex-grow:1; display:flex; flex-direction:column;">
            <strong style="font-size:0.85rem; word-break:break-all; display:block; margin-bottom:0.25rem;"><?= e($m['filename']) ?></strong>
            <span style="font-size:0.75rem; color:#64748b; margin-bottom:0.5rem;"><?= $m['width'] ? "{$m['width']}x{$m['height']} px" : '' ?> <?= round($m['file_size']/1024) ?> KB</span>

            <form method="post" action="" style="margin-top:auto;">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                <input type="hidden" name="update_alt" value="1">
                <input type="hidden" name="media_id" value="<?= $m['id'] ?>">
                <input type="text" name="alt_text" value="<?= e($m['alt_text']) ?>" placeholder="ALT tekst..." style="width:100%; font-size:0.75rem; padding:0.25rem 0.4rem; border:1px solid #cbd5e1; border-radius:4px; margin-bottom:0.4rem;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <button type="submit" class="btn-adm btn-adm-secondary btn-sm" style="font-size:0.7rem; padding:0.2rem 0.5rem;">Opslaan</button>
                    <a href="/admin/media.php?delete=<?= $m['id'] ?>" onclick="return confirmDelete()" style="color:#ef4444; font-size:0.75rem; text-decoration:none;">Verwijder</a>
                </div>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
<div style="display:flex; justify-content:center; gap:0.5rem; margin-top:2rem;">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <?php if ($i == 1 || $i == $totalPages || abs($i - $page) <= 2): ?>
            <a href="/admin/media.php?p=<?= $i ?>" class="btn-adm <?= $i == $page ? 'btn-adm-primary' : 'btn-adm-secondary' ?> btn-sm">
                <?= $i ?>
            </a>
        <?php elseif (abs($i - $page) == 3): ?>
            <span style="padding:0.25rem 0.5rem; color:#94a3b8;">...</span>
        <?php endif; ?>
    <?php endfor; ?>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
