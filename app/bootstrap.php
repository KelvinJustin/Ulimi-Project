<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/Core/Autoloader.php';

App\Core\Autoloader::register();

require_once __DIR__ . '/config/app.php';
