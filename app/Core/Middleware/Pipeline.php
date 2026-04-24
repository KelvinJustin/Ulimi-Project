<?php
declare(strict_types=1);

namespace App\Core\Middleware;

use App\Core\Request;

/**
 * Middleware Pipeline
 * 
 * Executes middleware in sequence, passing the request through each one.
 * Simplified implementation that works with the existing Request class.
 */
final class Pipeline
{
    private array $middleware = [];
    private mixed $handler;

    public function __construct(array $middleware, mixed $handler)
    {
        $this->middleware = $middleware;
        $this->handler = $handler;
    }

    /**
     * Execute the middleware pipeline
     *
     * @param Request $request The request to process
     * @return void
     */
    public function run(Request $request): void
    {
        $this->executeMiddleware($request, 0);
    }

    /**
     * Execute middleware recursively
     *
     * @param Request $request The request to process
     * @param int $index Current middleware index
     * @return void
     */
    private function executeMiddleware(Request $request, int $index): void
    {
        // If all middleware have been processed, execute the handler
        if ($index >= count($this->middleware)) {
            call_user_func($this->handler, $request);
            return;
        }

        $middleware = $this->middleware[$index];
        $next = function ($request) use ($index) {
            // If the request is a PSR-7 wrapper, extract the original Request
            if ($request instanceof \Psr\Http\Message\ServerRequestInterface && !$request instanceof Request) {
                // Try to get the original request from the wrapper
                $reflection = new \ReflectionClass($request);
                if ($reflection->hasProperty('request')) {
                    $property = $reflection->getProperty('request');
                    $property->setAccessible(true);
                    $request = $property->getValue($request);
                }
            }
            $this->executeMiddleware($request, $index + 1);
        };

        // Check if middleware is PSR-15 compliant
        if ($middleware instanceof \Psr\Http\Server\MiddlewareInterface) {
            // Create a simple request handler for the middleware
            $handler = new class($next) implements \Psr\Http\Server\RequestHandlerInterface {
                private $next;

                public function __construct(callable $next)
                {
                    $this->next = $next;
                }

                public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
                {
                    call_user_func($this->next, $request);
                    // Return a simple response since our app doesn't use PSR-7 responses
                    return new class implements \Psr\Http\Message\ResponseInterface {
                        public function getStatusCode(): int { return 200; }
                        public function withStatus($code, $reasonPhrase = ''): self { return $this; }
                        public function getReasonPhrase(): string { return ''; }
                        public function getProtocolVersion(): string { return '1.1'; }
                        public function withProtocolVersion($version): self { return $this; }
                        public function getHeaders(): array { return []; }
                        public function hasHeader($name): bool { return false; }
                        public function getHeader($name): array { return []; }
                        public function getHeaderLine($name): string { return ''; }
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
            };

            // Wrap our Request in a PSR-7 compatible interface
            $psrRequest = new class($request) implements \Psr\Http\Message\ServerRequestInterface {
                private Request $request;

                public function __construct(Request $request)
                {
                    $this->request = $request;
                }

                public function getMethod(): string
                {
                    return $this->request->method();
                }

                public function getParsedBody(): mixed
                {
                    return $this->request->all();
                }

                public function withParsedBody($data): self { return $this; }
                public function withMethod($method): self { return $this; }

                // Required PSR-7 methods (simplified implementation)
                public function getProtocolVersion(): string { return '1.1'; }
                public function withProtocolVersion($version): self { return $this; }
                public function getHeaders(): array { return []; }
                public function hasHeader($name): bool { return false; }
                public function getHeader($name): array { return []; }
                public function getHeaderLine($name): string { return ''; }
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
                public function getRequestTarget(): string { return $this->request->path(); }
                public function withRequestTarget($requestTarget): self { return $this; }
                public function getUri(): \Psr\Http\Message\UriInterface { 
                    return new class implements \Psr\Http\Message\UriInterface {
                        public function getScheme(): string { return 'http'; }
                        public function withScheme($scheme): self { return $this; }
                        public function getAuthority(): string { return ''; }
                        public function getUserInfo(): string { return ''; }
                        public function withUserInfo($user, $password = null): self { return $this; }
                        public function getHost(): string { return 'localhost'; }
                        public function withHost($host): self { return $this; }
                        public function getPort(): ?int { return null; }
                        public function withPort($port): self { return $this; }
                        public function getPath(): string { return '/'; }
                        public function withPath($path): self { return $this; }
                        public function getQuery(): string { return ''; }
                        public function withQuery($query): self { return $this; }
                        public function getFragment(): string { return ''; }
                        public function withFragment($fragment): self { return $this; }
                        public function __toString(): string { return 'http://localhost/'; }
                    };
                }
                public function withUri($uri, $preserveHost = false): self { return $this; }
                public function getServerParams(): array { return $_SERVER; }
                public function getCookieParams(): array { return $_COOKIE; }
                public function withCookieParams(array $cookies): self { return $this; }
                public function getQueryParams(): array { return $_GET; }
                public function withQueryParams(array $query): self { return $this; }
                public function getUploadedFiles(): array { return $_FILES; }
                public function withUploadedFiles(array $uploadedFiles): self { return $this; }
                public function getAttributes(): array { return []; }
                public function getAttribute($name, $default = null): mixed { return $default; }
                public function withAttribute($name, $value): self { return $this; }
                public function withoutAttribute($name): self { return $this; }
            };

            $middleware->process($psrRequest, $handler);
        } else {
            // Simple middleware with handle(Request, callable) signature
            $middleware->handle($request, $next);
        }
    }
}
