<?php
/**
 * Lahore Catering - Database Migration Script
 * Migrates 100% of extracted WordPress data into SQLite and generates MySQL SQL dump.
 */

ini_set('memory_limit', '1024M');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Database.php';

$dataDir = __DIR__ . '/../data_backup';
$dbPath = __DIR__ . '/../database/lahore_catering.sqlite';
$mysqlDumpPath = __DIR__ . '/../database/lahore_catering_mysql.sql';

// Reset SQLite DB if exists
if (file_exists($dbPath)) {
    unlink($dbPath);
}

$db = Database::getInstance();

echo "Creating SQLite schema...\n";

$sqliteSchema = "
CREATE TABLE IF NOT EXISTS pages (
  id INTEGER PRIMARY KEY,
  title TEXT NOT NULL,
  slug TEXT NOT NULL UNIQUE,
  content TEXT,
  excerpt TEXT,
  featured_image TEXT,
  featured_image_alt TEXT,
  template TEXT DEFAULT 'default',
  status TEXT DEFAULT 'published',
  created_at TEXT NOT NULL,
  updated_at TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS posts (
  id INTEGER PRIMARY KEY,
  title TEXT NOT NULL,
  slug TEXT NOT NULL UNIQUE,
  content TEXT,
  excerpt TEXT,
  author TEXT DEFAULT 'Lahore Catering',
  featured_image TEXT,
  featured_image_alt TEXT,
  featured_image_caption TEXT,
  status TEXT DEFAULT 'published',
  views INTEGER DEFAULT 0,
  created_at TEXT NOT NULL,
  updated_at TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS categories (
  id INTEGER PRIMARY KEY,
  name TEXT NOT NULL,
  slug TEXT NOT NULL UNIQUE,
  description TEXT,
  parent_id INTEGER DEFAULT 0,
  count INTEGER DEFAULT 0
);

CREATE TABLE IF NOT EXISTS tags (
  id INTEGER PRIMARY KEY,
  name TEXT NOT NULL,
  slug TEXT NOT NULL UNIQUE,
  count INTEGER DEFAULT 0
);

CREATE TABLE IF NOT EXISTS post_categories (
  post_id INTEGER NOT NULL,
  category_id INTEGER NOT NULL,
  PRIMARY KEY (post_id, category_id)
);

CREATE TABLE IF NOT EXISTS post_tags (
  post_id INTEGER NOT NULL,
  tag_id INTEGER NOT NULL,
  PRIMARY KEY (post_id, tag_id)
);

CREATE TABLE IF NOT EXISTS media (
  id INTEGER PRIMARY KEY,
  filename TEXT NOT NULL,
  filepath TEXT NOT NULL,
  url TEXT NOT NULL,
  alt_text TEXT,
  caption TEXT,
  mime_type TEXT,
  file_size INTEGER DEFAULT 0,
  width INTEGER DEFAULT 0,
  height INTEGER DEFAULT 0,
  created_at TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS menus (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  location TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS menu_items (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  menu_id INTEGER NOT NULL,
  title TEXT NOT NULL,
  url TEXT NOT NULL,
  target TEXT DEFAULT '_self',
  order_index INTEGER DEFAULT 0,
  parent_id INTEGER DEFAULT 0
);

CREATE TABLE IF NOT EXISTS food_items (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  slug TEXT NOT NULL UNIQUE,
  description TEXT,
  price REAL DEFAULT NULL,
  category TEXT DEFAULT 'General',
  image_url TEXT,
  image_alt TEXT,
  is_featured INTEGER DEFAULT 0
);

CREATE TABLE IF NOT EXISTS settings (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  setting_key TEXT NOT NULL UNIQUE,
  setting_value TEXT,
  setting_group TEXT DEFAULT 'general'
);

CREATE TABLE IF NOT EXISTS forms (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  form_code TEXT NOT NULL UNIQUE,
  fields_json TEXT,
  recipient_email TEXT
);

CREATE TABLE IF NOT EXISTS form_submissions (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  form_id INTEGER DEFAULT 1,
  form_type TEXT NOT NULL,
  data_json TEXT NOT NULL,
  ip_address TEXT,
  user_agent TEXT,
  status TEXT DEFAULT 'new',
  created_at TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS admins (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username TEXT NOT NULL UNIQUE,
  email TEXT NOT NULL UNIQUE,
  password_hash TEXT NOT NULL,
  role TEXT DEFAULT 'admin',
  last_login TEXT,
  created_at TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS redirects (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  source_url TEXT NOT NULL UNIQUE,
  target_url TEXT NOT NULL,
  status_code INTEGER DEFAULT 301,
  hits INTEGER DEFAULT 0,
  created_at TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS seo_metadata (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  entity_type TEXT NOT NULL,
  entity_id INTEGER NOT NULL,
  meta_title TEXT,
  meta_description TEXT,
  canonical_url TEXT,
  og_title TEXT,
  og_description TEXT,
  og_image TEXT,
  twitter_title TEXT,
  twitter_description TEXT,
  twitter_image TEXT,
  schema_json TEXT,
  robots TEXT DEFAULT 'index, follow',
  UNIQUE (entity_type, entity_id)
);
";

$db->exec($sqliteSchema);

$mysqlStatements = [];
$mysqlStatements[] = file_get_contents(__DIR__ . '/../database/schema_mysql.sql');
$mysqlStatements[] = "\n-- ================= DATA INSERTS ================\n";

function mysql_escape($val) {
    if ($val === null) return 'NULL';
    return "'" . str_replace(["\\", "'", "\0", "\n", "\r"], ["\\\\", "\\'", "\\0", "\\n", "\\r"], (string)$val) . "'";
}

// 1. Migrate Categories
echo "Migrating categories...\n";
$categories = json_decode(file_get_contents("$dataDir/categories.json"), true);
$stmtCat = $db->prepare("INSERT INTO categories (id, name, slug, description, parent_id, count) VALUES (?, ?, ?, ?, ?, ?)");
foreach ($categories as $c) {
    $stmtCat->execute([$c['id'], $c['name'], $c['slug'], $c['description'] ?? '', $c['parent'] ?? 0, $c['count'] ?? 0]);
    $mysqlStatements[] = "INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `parent_id`, `count`) VALUES (" .
        "{$c['id']}, " . mysql_escape($c['name']) . ", " . mysql_escape($c['slug']) . ", " . mysql_escape($c['description'] ?? '') . ", " . (int)($c['parent'] ?? 0) . ", " . (int)($c['count'] ?? 0) . ");";
}

// 2. Migrate Tags
echo "Migrating tags...\n";
$tags = json_decode(file_get_contents("$dataDir/tags.json"), true);
$stmtTag = $db->prepare("INSERT INTO tags (id, name, slug, count) VALUES (?, ?, ?, ?)");
foreach ($tags as $t) {
    $stmtTag->execute([$t['id'], $t['name'], $t['slug'], $t['count'] ?? 0]);
    $mysqlStatements[] = "INSERT INTO `tags` (`id`, `name`, `slug`, `count`) VALUES (" .
        "{$t['id']}, " . mysql_escape($t['name']) . ", " . mysql_escape($t['slug']) . ", " . (int)($t['count'] ?? 0) . ");";
}

// 3. Migrate Media
echo "Migrating media...\n";
$media = json_decode(file_get_contents("$dataDir/media.json"), true);
$stmtMedia = $db->prepare("INSERT INTO media (id, filename, filepath, url, alt_text, caption, mime_type, file_size, width, height, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($media as $m) {
    $url = $m['source_url'] ?? '';
    $parsedPath = parse_url($url, PHP_URL_PATH);
    $filepath = ltrim($parsedPath, '/');
    $filename = basename($filepath);
    $alt = $m['alt_text'] ?? '';
    $caption = strip_tags($m['caption']['rendered'] ?? '');
    $mime = $m['mime_type'] ?? '';
    $width = $m['media_details']['width'] ?? 0;
    $height = $m['media_details']['height'] ?? 0;
    $size = $m['media_details']['filesize'] ?? 0;
    $date = date('Y-m-d H:i:s', strtotime($m['date'] ?? 'now'));

    $stmtMedia->execute([$m['id'], $filename, $filepath, $url, $alt, $caption, $mime, $size, $width, $height, $date]);
    $mysqlStatements[] = "INSERT INTO `media` (`id`, `filename`, `filepath`, `url`, `alt_text`, `caption`, `mime_type`, `file_size`, `width`, `height`, `created_at`) VALUES (" .
        "{$m['id']}, " . mysql_escape($filename) . ", " . mysql_escape($filepath) . ", " . mysql_escape($url) . ", " . mysql_escape($alt) . ", " . mysql_escape($caption) . ", " . mysql_escape($mime) . ", $size, $width, $height, '$date');";
}

// 4. Migrate Pages & Page SEO
echo "Migrating pages...\n";
$pages = json_decode(file_get_contents("$dataDir/pages.json"), true);
$stmtPage = $db->prepare("INSERT INTO pages (id, title, slug, content, excerpt, featured_image, featured_image_alt, template, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmtSeo = $db->prepare("INSERT INTO seo_metadata (entity_type, entity_id, meta_title, meta_description, canonical_url, og_title, og_description, og_image, robots) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

foreach ($pages as $p) {
    $id = $p['id'];
    $title = html_entity_decode($p['title']['rendered'] ?? '', ENT_QUOTES, 'UTF-8');
    $slug = $p['slug'];
    $content = $p['content']['rendered'] ?? '';
    $excerpt = strip_tags($p['excerpt']['rendered'] ?? '');
    $status = $p['status'] ?? 'published';
    $created = date('Y-m-d H:i:s', strtotime($p['date'] ?? 'now'));
    $updated = date('Y-m-d H:i:s', strtotime($p['modified'] ?? 'now'));
    $template = $p['template'] ?: 'default';
    
    // Featured media
    $fmUrl = null;
    $fmAlt = null;
    if (!empty($p['_embedded']['wp:featuredmedia'][0])) {
        $fmUrl = $p['_embedded']['wp:featuredmedia'][0]['source_url'] ?? null;
        $fmAlt = $p['_embedded']['wp:featuredmedia'][0]['alt_text'] ?? null;
    }

    $stmtPage->execute([$id, $title, $slug, $content, $excerpt, $fmUrl, $fmAlt, $template, $status, $created, $updated]);
    $mysqlStatements[] = "INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `excerpt`, `featured_image`, `featured_image_alt`, `template`, `status`, `created_at`, `updated_at`) VALUES (" .
        "$id, " . mysql_escape($title) . ", " . mysql_escape($slug) . ", " . mysql_escape($content) . ", " . mysql_escape($excerpt) . ", " . mysql_escape($fmUrl) . ", " . mysql_escape($fmAlt) . ", " . mysql_escape($template) . ", '$status', '$created', '$updated');";

    // SEO
    $metaTitle = $title . ' - Lahore Catering Friesland';
    $metaDesc = $excerpt ?: substr(strip_tags($content), 0, 160);
    $canonical = 'https://lahorecatering.nl/' . ($slug === 'home' ? '' : $slug . '/');
    $stmtSeo->execute(['page', $id, $metaTitle, $metaDesc, $canonical, $metaTitle, $metaDesc, $fmUrl, 'index, follow']);
    $mysqlStatements[] = "INSERT INTO `seo_metadata` (`entity_type`, `entity_id`, `meta_title`, `meta_description`, `canonical_url`, `og_title`, `og_description`, `og_image`, `robots`) VALUES (" .
        "'page', $id, " . mysql_escape($metaTitle) . ", " . mysql_escape($metaDesc) . ", " . mysql_escape($canonical) . ", " . mysql_escape($metaTitle) . ", " . mysql_escape($metaDesc) . ", " . mysql_escape($fmUrl) . ", 'index, follow');";
}

// 5. Migrate Posts & Post SEO & Post Taxonomies
echo "Migrating posts...\n";
$posts = json_decode(file_get_contents("$dataDir/posts.json"), true);
$stmtPost = $db->prepare("INSERT INTO posts (id, title, slug, content, excerpt, author, featured_image, featured_image_alt, featured_image_caption, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmtPostCat = $db->prepare("INSERT OR IGNORE INTO post_categories (post_id, category_id) VALUES (?, ?)");
$stmtPostTag = $db->prepare("INSERT OR IGNORE INTO post_tags (post_id, tag_id) VALUES (?, ?)");

foreach ($posts as $p) {
    $id = $p['id'];
    $title = html_entity_decode($p['title']['rendered'] ?? '', ENT_QUOTES, 'UTF-8');
    $slug = $p['slug'];
    $content = $p['content']['rendered'] ?? '';
    $excerpt = strip_tags($p['excerpt']['rendered'] ?? '');
    $status = $p['status'] ?? 'published';
    $created = date('Y-m-d H:i:s', strtotime($p['date'] ?? 'now'));
    $updated = date('Y-m-d H:i:s', strtotime($p['modified'] ?? 'now'));
    $author = 'Lahore Catering';
    if (!empty($p['_embedded']['author'][0]['name'])) {
        $author = $p['_embedded']['author'][0]['name'];
    }

    $fmUrl = null;
    $fmAlt = null;
    $fmCap = null;
    if (!empty($p['_embedded']['wp:featuredmedia'][0])) {
        $fmUrl = $p['_embedded']['wp:featuredmedia'][0]['source_url'] ?? null;
        $fmAlt = $p['_embedded']['wp:featuredmedia'][0]['alt_text'] ?? null;
        $fmCap = strip_tags($p['_embedded']['wp:featuredmedia'][0]['caption']['rendered'] ?? '');
    }

    $stmtPost->execute([$id, $title, $slug, $content, $excerpt, $author, $fmUrl, $fmAlt, $fmCap, $status, $created, $updated]);
    $mysqlStatements[] = "INSERT INTO `posts` (`id`, `title`, `slug`, `content`, `excerpt`, `author`, `featured_image`, `featured_image_alt`, `featured_image_caption`, `status`, `created_at`, `updated_at`) VALUES (" .
        "$id, " . mysql_escape($title) . ", " . mysql_escape($slug) . ", " . mysql_escape($content) . ", " . mysql_escape($excerpt) . ", " . mysql_escape($author) . ", " . mysql_escape($fmUrl) . ", " . mysql_escape($fmAlt) . ", " . mysql_escape($fmCap) . ", '$status', '$created', '$updated');";

    // Categories
    if (!empty($p['categories'])) {
        foreach ($p['categories'] as $catId) {
            $stmtPostCat->execute([$id, $catId]);
            $mysqlStatements[] = "INSERT IGNORE INTO `post_categories` (`post_id`, `category_id`) VALUES ($id, $catId);";
        }
    }

    // Tags
    if (!empty($p['tags'])) {
        foreach ($p['tags'] as $tagId) {
            $stmtPostTag->execute([$id, $tagId]);
            $mysqlStatements[] = "INSERT IGNORE INTO `post_tags` (`post_id`, `tag_id`) VALUES ($id, $tagId);";
        }
    }

    // SEO
    $metaTitle = $title . ' - Lahore Catering';
    $metaDesc = $excerpt ?: substr(strip_tags($content), 0, 160);
    $canonical = 'https://lahorecatering.nl/' . $slug . '/';
    $stmtSeo->execute(['post', $id, $metaTitle, $metaDesc, $canonical, $metaTitle, $metaDesc, $fmUrl, 'index, follow']);
    $mysqlStatements[] = "INSERT INTO `seo_metadata` (`entity_type`, `entity_id`, `meta_title`, `meta_description`, `canonical_url`, `og_title`, `og_description`, `og_image`, `robots`) VALUES (" .
        "'post', $id, " . mysql_escape($metaTitle) . ", " . mysql_escape($metaDesc) . ", " . mysql_escape($canonical) . ", " . mysql_escape($metaTitle) . ", " . mysql_escape($metaDesc) . ", " . mysql_escape($fmUrl) . ", 'index, follow');";
}

// 6. Migrate Food Items
echo "Migrating food items...\n";
if (file_exists("$dataDir/food_items.json")) {
    $foodItems = json_decode(file_get_contents("$dataDir/food_items.json"), true);
    $stmtFood = $db->prepare("INSERT INTO food_items (name, slug, description, image_url, image_alt) VALUES (?, ?, ?, ?, ?)");
    foreach ($foodItems as $fi) {
        $imgUrl = $fi['images'][0]['loc'] ?? null;
        $imgAlt = $fi['images'][0]['title'] ?? $fi['title'];
        $stmtFood->execute([$fi['title'], $fi['slug'], $fi['description'], $imgUrl, $imgAlt]);
        $mysqlStatements[] = "INSERT INTO `food_items` (`name`, `slug`, `description`, `image_url`, `image_alt`) VALUES (" .
            mysql_escape($fi['title']) . ", " . mysql_escape($fi['slug']) . ", " . mysql_escape($fi['description']) . ", " . mysql_escape($imgUrl) . ", " . mysql_escape($imgAlt) . ");";
    }
}

// 7. Migrate Menus
echo "Migrating navigation menus...\n";
$db->exec("INSERT INTO menus (id, name, location) VALUES (1, 'Hoofdmenu', 'primary'), (2, 'Footermenu', 'footer')");
$mysqlStatements[] = "INSERT INTO `menus` (`id`, `name`, `location`) VALUES (1, 'Hoofdmenu', 'primary'), (2, 'Footermenu', 'footer');";

$primaryMenuItems = [
    ['title' => 'Home', 'url' => '/', 'order' => 1],
    ['title' => 'Catering', 'url' => '/catering/', 'order' => 2],
    ['title' => 'Buffet Restaurant', 'url' => '/buffet/', 'order' => 3],
    ['title' => 'Foodtruck', 'url' => '/foodtruck/', 'order' => 4],
    ['title' => 'Video & Foto Galerij', 'url' => '/video-en-foto-galerij/', 'order' => 5],
    ['title' => 'Blog', 'url' => '/blog/', 'order' => 6],
    ['title' => 'Online Bestellen', 'url' => ORDER_SIDES_URL, 'target' => '_blank', 'order' => 7],
    ['title' => 'Contact', 'url' => '/contact/', 'order' => 8]
];

$stmtMenuItem = $db->prepare("INSERT INTO menu_items (menu_id, title, url, target, order_index) VALUES (?, ?, ?, ?, ?)");
foreach ($primaryMenuItems as $item) {
    $target = $item['target'] ?? '_self';
    $stmtMenuItem->execute([1, $item['title'], $item['url'], $target, $item['order']]);
    $mysqlStatements[] = "INSERT INTO `menu_items` (`menu_id`, `title`, `url`, `target`, `order_index`) VALUES (" .
        "1, " . mysql_escape($item['title']) . ", " . mysql_escape($item['url']) . ", '$target', {$item['order']});";
}

$footerMenuItems = [
    ['title' => 'Buffet restaurant Friesland', 'url' => '/buffet-restaurant-friesland/', 'order' => 1],
    ['title' => 'Halal Restaurant Friesland', 'url' => '/halal-restaurant-friesland/', 'order' => 2],
    ['title' => 'Halal Catering Friesland', 'url' => '/halal-catering-friesland/', 'order' => 3],
    ['title' => 'Pakistaans Restaurant Leeuwarden', 'url' => '/pakistaans-restaurant-leeuwarden/', 'order' => 4],
    ['title' => 'Eten Bestellen Leeuwarden', 'url' => '/eten-bestellen-leeuwarden/', 'order' => 5],
    ['title' => 'Afhaal en Bezorging Stiens', 'url' => '/afhaal-en-bezorging-stiens/', 'order' => 6]
];
foreach ($footerMenuItems as $item) {
    $stmtMenuItem->execute([2, $item['title'], $item['url'], '_self', $item['order']]);
    $mysqlStatements[] = "INSERT INTO `menu_items` (`menu_id`, `title`, `url`, `target`, `order_index`) VALUES (" .
        "2, " . mysql_escape($item['title']) . ", " . mysql_escape($item['url']) . ", '_self', {$item['order']});";
}

// 8. Migrate Website Settings
echo "Migrating settings...\n";
$settings = [
    'site_name' => APP_NAME,
    'site_tagline' => 'Het lekkerste Pakistaanse en Indiase eten van Friesland',
    'contact_phone' => CONTACT_PHONE,
    'contact_email' => CONTACT_EMAIL,
    'contact_address' => CONTACT_ADDRESS,
    'facebook_url' => FACEBOOK_URL,
    'order_online_url' => ORDER_SIDES_URL,
    'opening_hours_weekday' => 'Maandag t/m donderdag 16:00 tot 20:00',
    'opening_hours_weekend' => 'Vrijdag t/m zondag 16:00 tot 21:00',
    'gloria_food_ruid' => GLORIA_FOOD_RUID,
    'copyright_text' => 'Lahore Catering levert in heel Friesland unieke Pakistaanse gerechten.'
];
$stmtSetting = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
foreach ($settings as $k => $v) {
    $stmtSetting->execute([$k, $v]);
    $mysqlStatements[] = "INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES (" . mysql_escape($k) . ", " . mysql_escape($v) . ");";
}

// 9. Migrate Forms
echo "Migrating forms...\n";
$forms = [
    [
        'name' => 'Reserveringsaanvraag / Catering Offerte',
        'code' => 'reservation_form',
        'email' => CONTACT_EMAIL,
        'fields' => json_encode([
            ['name' => 'date', 'label' => 'Gewenste datum', 'type' => 'date', 'required' => true],
            ['name' => 'time', 'label' => 'Gewenste tijd', 'type' => 'text', 'required' => true],
            ['name' => 'name', 'label' => 'Volledige naam', 'type' => 'text', 'required' => true],
            ['name' => 'email', 'label' => 'E-mailadres', 'type' => 'email', 'required' => true],
            ['name' => 'phone', 'label' => 'Telefoonnummer', 'type' => 'tel', 'required' => true],
            ['name' => 'guests', 'label' => 'Aantal personen', 'type' => 'number', 'required' => true],
            ['name' => 'buffet_type', 'label' => 'Buffet type', 'type' => 'select', 'options' => [
                'Budget Buffet (€18,50 p.p.)',
                'Chef Special Buffet (€22,50 p.p.)',
                'Vegetarisch Buffet (€15,50 p.p.)',
                'Maatwerk / Weet ik nog niet'
            ]],
            ['name' => 'notes', 'label' => 'Vragen of opmerkingen', 'type' => 'textarea', 'required' => false]
        ])
    ],
    [
        'name' => 'Contactformulier',
        'code' => 'contact_form',
        'email' => CONTACT_EMAIL,
        'fields' => json_encode([
            ['name' => 'name', 'label' => 'Naam', 'type' => 'text', 'required' => true],
            ['name' => 'email', 'label' => 'E-mail', 'type' => 'email', 'required' => true],
            ['name' => 'phone', 'label' => 'Telefoon', 'type' => 'tel', 'required' => false],
            ['name' => 'message', 'label' => 'Bericht', 'type' => 'textarea', 'required' => true]
        ])
    ]
];
$stmtForm = $db->prepare("INSERT INTO forms (name, form_code, fields_json, recipient_email) VALUES (?, ?, ?, ?)");
foreach ($forms as $f) {
    $stmtForm->execute([$f['name'], $f['code'], $f['fields'], $f['email']]);
    $mysqlStatements[] = "INSERT INTO `forms` (`name`, `form_code`, `fields_json`, `recipient_email`) VALUES (" .
        mysql_escape($f['name']) . ", " . mysql_escape($f['code']) . ", " . mysql_escape($f['fields']) . ", " . mysql_escape($f['email']) . ");";
}

// 10. Migrate Redirects
echo "Migrating redirects...\n";
if (file_exists("$dataDir/redirects.json")) {
    $redirects = json_decode(file_get_contents("$dataDir/redirects.json"), true);
    $stmtRedir = $db->prepare("INSERT INTO redirects (source_url, target_url, status_code, created_at) VALUES (?, ?, ?, ?)");
    $now = date('Y-m-d H:i:s');
    foreach ($redirects as $src => $dest) {
        $stmtRedir->execute([$src, $dest, 301, $now]);
        $mysqlStatements[] = "INSERT INTO `redirects` (`source_url`, `target_url`, `status_code`, `created_at`) VALUES (" .
            mysql_escape($src) . ", " . mysql_escape($dest) . ", 301, '$now');";
    }
}

// 11. Create Admin User
echo "Creating default administrator...\n";
$adminPass = 'Lahore2026!Admin';
$adminHash = password_hash($adminPass, PASSWORD_BCRYPT);
$now = date('Y-m-d H:i:s');
$db->prepare("INSERT INTO admins (username, email, password_hash, role, created_at) VALUES (?, ?, ?, ?, ?)")
   ->execute(['admin', CONTACT_EMAIL, $adminHash, 'admin', $now]);

$mysqlStatements[] = "INSERT INTO `admins` (`username`, `email`, `password_hash`, `role`, `created_at`) VALUES ('admin', " . mysql_escape(CONTACT_EMAIL) . ", " . mysql_escape($adminHash) . ", 'admin', '$now');";

// Save MySQL Export
file_put_contents($mysqlDumpPath, implode("\n", $mysqlStatements));

echo "\n============================================\n";
echo "MIGRATION COMPLETE!\n";
echo "SQLite Database: $dbPath\n";
echo "MySQL Dump File: $mysqlDumpPath\n";

// Print verification counts from SQLite
echo "\n--- VERIFICATION OF IMPORTED DATA ---\n";
echo "Pages in DB: " . $db->query("SELECT COUNT(*) FROM pages")->fetchColumn() . "\n";
echo "Posts in DB: " . $db->query("SELECT COUNT(*) FROM posts")->fetchColumn() . "\n";
echo "Categories in DB: " . $db->query("SELECT COUNT(*) FROM categories")->fetchColumn() . "\n";
echo "Tags in DB: " . $db->query("SELECT COUNT(*) FROM tags")->fetchColumn() . "\n";
echo "Media in DB: " . $db->query("SELECT COUNT(*) FROM media")->fetchColumn() . "\n";
echo "Food items in DB: " . $db->query("SELECT COUNT(*) FROM food_items")->fetchColumn() . "\n";
echo "Redirects in DB: " . $db->query("SELECT COUNT(*) FROM redirects")->fetchColumn() . "\n";
echo "SEO records in DB: " . $db->query("SELECT COUNT(*) FROM seo_metadata")->fetchColumn() . "\n";
echo "Menu items in DB: " . $db->query("SELECT COUNT(*) FROM menu_items")->fetchColumn() . "\n";
echo "Settings in DB: " . $db->query("SELECT COUNT(*) FROM settings")->fetchColumn() . "\n";
echo "============================================\n";
