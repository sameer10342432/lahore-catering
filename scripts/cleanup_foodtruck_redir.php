<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Database.php';

Database::query("DELETE FROM redirects WHERE source_url LIKE '%food-truck%'");
Database::query("INSERT OR REPLACE INTO redirects (source_url, target_url, status_code, created_at) VALUES ('/foodtruck', '/food-truck/', 301, datetime('now')), ('/foodtruck/', '/food-truck/', 301, datetime('now'))");
echo "Cleaned foodtruck redirects.\n";
