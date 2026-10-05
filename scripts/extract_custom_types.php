<?php
/**
 * Extract Custom Post Types:
 * - food items
 * - recipes
 * - testimonials
 * - buckets
 * - galleries
 * - navigation menus
 */

$backupDir = __DIR__ . '/../data_backup';
$cacheDir = __DIR__ . '/../httpdocs/wp-content/cache/wp-rocket/lahorecatering.nl';

function get_html_for_url($url) {
    global $cacheDir;
    // Check if in WP-Rocket cache
    $parsed = parse_url($url);
    $path = trim($parsed['path'] ?? '', '/');
    
    $localFile = $cacheDir . '/' . ($path ? $path . '/' : '') . 'index-https.html';
    if (file_exists($localFile)) {
        return file_get_contents($localFile);
    }
    
    // Fallback: fetch from live site
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Lahore-Custom/1.0',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 20
    ]);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}

// 1. Extract Navigation Menu from Homepage or Cached Pages
$homeHtml = get_html_for_url('https://lahorecatering.nl/');
$navItems = [];

if ($homeHtml) {
    // Extract menu items from HTML
    $dom = new DOMDocument();
    @$dom->loadHTML(mb_convert_encoding($homeHtml, 'HTML-ENTITIES', 'UTF-8'));
    $xpath = new DOMXPath($dom);
    
    // Look for nav links
    $menuLinks = $xpath->query('//nav//a | //header//a');
    foreach ($menuLinks as $link) {
        $href = trim($link->getAttribute('href'));
        $text = trim($link->textContent);
        if ($href && $text && !preg_match('/^#|^javascript:/', $href)) {
            $navItems[$href] = $text;
        }
    }
}
echo "Found " . count($navItems) . " navigation links\n";
file_put_contents("$backupDir/menus.json", json_encode($navItems, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// 2. Extract Food Items
$foodSitemap = simplexml_load_file(__DIR__ . '/../httpdocs/wp-content/uploads/rank-math/rank_math_fcaddd3f6fe0a9ac940e6c0b01aa038c.xml');
$foodItems = [];
echo "Extracting food items from sitemap (" . count($foodSitemap->url) . " URLs)...\n";
foreach ($foodSitemap->url as $urlObj) {
    $loc = (string)$urlObj->loc;
    $slug = basename(rtrim($loc, '/'));
    $images = [];
    foreach ($urlObj->children('http://www.google.com/schemas/sitemap-image/1.1')->image as $img) {
        $images[] = [
            'loc' => (string)$img->loc,
            'title' => (string)$img->title
        ];
    }
    
    $html = get_html_for_url($loc);
    $title = '';
    $desc = '';
    $price = '';
    if ($html) {
        $dom = new DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $xp = new DOMXPath($dom);
        
        $h1 = $xp->query('//h1');
        if ($h1->length > 0) $title = trim($h1->item(0)->textContent);
        
        // meta description or og:description
        $metaDesc = $xp->query('//meta[@name="description"]/@content | //meta[@property="og:description"]/@content');
        if ($metaDesc->length > 0) $desc = trim($metaDesc->item(0)->textContent);
    }
    
    $foodItems[] = [
        'slug' => $slug,
        'url' => $loc,
        'title' => $title ?: ucwords(str_replace('-', ' ', $slug)),
        'description' => $desc,
        'images' => $images,
        'raw_html' => $html ? true : false
    ];
}
echo "Extracted " . count($foodItems) . " food items\n";
file_put_contents("$backupDir/food_items.json", json_encode($foodItems, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// 3. Extract Recipes
$recipeSitemap = simplexml_load_file(__DIR__ . '/../httpdocs/wp-content/uploads/rank-math/rank_math_eb0bf39ea407de0383c24aa784e15462.xml');
$recipes = [];
echo "Extracting recipes from sitemap (" . count($recipeSitemap->url) . " URLs)...\n";
foreach ($recipeSitemap->url as $urlObj) {
    $loc = (string)$urlObj->loc;
    $slug = basename(rtrim($loc, '/'));
    $html = get_html_for_url($loc);
    $title = '';
    $content = '';
    if ($html) {
        $dom = new DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $xp = new DOMXPath($dom);
        $h1 = $xp->query('//h1');
        if ($h1->length > 0) $title = trim($h1->item(0)->textContent);
    }
    $recipes[] = [
        'slug' => $slug,
        'url' => $loc,
        'title' => $title ?: ucwords(str_replace('-', ' ', $slug)),
        'has_html' => !empty($html)
    ];
}
echo "Extracted " . count($recipes) . " recipes\n";
file_put_contents("$backupDir/recipes.json", json_encode($recipes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// 4. Extract Testimonials
$testSitemap = simplexml_load_file(__DIR__ . '/../httpdocs/wp-content/uploads/rank-math/rank_math_10e991817f9c71c8f2a3afa0e29c4fdc.xml');
$testimonials = [];
echo "Extracting testimonials (" . count($testSitemap->url) . " URLs)...\n";
foreach ($testSitemap->url as $urlObj) {
    $loc = (string)$urlObj->loc;
    $slug = basename(rtrim($loc, '/'));
    $html = get_html_for_url($loc);
    $author = '';
    $text = '';
    if ($html) {
        $dom = new DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $xp = new DOMXPath($dom);
        $h1 = $xp->query('//h1');
        if ($h1->length > 0) $author = trim($h1->item(0)->textContent);
        
        $entryContent = $xp->query('//div[contains(@class, "entry-content")] | //div[contains(@class, "content")]');
        if ($entryContent->length > 0) {
            $text = trim($entryContent->item(0)->textContent);
        }
    }
    $testimonials[] = [
        'slug' => $slug,
        'url' => $loc,
        'author' => $author ?: ucwords(str_replace('-', ' ', $slug)),
        'text' => $text
    ];
}
echo "Extracted " . count($testimonials) . " testimonials\n";
file_put_contents("$backupDir/testimonials.json", json_encode($testimonials, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// 5. Extract Buckets / Catering landing pages
$bucketSitemap = simplexml_load_file(__DIR__ . '/../httpdocs/wp-content/uploads/rank-math/rank_math_669e4aa6db94990cbb7c55eb780607f0.xml');
$buckets = [];
echo "Extracting buckets (" . count($bucketSitemap->url) . " URLs)...\n";
foreach ($bucketSitemap->url as $urlObj) {
    $loc = (string)$urlObj->loc;
    $slug = basename(rtrim($loc, '/'));
    $html = get_html_for_url($loc);
    $title = '';
    $body = '';
    if ($html) {
        $dom = new DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $xp = new DOMXPath($dom);
        $h1 = $xp->query('//h1');
        if ($h1->length > 0) $title = trim($h1->item(0)->textContent);
    }
    $buckets[] = [
        'slug' => $slug,
        'url' => $loc,
        'title' => $title ?: ucwords(str_replace('-', ' ', $slug))
    ];
}
echo "Extracted " . count($buckets) . " buckets\n";
file_put_contents("$backupDir/buckets.json", json_encode($buckets, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "\nAll custom post types extracted successfully!\n";
