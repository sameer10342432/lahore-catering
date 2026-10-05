<?php
$pages = json_decode(file_get_contents(__DIR__ . '/../data_backup/pages.json'), true);
echo "=== TOTAL PAGES: " . count($pages) . " ===\n";
foreach ($pages as $p) {
    echo "- ID: {$p['id']} | Slug: {$p['slug']} | Title: " . html_entity_decode($p['title']['rendered'] ?? '', ENT_QUOTES, 'UTF-8') . " | Link: {$p['link']}\n";
}
