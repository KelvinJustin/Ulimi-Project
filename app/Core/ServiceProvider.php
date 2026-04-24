<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Service Provider
 * 
 * Registers core services with the container.
 */
final class ServiceProvider
{
    public function __construct(private Container $container)
    {
    }

    /**
     * Register all services
     */
    public function register(): void
    {
        $this->registerCoreServices();
        $this->registerMiddleware();
        $this->registerControllers();
    }

    /**
     * Register core singleton services
     */
    private function registerCoreServices(): void
    {
        // Database - singleton
        $this->container->singleton(Database::class, function () {
            return Database::pdo();
        });

        // Config - singleton (already static, but registered for consistency)
        $this->container->singleton(Config::class, function () {
            return new Config();
        });

        // Auth - singleton
        $this->container->singleton(Auth::class, function () {
            return new Auth();
        });
    }

    /**
     * Register middleware (transient - new instance per request)
     */
    private function registerMiddleware(): void
    {
        $this->container->bind(\App\Core\Middleware\AuthenticationMiddleware::class);
        $this->container->bind(\App\Core\Middleware\CsrfMiddleware::class);
        $this->container->bind(\App\Core\Middleware\RateLimitMiddleware::class);
        $this->container->bind(\App\Core\Middleware\HttpsMiddleware::class);
    }

    /**
     * Register controllers (transient - new instance per request)
     */
    private function registerControllers(): void
    {
        $this->container->bind(\App\Controllers\AuthController::class);
        $this->container->bind(\App\Controllers\DashboardController::class);
        $this->container->bind(\App\Controllers\ProductController::class);
        $this->container->bind(\App\Controllers\BrowseController::class);
        $this->container->bind(\App\Controllers\ApiController::class);
        $this->container->bind(\App\Controllers\CartController::class);
        $this->container->bind(\App\Controllers\MessagesController::class);
        $this->container->bind(\App\Controllers\MessageController::class);
        $this->container->bind(\App\Controllers\HomeController::class);
        $this->container->bind(\App\Controllers\AffiliateController::class);
    }
}
