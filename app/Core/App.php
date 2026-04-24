<?php
declare(strict_types=1);

namespace App\Core;

use App\Routes\WebRoutes;

final class App
{
    private static ?Container $container = null;

    public function run(): void
    {
        $container = $this->getContainer();
        $router = new Router($container);
        (new WebRoutes())->register($router);

        $router->dispatch(new Request());
    }

    /**
     * Get or create the container instance
     */
    public function getContainer(): Container
    {
        if (self::$container === null) {
            self::$container = new Container();
            (new ServiceProvider(self::$container))->register();
        }

        return self::$container;
    }

    /**
     * Set container instance (useful for testing)
     */
    public static function setContainer(Container $container): void
    {
        self::$container = $container;
    }
}
