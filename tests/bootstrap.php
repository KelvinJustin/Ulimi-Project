<?php
declare(strict_types=1);

// Set test environment BEFORE loading bootstrap
$_ENV['APP_ENV'] = 'testing';
$_ENV['FORCE_HTTPS'] = '1';
putenv('APP_ENV=testing');

// Load the application bootstrap (will skip app/config/app.php due to APP_ENV=testing)
require_once __DIR__ . '/../app/bootstrap.php';

// Override config for testing
// Config::set() requires array as second argument
\App\Core\Config::set('app', [
    'name' => 'Ulimi 3.0',
    'base_url' => getenv('APP_BASE_URL') ?: '',
    'env' => 'testing',
]);

\App\Core\Config::set('security', [
    'csrf_key' => 'test-csrf-key',
    'jwt_secret' => 'test-jwt-secret',
    'cookie_secure' => false,
    'force_https' => true,
]);

\App\Core\Config::set('rate_limit', [
    'auth' => [
        'max_requests' => 5,
        'window_seconds' => 60,
        'limit_type' => 'ip',
    ],
    'api' => [
        'max_requests' => 100,
        'window_seconds' => 60,
        'limit_type' => 'user',
    ],
    'api_guest' => [
        'max_requests' => 30,
        'window_seconds' => 60,
        'limit_type' => 'ip',
    ],
    'upload' => [
        'max_requests' => 10,
        'window_seconds' => 60,
        'limit_type' => 'user',
    ],
    'general' => [
        'max_requests' => 60,
        'window_seconds' => 60,
        'limit_type' => 'user',
    ],
]);

\App\Core\Config::set('database', [
    'host' => 'localhost',
    'name' => 'ulimi_test',
    'user' => 'root',
    'pass' => '',
    'charset' => 'utf8mb4',
]);

// Start session for tests that need it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
