<?php
/**
 * Application Configuration
 * Lahore Catering - Custom PHP Platform
 */

// Error reporting (set to 0 in production)
ini_set('display_errors', '0');
error_reporting(E_ALL);

// Base URLs and Paths
define('APP_NAME', 'Lahore Catering Friesland');
define('APP_ENV', getenv('APP_ENV') ?: 'production');
define('APP_DEBUG', getenv('APP_DEBUG') === 'true');

// Determine base URL dynamically or use canonical
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? 80) == 443 ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
define('SITE_URL', rtrim($protocol . $host, '/'));
define('CANONICAL_DOMAIN', 'https://lahorecatering.nl');

// Root Paths
define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('CONTROLLER_PATH', ROOT_PATH . '/controllers');
define('MODEL_PATH', ROOT_PATH . '/models');
define('VIEW_PATH', ROOT_PATH . '/views');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('UPLOAD_PATH', ROOT_PATH . '/uploads');

// Contact Information (Preserved from WordPress)
define('CONTACT_PHONE', '06 331 667 30');
define('CONTACT_PHONE_RAW', '+31633166730');
define('CONTACT_EMAIL', 'info@lahorecatering.nl');
define('CONTACT_ADDRESS', 'Smelbrege 4, 9051 BH Stiens, Friesland');
define('ORDER_SIDES_URL', 'https://lahore-catering-nl.sides-shop.com/articles');
define('GLORIA_FOOD_RUID', '19cfad1c-0b7d-483a-aebc-f347a34a3a13');
define('FACEBOOK_URL', 'https://www.facebook.com/LahoreCateringVanFriesland/');

// Security & Session
define('SESSION_LIFETIME', 86400); // 24 hours
define('CSRF_TOKEN_KEY', '_lc_csrf_token');

// Start secure session if not started
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', '1');
    }
    session_start();
}

// CSRF Helpers
function generate_csrf_token() {
    if (empty($_SESSION[CSRF_TOKEN_KEY])) {
        $_SESSION[CSRF_TOKEN_KEY] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_KEY];
}

function verify_csrf_token($token) {
    return isset($_SESSION[CSRF_TOKEN_KEY]) && hash_equals($_SESSION[CSRF_TOKEN_KEY], $token);
}

// Sanitize & Escape helpers
function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}
