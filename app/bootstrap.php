<?php
declare(strict_types=1);

// Skip session start in test environment to avoid conflicts
if ((getenv('APP_ENV') ?: 'local') !== 'testing') {
    session_start();
}

require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Skip loading app config in test environment (tests will set their own config)
if ((getenv('APP_ENV') ?: 'local') !== 'testing') {
    require_once __DIR__ . '/config/app.php';
}
