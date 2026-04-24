<?php
declare(strict_types=1);

namespace App\Core\Middleware;

use App\Core\Auth;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

/**
 * Authentication Middleware
 * 
 * Ensures the user is authenticated before allowing access to the route.
 * Redirects to login if not authenticated for web routes.
 * Returns JSON 401 error for API routes.
 */
final class AuthenticationMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!Auth::check()) {
            // Check if this is an API route
            $uri = $request->getUri()->getPath();
            $isApiRoute = strpos($uri, '/api/') === 0;

            if ($isApiRoute) {
                // Return JSON 401 for API routes
                header('Content-Type: application/json');
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit;
            } else {
                // Redirect to login for web routes
                Auth::redirectToLogin();
                exit;
            }
        }

        return $handler->handle($request);
    }
}
