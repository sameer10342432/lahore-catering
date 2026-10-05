<?php
$posts = json_decode(file_get_contents(__DIR__ . '/../data_backup/posts.json'), true);
echo "=== TOTAL POSTS: " . count($posts) . " ===\n";
echo "Showing first 10 posts:\n";
for ($i = 0; $i < min(10, count($posts)); $i++) {
    $p = $posts[$i];
    echo "- ID: {$p['id']} | Slug: {$p['slug']} | Date: {$p['date']} | Title: " . html_entity_decode($p['title']['rendered'] ?? '', ENT_QUOTES, 'UTF-8') . "\n";
}
echo "Showing last 5 posts:\n";
for ($i = max(0, count($posts) - 5); $i < count($posts); $i++) {
    $p = $posts[$i];
    echo "- ID: {$p['id']} | Slug: {$p['slug']} | Date: {$p['date']} | Title: " . html_entity_decode($p['title']['rendered'] ?? '', ENT_QUOTES, 'UTF-8') . "\n";
}
