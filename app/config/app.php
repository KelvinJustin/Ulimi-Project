<?php
declare(strict_types=1);

use App\Core\Config;

Config::set('app', [
    'name' => 'Ulimi 3.0',
    'base_url' => getenv('APP_BASE_URL') ?: '',
    'env' => getenv('APP_ENV') ?: 'local',
]);

Config::set('db', [
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'name' => getenv('DB_NAME') ?: 'ulimi',
    'user' => getenv('DB_USER') ?: 'root',
    'pass' => getenv('DB_PASS') ?: '',
    'charset' => 'utf8mb4',
]);

Config::set('security', [
    'csrf_key' => getenv('CSRF_KEY') ?: 'change-me',
    'jwt_secret' => getenv('JWT_SECRET') ?: 'change-me',
    'cookie_secure' => (getenv('COOKIE_SECURE') ?: '0') === '1',
]);

Config::set('stripe', [
    'secret_key' => getenv('STRIPE_SECRET_KEY') ?: '',
    'webhook_secret' => getenv('STRIPE_WEBHOOK_SECRET') ?: '',
]);
