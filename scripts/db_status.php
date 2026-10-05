<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Database.php';

$db = Database::getInstance();
$tables = ['pages', 'posts', 'categories', 'tags', 'media', 'food_items', 'menus', 'menu_items', 'settings', 'forms', 'form_submissions', 'admins', 'redirects', 'seo_metadata'];

echo "=== LAHORE CATERING DATABASE INVENTORY ===\n";
foreach ($tables as $t) {
    $count = $db->query("SELECT COUNT(*) FROM $t")->fetchColumn();
    printf("%-20s: %d records\n", $t, $count);
}
echo "==========================================\n";
