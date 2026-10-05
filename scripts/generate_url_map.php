<?php
/**
 * Generate Complete URL Mapping
 */

$pages = json_decode(file_get_contents(__DIR__ . '/../data_backup/pages.json'), true);
$posts = json_decode(file_get_contents(__DIR__ . '/../data_backup/posts.json'), true);
$categories = json_decode(file_get_contents(__DIR__ . '/../data_backup/categories.json'), true);
$tags = json_decode(file_get_contents(__DIR__ . '/../data_backup/tags.json'), true);

$urlMap = [];

// Homepage
$urlMap['/'] = [
    'old_url' => 'https://lahorecatering.nl/',
    'new_url' => '/',
    'type' => 'page',
    'id' => 101,
    'slug' => '',
    'title' => 'Lahore Catering Friesland - Home'
];

// Pages
foreach ($pages as $p) {
    $slug = $p['slug'];
    if ($slug === 'home' || $slug === 'home-3') continue;
    $parsed = parse_url($p['link']);
    $path = '/' . trim($parsed['path'], '/') . '/';
    $urlMap[$path] = [
        'old_url' => $p['link'],
        'new_url' => $path,
        'type' => 'page',
        'id' => $p['id'],
        'slug' => $slug,
        'title' => html_entity_decode($p['title']['rendered'] ?? '', ENT_QUOTES, 'UTF-8')
    ];
}

// Posts (WordPress had post URLs directly at /slug/)
foreach ($posts as $p) {
    $slug = $p['slug'];
    $parsed = parse_url($p['link']);
    $path = '/' . trim($parsed['path'], '/') . '/';
    $urlMap[$path] = [
        'old_url' => $p['link'],
        'new_url' => $path,
        'type' => 'post',
        'id' => $p['id'],
        'slug' => $slug,
        'title' => html_entity_decode($p['title']['rendered'] ?? '', ENT_QUOTES, 'UTF-8')
    ];
}

// Categories
foreach ($categories as $c) {
    $slug = $c['slug'];
    $parsed = parse_url($c['link']);
    $path = '/' . trim($parsed['path'], '/') . '/';
    $urlMap[$path] = [
        'old_url' => $c['link'],
        'new_url' => $path,
        'type' => 'category',
        'id' => $c['id'],
        'slug' => $slug,
        'title' => $c['name']
    ];
}

// Redirects for aliases or common variations
$redirects = [
    '/home' => '/',
    '/home/' => '/',
    '/home-3' => '/',
    '/home-3/' => '/',
    '/food-truck' => '/foodtruck/',
    '/foodtruck' => '/foodtruck/',
    '/catering' => '/catering/',
    '/buffet' => '/buffet/',
    '/blog' => '/blog/',
    '/the-best-indian-catering-company-in-friesland' => '/catering/',
    '/the-best-indian-catering-company-in-friesland/' => '/catering/',
    '/cheap-catering-in-leeuwarden' => '/buffet/',
    '/cheap-catering-in-leeuwarden/' => '/buffet/',
    '/order-food-leeuwarden' => '/pakistaans-eten-leeuwarden/',
    '/order-food-leeuwarden/' => '/pakistaans-eten-leeuwarden/',
    '/pakistani-restaurant-leeuwarden' => '/pakistaans-restaurant-leeuwarden/',
    '/pakistani-restaurant-leeuwarden/' => '/pakistaans-restaurant-leeuwarden/',
    '/takeaway-and-delivery-stiens' => '/eten-bezorgen-stiens/',
    '/takeaway-and-delivery-stiens/' => '/eten-bezorgen-stiens/'
];

file_put_contents(__DIR__ . '/../data_backup/url_map.json', json_encode($urlMap, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
file_put_contents(__DIR__ . '/../data_backup/redirects.json', json_encode($redirects, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

echo "Generated URL Map with " . count($urlMap) . " mapped URLs and " . count($redirects) . " standard redirects.\n";
