<?php
/**
 * Router script for PHP Built-in Development Server
 * Usage: php -S localhost:8000 router.php
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 1. Transparent wp-content/uploads/ fallback
if (str_starts_with($uri, '/wp-content/uploads/')) {
    $subPath = substr($uri, strlen('/wp-content/uploads/'));
    $file = __DIR__ . '/uploads/' . $subPath;
    if (file_exists($file) && !is_dir($file)) {
        $mime = mime_content_type($file);
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
}

// 2. Direct static files
$fullPath = __DIR__ . $uri;
if ($uri !== '/' && file_exists($fullPath) && !is_dir($fullPath)) {
    return false; // Serve directly
}

// 3. Admin routes with direct file execution
if (str_starts_with($uri, '/admin')) {
    $adminFile = __DIR__ . $uri;
    if (is_dir($adminFile)) {
        $adminFile = rtrim($adminFile, '/') . '/index.php';
    } elseif (!str_ends_with($adminFile, '.php')) {
        if (file_exists($adminFile . '.php')) {
            $adminFile .= '.php';
        }
    }
    if (file_exists($adminFile)) {
        require $adminFile;
        exit;
    }
}

// 4. Default Front Controller
require __DIR__ . '/index.php';
