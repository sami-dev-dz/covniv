<?php
// public/index.php

// Define base path
define('BASE_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

// Simple PSR-4 Autoloader
spl_autoload_register(function ($class) {
    // Prefix for the namespace
    $prefix = 'App\\';
    // Base directory for the namespace
    $base_dir = BASE_PATH . 'app/';

    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace namespace separators with directory separators, add .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require_once $file;
    }
});

// Include configuration and database connection
require_once BASE_PATH . 'config/database.php';

// Very simple router logic for the MVC structure

// Security Headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
// A basic CSP that allows standard assets (adjust if external CDN are used like lucide unpkg)
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://unpkg.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' data: https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self'; frame-ancestors 'none';");

// Global CORS for API routes (Middleware will refine this)
if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// No longer needed with autoloader
// require_once BASE_PATH . 'app/Services/AuthSecurity.php';

// Check for remember me token
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $userId = \App\Services\AuthSecurity::validateRememberToken($_COOKIE['remember_token']);
    if ($userId) {
        $_SESSION['user_id'] = $userId;
        $conn = \Database::getConnection();
        $stmt = $conn->prepare("SELECT prenom, num_carte FROM infos WHERE user_info_id = :id");
        $stmt->execute(['id' => $userId]);
        $info = $stmt->fetch();
        if ($info) {
            $_SESSION['prenom'] = $info['prenom'];
            $_SESSION['num_carte'] = $info['num_carte'];
            session_regenerate_id(true);
        }
    } else {
        setcookie('remember_token', '', time() - 3600, '/', '', true, true);
    }
}

// Get the requested URI (relative to public/)
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];

// Extract the path from the URI
$requestPath = parse_url($requestUri, PHP_URL_PATH);

// Error handling based on environment
$appDebug = getenv('APP_DEBUG') === 'true';
if ($appDebug) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// Ensure logs directory exists
if (!file_exists(BASE_PATH . 'logs')) {
    mkdir(BASE_PATH . 'logs', 0755, true);
}
ini_set('log_errors', 1);
ini_set('error_log', BASE_PATH . 'logs/error.log');

// If the project is in a subfolder (like /uniride/), remove that base path from the request
$baseDir = str_replace('\\', '/', dirname($scriptName));

if (strpos($requestPath, $baseDir) === 0) {
    if ($baseDir !== '/') {
        $requestPath = substr($requestPath, strlen($baseDir));
    }
}

// Clean up the path
$requestPath = trim($requestPath, '/');

// Include routes file
require_once BASE_PATH . 'routes/web.php';

// Dispatch
Route::dispatch($requestPath);
