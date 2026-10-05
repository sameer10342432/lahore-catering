<?php
/**
 * Header Component with Dynamic SEO
 */

$metaTitle = $metaTitle ?? (APP_NAME . ' | Authentiek Pakistaans Eten & Catering in Friesland');
$metaDesc = $metaDesc ?? 'Lahore Catering Friesland verzorgt heerlijke Pakistaanse en Indiase catering voor feesten, bruiloften en evenementen. Bestel online of reserveer uw buffet!';
$canonicalUrl = $canonicalUrl ?? (CANONICAL_DOMAIN . parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));
$ogImage = $ogImage ?? (SITE_URL . '/uploads/2026/10/Pakistani-Feast-with-Frisian-Charm.png');
$ogType = $ogType ?? 'website';
$robots = $robots ?? 'index, follow';
?>
<!DOCTYPE html>
<html lang="nl-NL" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($metaTitle) ?></title>
    <meta name="description" content="<?= e($metaDesc) ?>">
    <meta name="robots" content="<?= e($robots) ?>">
    <link rel="canonical" href="<?= e($canonicalUrl) ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:locale" content="nl_NL">
    <meta property="og:type" content="<?= e($ogType) ?>">
    <meta property="og:title" content="<?= e($metaTitle) ?>">
    <meta property="og:description" content="<?= e($metaDesc) ?>">
    <meta property="og:url" content="<?= e($canonicalUrl) ?>">
    <meta property="og:site_name" content="<?= e(APP_NAME) ?>">
    <meta property="og:image" content="<?= e($ogImage) ?>">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($metaTitle) ?>">
    <meta name="twitter:description" content="<?= e($metaDesc) ?>">
    <meta name="twitter:image" content="<?= e($ogImage) ?>">

    <!-- Favicon (Full Color) -->
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png?v=2">
    <link rel="icon" type="image/png" sizes="192x192" href="/assets/images/favicon.png?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png?v=2">
    <link rel="shortcut icon" href="/favicon.ico?v=2">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">

    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    <?= Seo::generateLocalBusinessSchema() ?>
    </script>
    <?php if (!empty($articleSchema)): ?>
    <script type="application/ld+json">
    <?= $articleSchema ?>
    </script>
    <?php endif; ?>
</head>
<body>

<!-- Top notification bar -->
<div class="topbar">
    <div class="container topbar-content">
        <div class="topbar-info">
            <span class="topbar-item">📍 <?= e(CONTACT_ADDRESS) ?></span>
            <span class="topbar-item">⏰ Ma-Do: 16:00 - 20:00 | Vr-Zo: 16:00 - 21:00</span>
        </div>
        <div class="topbar-contact">
            <span class="topbar-item">📞 Bel direct: <a href="tel:<?= e(CONTACT_PHONE_RAW) ?>"><?= e(CONTACT_PHONE) ?></a></span>
        </div>
    </div>
</div>

<!-- Header / Navigation -->
<header class="site-header">
    <div class="container navbar">
        <a href="/" class="site-logo" title="<?= e(APP_NAME) ?>">
            <img src="/assets/images/logo.png" alt="<?= e(APP_NAME) ?>" class="site-logo-img" width="220" height="60" onerror="this.style.display='none'; document.getElementById('logo-text-fallback').style.display='block';">
            <span id="logo-text-fallback" class="logo-fallback-text" style="display:none; font-family:var(--font-heading); font-size:1.5rem; font-weight:800; color:var(--primary-color); letter-spacing:-0.5px;">Lahore Catering</span>
        </a>

        <button class="menu-toggle" aria-label="Menu openen" aria-expanded="false">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>

        <ul class="nav-menu">
            <?php
            $currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
            $navItems = Menu::getByLocation('primary');
            foreach ($navItems as $item):
                $isActive = ($item['url'] === '/' && $currentUri === '/') || ($item['url'] !== '/' && str_starts_with($currentUri, $item['url']));
            ?>
            <li>
                <a href="<?= e($item['url']) ?>" class="nav-link <?= $isActive ? 'active' : '' ?>" target="<?= e($item['target']) ?>">
                    <?= e($item['title']) ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>

        <div class="nav-actions">
            <a href="<?= e(ORDER_SIDES_URL) ?>" target="_blank" rel="noopener" class="btn btn-primary btn-sm">
                Bestel Online
            </a>
        </div>
    </div>
</header>
<main id="main-content">
