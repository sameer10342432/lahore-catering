<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Tag.php';
require_once __DIR__ . '/../models/Seo.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post = $id ? Post::findById($id) : null;
$seo = $id ? Seo::get('post', $id) : null;

$pageTitle = $post ? 'Artikel Bewerken: ' . $post['title'] : 'Nieuw Blogartikel';
$activePage = 'posts';

$categories = Category::all();
$tags = Tag::all();

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
        $author = trim($_POST['author'] ?? 'Lahore Catering');
        $status = $_POST['status'] ?? 'published';
        $featuredImage = trim($_POST['featured_image'] ?? '');
        $featuredImageAlt = trim($_POST['featured_image_alt'] ?? '');
        $featuredImageCaption = trim($_POST['featured_image_caption'] ?? '');
        
        $selectedCats = $_POST['categories'] ?? [];
        $selectedTags = $_POST['tags'] ?? [];

        // SEO
        $metaTitle = trim($_POST['meta_title'] ?? '');
        $metaDesc = trim($_POST['meta_description'] ?? '');
        $canonicalUrl = trim($_POST['canonical_url'] ?? '');

        if (empty($title) || empty($slug)) {
            $error = 'Titel en slug zijn verplicht.';
        } else {
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '-', $slug));
            $slug = trim(preg_replace('/-+/', '-', $slug), '-');

            $data = [
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'excerpt' => $excerpt,
                'author' => $author,
                'featured_image' => $featuredImage ?: null,
                'featured_image_alt' => $featuredImageAlt ?: null,
                'featured_image_caption' => $featuredImageCaption ?: null,
                'status' => $status,
                'categories' => $selectedCats,
                'tags' => $selectedTags
            ];

            if ($post) {
                Post::update($id, $data);
                Seo::set('post', $id, [
                    'meta_title' => $metaTitle,
                    'meta_description' => $metaDesc,
                    'canonical_url' => $canonicalUrl
                ]);
                $message = 'Blogartikel succesvol bijgewerkt!';
                $post = Post::findById($id);
                $seo = Seo::get('post', $id);
            } else {
                $newId = Post::create($data);
                Seo::set('post', $newId, [
                    'meta_title' => $metaTitle ?: ($title . ' - Lahore Catering'),
                    'meta_description' => $metaDesc ?: $excerpt,
                    'canonical_url' => $canonicalUrl ?: (CANONICAL_DOMAIN . '/' . $slug . '/')
                ]);
                header("Location: /admin/post-edit.php?id=$newId&msg=created");
                exit;
            }
        }
    }
}

if (isset($_GET['msg']) && $_GET['msg'] === 'created') {
    $message = 'Nieuw blogartikel succesvol gepubliceerd!';
}

$postCatIds = !empty($post['categories']) ? array_column($post['categories'], 'id') : [];
$postTagIds = !empty($post['tags']) ? array_column($post['tags'], 'id') : [];

$csrfToken = generate_csrf_token();
require_once __DIR__ . '/includes/header.php';
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
    <h2 style="font-size:1.5rem; font-weight:700;"><?= e($pageTitle) ?></h2>
    <a href="/admin/posts.php" class="btn-adm btn-adm-secondary">&larr; Terug naar Artikelen</a>
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
        <!-- Left: Content & SEO -->
        <div>
            <div class="admin-panel">
                <div class="panel-header">
                    <h3 style="font-size:1.1rem; font-weight:700;">Artikel Inhoud (Nederlands)</h3>
                </div>
                <div class="panel-body">
                    <div>
                        <label class="form-label" for="title">Artikeltitel *</label>
                        <input type="text" class="form-input" id="title" name="title" value="<?= e($post['title'] ?? '') ?>" required>
                    </div>

                    <div>
                        <label class="form-label" for="slug">Slug / URL (bijv. traditioneel-pakistaans-eten-friesland) *</label>
                        <input type="text" class="form-input" id="slug" name="slug" value="<?= e($post['slug'] ?? '') ?>" required style="font-family:monospace;">
                    </div>

                    <div>
                        <label class="form-label" for="content">Volledige Tekst (HTML toegestaan)</label>
                        <textarea class="form-input" id="content" name="content" rows="20" style="font-family:Consolas, monospace; font-size:0.9rem; line-height:1.5;"><?= htmlspecialchars($post['content'] ?? '') ?></textarea>
                    </div>

                    <div>
                        <label class="form-label" for="excerpt">Samenvatting (Excerpt)</label>
                        <textarea class="form-input" id="excerpt" name="excerpt" rows="3"><?= e($post['excerpt'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="admin-panel">
                <div class="panel-header">
                    <h3 style="font-size:1.1rem; font-weight:700;">SEO Instellingen</h3>
                </div>
                <div class="panel-body">
                    <div>
                        <label class="form-label" for="meta_title">SEO Titel</label>
                        <input type="text" class="form-input" id="meta_title" name="meta_title" value="<?= e($seo['meta_title'] ?? '') ?>" placeholder="Standaard: Titel - Lahore Catering">
                    </div>

                    <div>
                        <label class="form-label" for="meta_description">Meta Omschrijving</label>
                        <textarea class="form-input" id="meta_description" name="meta_description" rows="3"><?= e($seo['meta_description'] ?? '') ?></textarea>
                    </div>

                    <div>
                        <label class="form-label" for="canonical_url">Canonieke URL</label>
                        <input type="url" class="form-input" id="canonical_url" name="canonical_url" value="<?= e($seo['canonical_url'] ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Taxonomies, Publishing & Media -->
        <div>
            <!-- Publish Actions -->
            <div class="admin-panel">
                <div class="panel-header">
                    <h3 style="font-size:1.1rem; font-weight:700;">Publicatie</h3>
                </div>
                <div class="panel-body">
                    <div>
                        <label class="form-label" for="status">Status</label>
                        <select class="form-input" id="status" name="status">
                            <option value="published" <?= ($post['status'] ?? '') === 'published' ? 'selected' : '' ?>>Gepubliceerd</option>
                            <option value="draft" <?= ($post['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Concept</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label" for="author">Auteur</label>
                        <input type="text" class="form-input" id="author" name="author" value="<?= e($post['author'] ?? 'Lahore Catering') ?>">
                    </div>

                    <button type="submit" class="btn-adm btn-adm-primary" style="width:100%; justify-content:center; padding:0.85rem; font-size:1rem;">
                        <?= $post ? 'Wijzigingen Opslaan' : 'Artikel Publiceren' ?>
                    </button>
                </div>
            </div>

            <!-- Categories -->
            <div class="admin-panel">
                <div class="panel-header">
                    <h3 style="font-size:1.1rem; font-weight:700;">Categorieën</h3>
                </div>
                <div class="panel-body">
                    <?php foreach ($categories as $cat): ?>
                    <label style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.5rem; cursor:pointer;">
                        <input type="checkbox" name="categories[]" value="<?= $cat['id'] ?>" <?= in_array($cat['id'], $postCatIds) ? 'checked' : '' ?>>
                        <span><?= e($cat['name']) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Tags -->
            <div class="admin-panel">
                <div class="panel-header">
                    <h3 style="font-size:1.1rem; font-weight:700;">Tags</h3>
                </div>
                <div class="panel-body" style="max-height:220px; overflow-y:auto;">
                    <?php foreach ($tags as $tag): ?>
                    <label style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.4rem; font-size:0.9rem; cursor:pointer;">
                        <input type="checkbox" name="tags[]" value="<?= $tag['id'] ?>" <?= in_array($tag['id'], $postTagIds) ? 'checked' : '' ?>>
                        <span><?= e($tag['name']) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Featured Image -->
            <div class="admin-panel">
                <div class="panel-header">
                    <h3 style="font-size:1.1rem; font-weight:700;">Uitgelichte Afbeelding</h3>
                </div>
                <div class="panel-body">
                    <?php if (!empty($post['featured_image'])): ?>
                    <div style="margin-bottom:1rem; border-radius:6px; overflow:hidden; border:1px solid var(--admin-border);">
                        <img src="<?= e($post['featured_image']) ?>" alt="Voorvertoning" style="width:100%; height:160px; object-fit:cover;">
                    </div>
                    <?php endif; ?>

                    <div>
                        <label class="form-label" for="featured_image">Afbeeldings-URL</label>
                        <input type="text" class="form-input" id="featured_image" name="featured_image" value="<?= e($post['featured_image'] ?? '') ?>" placeholder="/uploads/2026/...">
                    </div>

                    <div>
                        <label class="form-label" for="featured_image_alt">ALT Tekst</label>
                        <input type="text" class="form-input" id="featured_image_alt" name="featured_image_alt" value="<?= e($post['featured_image_alt'] ?? '') ?>">
                    </div>

                    <div>
                        <label class="form-label" for="featured_image_caption">Onderschrift (Caption)</label>
                        <input type="text" class="form-input" id="featured_image_caption" name="featured_image_caption" value="<?= e($post['featured_image_caption'] ?? '') ?>">
                    </div>

                    <a href="/admin/media.php" target="_blank" style="font-size:0.85rem; color:var(--admin-primary); display:block; text-align:right;">
                        Media Bibliotheek Openen &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
