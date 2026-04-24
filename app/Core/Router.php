<?php
declare(strict_types=1);

namespace App\Core;

use App\Core\Middleware\Pipeline;

final class Router
{
    private array $routes = [];
    private Container $container;
    private array $middlewareGroups = [];
    private array $globalMiddleware = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->defineMiddlewareGroups();
        
        // Add HTTPS middleware to global middleware (runs first on all routes)
        $this->globalMiddleware = [
            $this->container->get(\App\Core\Middleware\HttpsMiddleware::class)
        ];
    }

    public function get(string $path, callable $handler): RouteBuilder
    {
        return $this->map('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): RouteBuilder
    {
        return $this->map('POST', $path, $handler);
    }

    public function map(string $method, string $path, callable $handler): RouteBuilder
    {
        $route = new Route($method, $path, $handler);
        $this->routes[$method][$path] = $route;
        return new RouteBuilder($route, $this);
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $path = rtrim($request->path(), '/') ?: '/';

        $route = $this->routes[$method][$path] ?? null;
        if ($route !== null) {
            $this->executeRoute($route, $request);
            return;
        }

        foreach (($this->routes[$method] ?? []) as $routePath => $route) {
            $pattern = preg_replace('#\\{[a-zA-Z_][a-zA-Z0-9_]*\\}#', '([^/]+)', $routePath);
            $pattern = '#^' . rtrim($pattern, '/') . '$#';

            if (!preg_match($pattern, $path, $matches)) {
                continue;
            }

            array_shift($matches);
            $params = $this->extractParams($routePath, $matches);
            $route->setParams($params);
            $this->executeRoute($route, $request);
            return;
        }

        http_response_code(404);
        echo '404 Not Found';
    }

    private function executeRoute(Route $route, Request $request): void
    {
        $middleware = array_merge(
            $this->globalMiddleware,
            $this->resolveMiddlewareGroups($route->getMiddleware())
        );

        $handler = function ($req) use ($route) {
            $route->getHandler()($req, $route->getParams());
        };

        if (empty($middleware)) {
            $handler($request);
            return;
        }

        $pipeline = new Pipeline($middleware, $handler);
        $pipeline->run($request);
    }

    private function resolveMiddlewareGroups(array $middleware): array
    {
        $resolved = [];
        foreach ($middleware as $name) {
            if (isset($this->middlewareGroups[$name])) {
                $resolved = array_merge($resolved, $this->middlewareGroups[$name]);
            } else {
                $resolved[] = $this->container->get($name);
            }
        }
        return $resolved;
    }

    private function extractParams(string $routePath, array $values): array
    {
        preg_match_all('#\\{([a-zA-Z_][a-zA-Z0-9_]*)\\}#', $routePath, $keys);
        $keys = $keys[1] ?? [];

        $params = [];
        foreach ($keys as $i => $key) {
            $params[$key] = $values[$i] ?? null;
        }

        return $params;
    }

    private function defineMiddlewareGroups(): void
    {
        $rateLimitConfig = \App\Core\Config::get('rate_limit', []);

        $this->middlewareGroups = [
            'auth' => [
                $this->container->get(\App\Core\Middleware\AuthenticationMiddleware::class),
                $this->container->get(\App\Core\Middleware\CsrfMiddleware::class),
            ],
            'admin' => [
                $this->container->get(\App\Core\Middleware\AuthenticationMiddleware::class),
                new \App\Core\Middleware\RoleMiddleware(['admin']),
            ],
            'seller' => [
                $this->container->get(\App\Core\Middleware\AuthenticationMiddleware::class),
                new \App\Core\Middleware\RoleMiddleware(['seller']),
            ],
            'buyer' => [
                $this->container->get(\App\Core\Middleware\AuthenticationMiddleware::class),
                new \App\Core\Middleware\RoleMiddleware(['buyer']),
            ],
            // Rate limiting groups
            'throttle-auth' => [
                new \App\Core\Middleware\RateLimitMiddleware(
                    $rateLimitConfig['auth']['max_requests'] ?? 5,
                    $rateLimitConfig['auth']['window_seconds'] ?? 60,
                    $rateLimitConfig['auth']['limit_type'] ?? 'ip'
                ),
            ],
            'throttle-api' => [
                new \App\Core\Middleware\RateLimitMiddleware(
                    $rateLimitConfig['api']['max_requests'] ?? 100,
                    $rateLimitConfig['api']['window_seconds'] ?? 60,
                    $rateLimitConfig['api']['limit_type'] ?? 'user'
                ),
            ],
            'throttle-api-guest' => [
                new \App\Core\Middleware\RateLimitMiddleware(
                    $rateLimitConfig['api_guest']['max_requests'] ?? 30,
                    $rateLimitConfig['api_guest']['window_seconds'] ?? 60,
                    $rateLimitConfig['api_guest']['limit_type'] ?? 'ip'
                ),
            ],
            'throttle-upload' => [
                new \App\Core\Middleware\RateLimitMiddleware(
                    $rateLimitConfig['upload']['max_requests'] ?? 10,
                    $rateLimitConfig['upload']['window_seconds'] ?? 60,
                    $rateLimitConfig['upload']['limit_type'] ?? 'user'
                ),
            ],
            'throttle-general' => [
                new \App\Core\Middleware\RateLimitMiddleware(
                    $rateLimitConfig['general']['max_requests'] ?? 60,
                    $rateLimitConfig['general']['window_seconds'] ?? 60,
                    $rateLimitConfig['general']['limit_type'] ?? 'user'
                ),
            ],
        ];
    }

    /**
     * Get the container instance
     */
    public function getContainer(): Container
    {
        return $this->container;
    }
}

/**
 * Route class to store route information
 */
class Route
{
    private string $method;
    private string $path;
    private $handler;
    private array $middleware = [];
    private array $params = [];

    public function __construct(string $method, string $path, callable $handler)
    {
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getHandler()
    {
        return $this->handler;
    }

    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    public function setMiddleware(array $middleware): void
    {
        $this->middleware = $middleware;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }
}

/**
 * Route Builder for fluent middleware registration
 */
class RouteBuilder
{
    private Route $route;
    private Router $router;

    public function __construct(Route $route, Router $router)
    {
        $this->route = $route;
        $this->router = $router;
    }

    public function middleware(string ...$middleware): self
    {
        $this->route->setMiddleware($middleware);
        return $this;
    }
}

