<?php
declare(strict_types=1);

// Enable error display to show actual PHP errors instead of HTTP 500
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\App;

(new App())->run();
