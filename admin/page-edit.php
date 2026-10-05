<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../models/Page.php';
require_once __DIR__ . '/../models/Seo.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$page = $id ? Page::findById($id) : null;
$seo = $id ? Seo::get('page', $id) : null;

$pageTitle = $page ? 'Pagina Bewerken: ' . $page['title'] : 'Nieuwe Pagina Toevoegen';
$activePage = 'pages';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $error = 'Sessiebeveiliging ongeldig. Vernieuw de pagina.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $content = $_POST['content'] ?? '';
        $excerpt = trim($_POST['excerpt'] ?? '');
        $status = $_POST['status'] ?? 'published';
        $template = $_POST['template'] ?? 'default';
        $featuredImage = trim($_POST['featured_image'] ?? '');
        $featuredImageAlt = trim($_POST['featured_image_alt'] ?? '');

        // SEO
        $metaTitle = trim($_POST['meta_title'] ?? '');
        $metaDesc = trim($_POST['meta_description'] ?? '');
        $canonicalUrl = trim($_POST['canonical_url'] ?? '');

        if (empty($title) || empty($slug)) {
            $error = 'Titel en slug zijn verplicht.';
        } else {
            // Normalize slug
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '-', $slug));
            $slug = trim(preg_replace('/-+/', '-', $slug), '-');

            $data = [
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'excerpt' => $excerpt,
                'featured_image' => $featuredImage ?: null,
                'featured_image_alt' => $featuredImageAlt ?: null,
                'template' => $template,
                'status' => $status
            ];

            if ($page) {
                Page::update($id, $data);
                Seo::set('page', $id, [
                    'meta_title' => $metaTitle,
                    'meta_description' => $metaDesc,
                    'canonical_url' => $canonicalUrl
                ]);
                $message = 'Pagina succesvol bijgewerkt!';
                $page = Page::findById($id);
                $seo = Seo::get('page', $id);
            } else {
                $newId = Page::create($data);
                Seo::set('page', $newId, [
                    'meta_title' => $metaTitle ?: ($title . ' - Lahore Catering'),
                    'meta_description' => $metaDesc ?: $excerpt,
                    'canonical_url' => $canonicalUrl ?: (CANONICAL_DOMAIN . '/' . $slug . '/')
                ]);
                header("Location: /admin/page-edit.php?id=$newId&msg=created");
                exit;
            }
        }
    }
}

if (isset($_GET['msg']) && $_GET['msg'] === 'created') {
    $message = 'Nieuwe pagina succesvol aangemaakt!';
}

$csrfToken = generate_csrf_token();
require_once __DIR__ . '/includes/header.php';
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
    <h2 style="font-size:1.5rem; font-weight:700;"><?= e($pageTitle) ?></h2>
    <a href="/admin/pages.php" class="btn-adm btn-adm-secondary">&larr; Terug naar Pagina's</a>
</div>

<?php if (!empty($message)): ?>
    <div style="background:#dcfce7; color:#15803d; padding:0.85rem 1.25rem; border-radius:6px; margin-bottom:1.5rem; font-weight:600;">
        <?= e($message) ?>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div style="background:#fee2e2; color:#b91c1c; padding:0.85rem 1.25rem; border-radius:6px; margin-bottom:1.5rem; font-weight:600;">
        <?= e($error) ?>
    </div>
<?php endif; ?>

<form method="post" action="">
    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:2rem;">
        <!-- Left Column: Content -->
        <div>
            <div class="admin-panel">
                <div class="panel-header">
                    <h3 style="font-size:1.1rem; font-weight:700;">Inhoud & Tekst (Nederlands)</h3>
                </div>
                <div class="panel-body">
                    <div>
                        <label class="form-label" for="title">Paginatitel *</label>
                        <input type="text" class="form-input" id="title" name="title" value="<?= e($page['title'] ?? '') ?>" required>
                    </div>

                    <div>
                        <label class="form-label" for="slug">Slug / URL (zonder slashes) *</label>
                        <input type="text" class="form-input" id="slug" name="slug" value="<?= e($page['slug'] ?? '') ?>" required style="font-family:monospace;">
                    </div>

                    <div>
                        <label class="form-label" for="content">Volledige Pagina Inhoud (HTML toegestaan)</label>
                        <textarea class="form-input" id="content" name="content" rows="18" style="font-family:Consolas, monospace; font-size:0.9rem; line-height:1.5;"><?= htmlspecialchars($page['content'] ?? '') ?></textarea>
                    </div>

                    <div>
                        <label class="form-label" for="excerpt">Korte Samenvatting (Excerpt)</label>
                        <textarea class="form-input" id="excerpt" name="excerpt" rows="3"><?= e($page['excerpt'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- SEO Settings Panel -->
            <div class="admin-panel">
                <div class="panel-header">
                    <h3 style="font-size:1.1rem; font-weight:700;">SEO Metadata</h3>
                </div>
                <div class="panel-body">
                    <div>
                        <label class="form-label" for="meta_title">SEO Titel (Meta Title)</label>
                        <input type="text" class="form-input" id="meta_title" name="meta_title" value="<?= e($seo['meta_title'] ?? '') ?>" placeholder="Laat leeg voor standaard paginatitel">
                    </div>

                    <div>
                        <label class="form-label" for="meta_description">Meta Omschrijving (150-160 tekens)</label>
                        <textarea class="form-input" id="meta_description" name="meta_description" rows="3"><?= e($seo['meta_description'] ?? '') ?></textarea>
                    </div>

                    <div>
                        <label class="form-label" for="canonical_url">Canonieke URL (Canonical URL)</label>
                        <input type="url" class="form-input" id="canonical_url" name="canonical_url" value="<?= e($seo['canonical_url'] ?? '') ?>" placeholder="https://lahorecatering.nl/uw-slug/">
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Settings & Media -->
        <div>
            <div class="admin-panel">
                <div class="panel-header">
                    <h3 style="font-size:1.1rem; font-weight:700;">Publicatie</h3>
                </div>
                <div class="panel-body">
                    <div>
                        <label class="form-label" for="status">Status</label>
                        <select class="form-input" id="status" name="status">
                            <option value="published" <?= ($page['status'] ?? '') === 'published' ? 'selected' : '' ?>>Gepubliceerd</option>
                            <option value="draft" <?= ($page['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Concept</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label" for="template">Pagina Template</label>
                        <select class="form-input" id="template" name="template">
                            <option value="default" <?= ($page['template'] ?? '') === 'default' ? 'selected' : '' ?>>Standaard Pagina</option>
                            <option value="home" <?= ($page['template'] ?? '') === 'home' ? 'selected' : '' ?>>Homepage</option>
                            <option value="catering" <?= ($page['template'] ?? '') === 'catering' ? 'selected' : '' ?>>Catering</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-adm btn-adm-primary" style="width:100%; justify-content:center; padding:0.85rem; font-size:1rem;">
                        <?= $page ? 'Wijzigingen Opslaan' : 'Pagina Publiceren' ?>
                    </button>
                </div>
            </div>

            <!-- Featured Image Panel -->
            <div class="admin-panel">
                <div class="panel-header">
                    <h3 style="font-size:1.1rem; font-weight:700;">Uitgelichte Afbeelding</h3>
                </div>
                <div class="panel-body">
                    <?php if (!empty($page['featured_image'])): ?>
                    <div style="margin-bottom:1rem; border-radius:6px; overflow:hidden; border:1px solid var(--admin-border);">
                        <img src="<?= e($page['featured_image']) ?>" alt="Voorvertoning" style="width:100%; height:160px; object-fit:cover;">
                    </div>
                    <?php endif; ?>

                    <div>
                        <label class="form-label" for="featured_image">Afbeeldings-URL</label>
                        <input type="text" class="form-input" id="featured_image" name="featured_image" value="<?= e($page['featured_image'] ?? '') ?>" placeholder="/uploads/2026/...">
                    </div>

                    <div>
                        <label class="form-label" for="featured_image_alt">ALT Tekst (SEO & Toegankelijkheid)</label>
                        <input type="text" class="form-input" id="featured_image_alt" name="featured_image_alt" value="<?= e($page['featured_image_alt'] ?? '') ?>">
                    </div>

                    <a href="/admin/media.php" target="_blank" style="font-size:0.85rem; color:var(--admin-primary); display:block; text-align:right;">
                        Kies uit Media Bibliotheek &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
