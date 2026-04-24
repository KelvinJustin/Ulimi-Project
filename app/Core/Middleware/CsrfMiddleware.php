<?php
declare(strict_types=1);

namespace App\Core\Middleware;

use App\Core\Csrf;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

/**
 * CSRF Middleware
 * 
 * Validates CSRF tokens for POST requests to prevent cross-site request forgery.
 */
final class CsrfMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Only validate POST requests
        if ($request->getMethod() !== 'POST') {
            return $handler->handle($request);
        }

        // Skip CSRF for API requests that use auth middleware (session-based auth is sufficient)
        $uri = $request->getUri()->getPath();
        if (str_starts_with($uri, '/api/')) {
            return $handler->handle($request);
        }

        // Get CSRF token from request body or header
        $token = $request->getParsedBody()['_csrf'] ?? null;
        if ($token === null) {
            $token = $request->getHeaderLine('X-CSRF-Token');
        }

        if (!Csrf::verify($token)) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            exit;
        }

        return $handler->handle($request);
    }
}
