<?php
require_once __DIR__ . '/auth.php';
$activePage = $activePage ?? 'dashboard';
$newSubmissionsCount = FormSubmission::countNew();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Beheer') ?> | Lahore Catering Admin</title>
    <link rel="stylesheet" href="/admin/assets/admin.css">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png?v=2">
    <link rel="icon" type="image/png" sizes="192x192" href="/assets/images/favicon.png?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png?v=2">
    <link rel="shortcut icon" href="/favicon.ico?v=2">
</head>
<body>

<aside class="admin-sidebar">
    <div class="sidebar-header">
        <a href="/admin/index.php" style="display:block; margin-bottom:0.5rem;">
            <img src="/assets/images/logo.png" alt="Lahore Catering" style="height:42px; width:auto; max-width:180px; object-fit:contain; background:#ffffff; padding:4px 10px; border-radius:6px; box-shadow:0 2px 4px rgba(0,0,0,0.15);">
        </a>
        <span style="font-size:0.75rem; color:#94a3b8; display:block;">Beheersysteem</span>
    </div>

    <ul class="sidebar-menu">
        <li class="<?= $activePage === 'dashboard' ? 'active' : '' ?>">
            <a href="/admin/index.php">
                <i>📊</i> <span>Dashboard</span>
            </a>
        </li>
        <li class="<?= $activePage === 'pages' ? 'active' : '' ?>">
            <a href="/admin/pages.php">
                <i>📄</i> <span>Pagina's</span>
            </a>
        </li>
        <li class="<?= $activePage === 'posts' ? 'active' : '' ?>">
            <a href="/admin/posts.php">
                <i>✍️</i> <span>Blog Artikelen</span>
            </a>
        </li>
        <li class="<?= $activePage === 'categories' ? 'active' : '' ?>">
            <a href="/admin/categories.php">
                <i>📁</i> <span>Categorieën</span>
            </a>
        </li>
        <li class="<?= $activePage === 'tags' ? 'active' : '' ?>">
            <a href="/admin/tags.php">
                <i>🏷️</i> <span>Tags</span>
            </a>
        </li>
        <li class="<?= $activePage === 'media' ? 'active' : '' ?>">
            <a href="/admin/media.php">
                <i>🖼️</i> <span>Media Bibliotheek</span>
            </a>
        </li>
        <li class="<?= $activePage === 'submissions' ? 'active' : '' ?>">
            <a href="/admin/submissions.php">
                <i>📬</i> <span>Aanvragen</span>
                <?php if ($newSubmissionsCount > 0): ?>
                    <span class="badge badge-warning" style="margin-left:auto;"><?= $newSubmissionsCount ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li class="<?= $activePage === 'menus' ? 'active' : '' ?>">
            <a href="/admin/menus.php">
                <i>🧭</i> <span>Navigatiemenu's</span>
            </a>
        </li>
        <li class="<?= $activePage === 'redirects' ? 'active' : '' ?>">
            <a href="/admin/redirects.php">
                <i>🔀</i> <span>301 Redirects</span>
            </a>
        </li>
        <li class="<?= $activePage === 'settings' ? 'active' : '' ?>">
            <a href="/admin/settings.php">
                <i>⚙️</i> <span>Instellingen</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <a href="/" target="_blank" style="color:#94a3b8; text-decoration:none; display:flex; align-items:center; gap:0.5rem;">
            <i>🌐</i> <span>Bekijk Website &rarr;</span>
        </a>
    </div>
</aside>

<main class="admin-main">
    <div class="admin-topbar">
        <div>
            <h1 style="font-size:1.35rem; font-weight:700;"><?= e($pageTitle ?? 'Beheerderspaneel') ?></h1>
        </div>
        <div style="display:flex; align-items:center; gap:1.25rem;">
            <span style="font-size:0.9rem; color:var(--admin-muted);">Ingelogd als: <strong><?= e($currentAdmin['username']) ?></strong></span>
            <a href="/admin/logout.php" class="btn-adm btn-adm-secondary btn-sm" style="font-size:0.85rem;">Uitloggen</a>
        </div>
    </div>
    <div class="admin-body">
