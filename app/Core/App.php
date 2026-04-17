<?php
declare(strict_types=1);

namespace App\Core;

use App\Routes\WebRoutes;

final class App
{
    public function run(): void
    {
        $router = new Router();
        (new WebRoutes())->register($router);

        $router->dispatch(new Request());
    }
}
