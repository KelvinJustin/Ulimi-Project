<?php
declare(strict_types=1);

namespace App\Core\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

/**
 * HTTPS Middleware
 * 
 * Enforces HTTPS for all requests in production environments.
 * Redirects HTTP requests to HTTPS with a 301 permanent redirect.
 * Allows HTTP in local development when APP_ENV is not 'production'.
 */
final class HttpsMiddleware implements MiddlewareInterface
{
    private bool $shouldExit = true;

    /**
     * Set whether to exit after redirect (useful for testing)
     */
    public function setShouldExit(bool $shouldExit): void
    {
        $this->shouldExit = $shouldExit;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Skip HTTPS check for CLI requests
        if (php_sapi_name() === 'cli') {
            return $handler->handle($request);
        }

        // Get environment and force_https config
        $env = \App\Core\Config::get('app.env', 'local');
        $forceHttps = \App\Core\Config::get('security.force_https', false);

        // Allow HTTP in local development unless force_https is explicitly set
        if ($env !== 'production' && !$forceHttps) {
            return $handler->handle($request);
        }

        // Check if request is already using HTTPS
        $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        
        // Also check for reverse proxy headers (e.g., behind load balancer)
        if (!$isHttps && isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            $isHttps = strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https';
        }

        // If not HTTPS, redirect to HTTPS
        if (!$isHttps) {
            $httpsUrl = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header('Location: ' . $httpsUrl, true, 301);
            if ($this->shouldExit) {
                exit;
            }
            // Return a simple redirect response for PSR-15 compliance
            $redirectUrl = $httpsUrl;
            return new class($redirectUrl) implements ResponseInterface {
                private string $location;

                public function __construct(string $location)
                {
                    $this->location = $location;
                }

                public function getStatusCode(): int { return 301; }
                public function withStatus($code, $reasonPhrase = ''): self { return $this; }
                public function getReasonPhrase(): string { return 'Moved Permanently'; }
                public function getProtocolVersion(): string { return '1.1'; }
                public function withProtocolVersion($version): self { return $this; }
                public function getHeaders(): array { return ['Location' => [$this->location]]; }
                public function hasHeader($name): bool { return strtolower($name) === 'location'; }
                public function getHeader($name): array { return strtolower($name) === 'location' ? [$this->location] : []; }
                public function getHeaderLine($name): string { return strtolower($name) === 'location' ? $this->location : ''; }
                public function withHeader($name, $value): self { return $this; }
                public function withAddedHeader($name, $value): self { return $this; }
                public function withoutHeader($name): self { return $this; }
                public function getBody(): \Psr\Http\Message\StreamInterface {
                    return new class implements \Psr\Http\Message\StreamInterface {
                        public function __toString(): string { return ''; }
                        public function close(): void {}
                        public function detach() { return null; }
                        public function getSize(): ?int { return 0; }
                        public function tell(): int { return 0; }
                        public function isSeekable(): bool { return false; }
                        public function seek($offset, $whence = SEEK_SET): void {}
                        public function rewind(): void {}
                        public function isWritable(): bool { return false; }
                        public function write($string): int { return 0; }
                        public function isReadable(): bool { return false; }
                        public function read($length): string { return ''; }
                        public function getContents(): string { return ''; }
                        public function getMetadata($key = null) { return $key === null ? [] : null; }
                    };
                }
                public function withBody($body): self { return $this; }
            };
        }

        return $handler->handle($request);
    }
}
