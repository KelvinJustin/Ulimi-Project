<?php
declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->map('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->map('POST', $path, $handler);
    }

    public function map(string $method, string $path, callable $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $path = rtrim($request->path(), '/') ?: '/';

        $handler = $this->routes[$method][$path] ?? null;
        if ($handler !== null) {
            $handler($request);
            return;
        }

        foreach (($this->routes[$method] ?? []) as $routePath => $routeHandler) {
            $pattern = preg_replace('#\\{[a-zA-Z_][a-zA-Z0-9_]*\\}#', '([^/]+)', $routePath);
            $pattern = '#^' . rtrim($pattern, '/') . '$#';

            if (!preg_match($pattern, $path, $matches)) {
                continue;
            }

            array_shift($matches);
            $params = $this->extractParams($routePath, $matches);
            $routeHandler($request, $params);
            return;
        }

        http_response_code(404);
        echo '404 Not Found';
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
}
