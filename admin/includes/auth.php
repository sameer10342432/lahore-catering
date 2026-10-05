<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../models/Database.php';
require_once __DIR__ . '/../../models/Admin.php';
require_once __DIR__ . '/../../models/FormSubmission.php';

if (!Admin::isLoggedIn()) {
    header('Location: /admin/login.php');
    exit;
}

$currentAdmin = Admin::current();
if (!$currentAdmin) {
    Admin::logout();
    header('Location: /admin/login.php');
    exit;
}
