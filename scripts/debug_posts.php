<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Database.php';

$res = Database::fetchAll("SELECT id, title, slug, status FROM posts WHERE slug LIKE '%pakistaanse%' LIMIT 5");
echo json_encode($res, JSON_PRETTY_PRINT) . "\n";
