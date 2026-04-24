<?php
declare(strict_types=1);

namespace App\Core\Middleware;

use App\Core\Auth;
use App\Core\View;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

/**
 * Role Middleware
 * 
 * Ensures the authenticated user has the required role(s).
 * Shows access denied page if role requirement is not met.
 */
final class RoleMiddleware implements MiddlewareInterface
{
    private array $allowedRoles;

    public function __construct(array $allowedRoles)
    {
        $this->allowedRoles = $allowedRoles;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!Auth::check()) {
            Auth::redirectToLogin();
            exit;
        }

        $user = Auth::user();
        if (!$user || !in_array($user['role'], $this->allowedRoles)) {
            http_response_code(403);
            if (!headers_sent()) {
                View::render('auth.access-denied', [
                    'title' => 'Access Denied - Ulimi Agricultural Marketplace'
                ]);
            }
            exit;
        }

        return $handler->handle($request);
    }
}
