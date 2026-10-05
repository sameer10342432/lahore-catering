<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Admin.php';

Admin::logout();
header('Location: /admin/login.php');
exit;
