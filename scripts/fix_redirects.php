<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Redirect.php';

Redirect::create('/foodtruck', '/food-truck/', 301);
Redirect::create('/foodtruck/', '/food-truck/', 301);
echo "Redirects updated.\n";
