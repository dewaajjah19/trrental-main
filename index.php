<?php

// =====================================================
// BASE PATH
// Lokasi root project.
// =====================================================
define('BASE_PATH', __DIR__);


// =====================================================
// BASE URL DINAMIS
// Local XAMPP  : http://localhost/trrental-main
// Vercel      : https://trrental-main.vercel.app
// =====================================================
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

$isLocalhost = str_contains($host, 'localhost') || str_contains($host, '127.0.0.1');

$protocol = 'http://';

if (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
    (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
) {
    $protocol = 'https://';
}

if ($isLocalhost) {
    define('BASE_URL', $protocol . $host . '/trrental-main');
} else {
    define('BASE_URL', $protocol . $host);
}


// =====================================================
// REQUIRE CORE FILES
// =====================================================
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/core/App.php';
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/core/Model.php';


// =====================================================
// RUN APPLICATION
// =====================================================
$app = new App();
