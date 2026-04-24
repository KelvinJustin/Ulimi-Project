# Middleware System Documentation

## Overview

The Ulimi application now implements a PSR-15 compliant middleware system to centralize cross-cutting concerns such as authentication, authorization, and CSRF protection. This system replaces manual `Auth::check()` and `Auth::require*()` calls scattered throughout controllers.

## Architecture

### Core Components

#### 1. MiddlewareInterface (`app/Core/Middleware/MiddlewareInterface.php`)

PSR-15 compliant interface defining the contract for all middleware:

```php
public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;
```

#### 2. AbstractMiddleware (`app/Core/Middleware/AbstractMiddleware.php`)

Base class implementing the PSR-15 interface with a simplified `handle()` method for easier middleware development:

```php
abstract public function handle(Request $request): ?ResponseInterface;
```

#### 3. Pipeline (`app/Core/Middleware/Pipeline.php`)

Executes middleware chains in the correct order, wrapping the legacy `Request` class in a PSR-7 compatible proxy for middleware compatibility.

#### 4. Router Enhancements (`app/Core/Router.php`)

The Router now supports:
- Route-level middleware assignment via fluent interface
- Middleware groups for common protection patterns
- Global middleware applied to all routes
- Automatic pipeline execution before route handlers

## Available Middleware

### AuthenticationMiddleware

Validates that the user is logged in. Redirects to `/login` if not authenticated.

**Usage:** Applied via `auth` middleware group

```php
$router->get('/dashboard', fn() => $controller->index())->middleware('auth');
```

### RoleMiddleware

Validates that the user has the required role(s). Renders an access denied page if unauthorized.

**Usage:** Applied via `admin` or `seller` middleware groups

```php
$router->get('/admin/dashboard', fn() => $controller->adminIndex())->middleware('admin');
```

### CsrfMiddleware

Validates CSRF tokens on POST requests. Returns 419 status if token is invalid or missing.

**Usage:** Applied via `auth`, `admin`, and `seller` middleware groups

```php
$router->post('/create-listing', fn($req) => $controller->createListing($req))->middleware('seller');
```

### RateLimitMiddleware

Protects against brute force attacks and API abuse by limiting request frequency. Supports both IP-based and user-based rate limiting.

**Usage:** Applied via throttle middleware groups

```php
$router->post('/login', fn($req) => $controller->login($req))->middleware('throttle-auth');
```

**Rate Limit Headers:**
- `X-RateLimit-Limit`: Maximum requests allowed in the time window
- `X-RateLimit-Remaining`: Remaining requests in the current window
- `X-RateLimit-Reset`: Unix timestamp when the rate limit resets
- `Retry-After`: Seconds until the rate limit resets (sent when limit exceeded)

**Response:** Returns 429 Too Many Requests with JSON error when limit exceeded.

## Middleware Groups

Predefined middleware groups for common protection patterns:

### `auth` Group
- AuthenticationMiddleware
- CsrfMiddleware

**Use for:** Routes requiring any authenticated user

### `admin` Group
- AuthenticationMiddleware
- RoleMiddleware(['admin'])

**Use for:** Admin-only routes

### `seller` Group
- AuthenticationMiddleware
- RoleMiddleware(['seller'])
- CsrfMiddleware

**Use for:** Seller-only routes

### `throttle-auth` Group
- RateLimitMiddleware (5 requests/minute, IP-based)

**Use for:** Authentication endpoints (login, register) to prevent brute force attacks

### `throttle-api` Group
- RateLimitMiddleware (100 requests/minute, user-based)

**Use for:** Authenticated API endpoints

### `throttle-api-guest` Group
- RateLimitMiddleware (30 requests/minute, IP-based)

**Use for:** Public API endpoints

### `throttle-upload` Group
- RateLimitMiddleware (10 requests/minute, user-based)

**Use for:** File upload endpoints to prevent abuse

### `throttle-general` Group
- RateLimitMiddleware (60 requests/minute, user-based)

**Use for:** General POST operations (profile updates, listing management)

## Usage Examples

### Applying Middleware to Routes

```php
// Single middleware group
$router->get('/dashboard', fn() => $controller->index())->middleware('auth');

// Admin-protected route
$router->get('/admin/dashboard', fn() => $controller->adminIndex())->middleware('admin');

// Seller-protected route
$router->get('/create-listing', fn() => $controller->showCreateListing())->middleware('seller');

// POST route with CSRF protection
$router->post('/create-listing', fn($req) => $controller->createListing($req))->middleware('seller');

// Rate-limited authentication endpoint
$router->post('/login', fn($req) => $controller->login($req))->middleware('throttle-auth');

// Rate-limited API endpoint
$router->get('/api/products', fn($req) => $controller->products($req))->middleware('throttle-api-guest');

// Multiple middleware groups
$router->post('/api/cart/add', fn($req) => $controller->addItem($req))->middleware('auth', 'throttle-api');
```

### Creating Custom Middleware

1. Implement `MiddlewareInterface` or extend `AbstractMiddleware`

```php
<?php
namespace App\Core\Middleware;

use App\Core\Request;
use App\Core\AbstractMiddleware;

final class CustomMiddleware extends AbstractMiddleware
{
    public function handle(Request $request): ?ResponseInterface
    {
        // Your custom logic here
        
        // Return null to continue to next middleware
        // Return a ResponseInterface to short-circuit the pipeline
        return null;
    }
}
```

2. Register in ServiceProvider (`app/Core/ServiceProvider.php`)

```php
private function registerMiddleware(): void
{
    $this->container->bind(\App\Core\Middleware\CustomMiddleware::class, fn() => 
        new \App\Core\Middleware\CustomMiddleware()
    );
}
```

3. Add to a middleware group in Router (`app/Core/Router.php`)

```php
private function defineMiddlewareGroups(): void
{
    $this->middlewareGroups = [
        'custom' => [
            $this->container->get(\App\Core\Middleware\CustomMiddleware::class),
        ],
        // ... other groups
    ];
}
```

## Migration from Manual Auth Checks

### Before (Manual Checks)

```php
public function createListing(Request $request): void
{
    Auth::requireSeller();
    
    if (!Csrf::verify($request->input('_csrf'))) {
        http_response_code(419);
        echo 'Invalid CSRF token';
        return;
    }
    
    // Controller logic...
}
```

### After (Middleware)

```php
public function createListing(Request $request): void
{
    // No manual checks needed - handled by middleware
    
    // Controller logic...
}
```

Route definition:
```php
$router->post('/create-listing', fn($req) => $controller->createListing($req))->middleware('seller');
```

## Controller Changes

All manual authentication and authorization checks have been removed from controllers:

- **ProductController**: Removed `Auth::requireSeller()`, `Auth::requireAdmin()`, and CSRF checks
- **DashboardController**: Removed `Auth::check()`, `Auth::isAdmin()`, and CSRF checks
- **ApiController**: Removed `Auth::check()` and role checks
- **CartController**: Removed `Auth::check()` from all methods
- **MessageController**: Removed `Auth::check()` from all methods
- **MessagesController**: Removed `Auth::check()` and role checks

## Security Benefits

1. **Centralized Security Logic**: All authentication and authorization is now defined in one place
2. **Consistent Protection**: No risk of forgetting to add auth checks to new routes
3. **CSRF Protection by Default**: All POST routes with middleware groups are protected
4. **PSR-15 Compliance**: Follows industry standards for middleware implementation
5. **Easier Auditing**: Security policies are visible in route definitions

## Testing

The middleware system has been tested with the development server. To test:

```bash
php -S localhost:8000 -t public
```

Test scenarios:
- Access `/dashboard` without authentication → redirects to `/login`
- Access `/admin/dashboard` as non-admin → access denied
- POST to protected routes without CSRF token → 419 error
- Access seller routes as non-seller → access denied

## Future Enhancements

Potential additions for Phase 2:
- **LoggingMiddleware**: Log all requests and responses
- **RateLimitMiddleware**: Prevent API abuse
- **CorsMiddleware**: Handle CORS headers for API routes
- **CacheMiddleware**: Cache GET requests
- **CompressionMiddleware**: Compress responses

## File Structure

```
app/Core/Middleware/
├── MiddlewareInterface.php      # PSR-15 interface
├── AbstractMiddleware.php        # Base middleware class
├── AuthenticationMiddleware.php  # Auth validation
├── RoleMiddleware.php            # Role-based access control
├── CsrfMiddleware.php            # CSRF protection
└── Pipeline.php                  # Middleware executor

app/Core/
├── Router.php                    # Enhanced with middleware support
└── ServiceProvider.php           # Middleware registration

app/Routes/
└── WebRoutes.php                 # Route definitions with middleware
```

## Troubleshooting

### Middleware Not Executing

- Ensure middleware is registered in `ServiceProvider::registerMiddleware()`
- Check that the middleware group is defined in `Router::defineMiddlewareGroups()`
- Verify the route has the correct middleware group assigned

### CSRF Errors

- Ensure forms include the CSRF token: `<?= Csrf::token() ?>`
- Check that POST requests include the `_csrf` field
- Verify the middleware group includes `CsrfMiddleware`

### Role-Based Access Issues

- Check that user roles are correctly set in the database
- Verify the role name matches exactly (case-sensitive)
- Ensure the middleware group includes `RoleMiddleware` with correct roles
