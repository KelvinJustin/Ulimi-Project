<?php
declare(strict_types=1);

namespace App\Core;

final class Request
{
    private ?array $jsonBody = null;

    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function path(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH);
        return $path ?: '/';
    }

    public function query(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        // Check POST data first
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }

        // Then check JSON body
        $json = $this->getJsonBody();
        if ($json !== null && isset($json[$key])) {
            return $json[$key];
        }

        return $default;
    }

    public function all(): array
    {
        return array_merge($_POST, $this->getJsonBody() ?? []);
    }

    private function getJsonBody(): ?array
    {
        if ($this->jsonBody !== null) {
            return $this->jsonBody;
        }

        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (str_contains($contentType, 'application/json')) {
            $rawInput = file_get_contents('php://input');
            if ($rawInput) {
                $this->jsonBody = json_decode($rawInput, true);
                return $this->jsonBody;
            }
        }

        $this->jsonBody = [];
        return $this->jsonBody;
    }
}
