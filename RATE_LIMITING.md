# Rate Limiting Documentation

## Overview

Rate limiting is a security feature that protects the application from abuse by limiting the number of requests a client can make within a specific time window. This prevents:
- API abuse and DDoS attacks
- Brute force attacks on authentication endpoints
- Excessive resource consumption
- Spam and automated scraping

## Implementation Architecture

### Components

1. **RateLimitMiddleware** (`app/Core/Middleware/RateLimitMiddleware.php`)
   - Intercepts incoming requests
   - Tracks request counts per identifier (IP or user ID)
   - Enforces limits and returns 429 status when exceeded
   - Adds rate limit headers to responses

2. **RateLimitStore** (`app/Core/RateLimitStore.php`)
   - Manages rate limit data persistence
   - Stores request counts in the `rate_limits` database table
   - Handles incrementing, querying, and cleanup operations

3. **Router Integration** (`app/Core/Router.php`)
   - Defines middleware groups with different rate limit configurations
   - Resolves middleware groups and executes them in the pipeline

4. **Database Table** (`rate_limits`)
   - Stores rate limit records with identifier, endpoint, request count, and window start time
   - Indexed for efficient lookups

## Configuration

Rate limiting is configured in `app/config/app.php`:

```php
Config::set('rate_limit', [
    'auth' => [
        'max_requests' => 5,
        'window_seconds' => 60,
        'limit_type' => 'ip',
    ],
    'api' => [
        'max_requests' => 100,
        'window_seconds' => 60,
        'limit_type' => 'user',
    ],
    'api_guest' => [
        'max_requests' => 30,
        'window_seconds' => 60,
        'limit_type' => 'ip',
    ],
    'upload' => [
        'max_requests' => 10,
        'window_seconds' => 60,
        'limit_type' => 'user',
    ],
    'general' => [
        'max_requests' => 60,
        'window_seconds' => 60,
        'limit_type' => 'user',
    ],
]);
```

### Configuration Parameters

- **max_requests**: Maximum number of requests allowed in the time window
- **window_seconds**: Time window in seconds (e.g., 60 = 1 minute)
- **limit_type**: How to identify clients
  - `'ip'`: Rate limit by IP address
  - `'user'`: Rate limit by authenticated user ID

## Middleware Groups

The following middleware groups are defined in the Router:

| Group | Limit | Window | Type | Use Case |
|-------|-------|--------|------|----------|
| `throttle-auth` | 5 requests | 60 seconds | IP | Login/register endpoints |
| `throttle-api` | 100 requests | 60 seconds | User | Authenticated API calls |
| `throttle-api-guest` | 30 requests | 60 seconds | IP | Guest API calls |
| `throttle-upload` | 10 requests | 60 seconds | User | File uploads |
| `throttle-general` | 60 requests | 60 seconds | User | General authenticated actions |

## Usage

### Applying Rate Limiting to Routes

Add the throttle middleware to any route in `app/Routes/WebRoutes.php`:

```php
// Rate limit authentication endpoints
$router->post('/login', fn(Request $req) => $controller->login($req))
    ->middleware('throttle-auth');

// Rate limit API endpoints for guests
$router->get('/api/products', fn(Request $req) => $controller->products($req))
    ->middleware('throttle-api-guest');

// Rate limit authenticated API endpoints
$router->post('/api/cart/add', fn(Request $req) => $controller->addItem($req))
    ->middleware('auth', 'throttle-api');
```

### Multiple Middleware

Rate limiting can be combined with other middleware:

```php
$router->post('/api/messages/upload', fn(Request $req) => $controller->uploadImage($req))
    ->middleware('auth', 'throttle-upload');
```

## Response Headers

When rate limiting is active, the following headers are included in responses:

- **X-RateLimit-Limit**: Maximum requests allowed in the window
- **X-RateLimit-Remaining**: Remaining requests in the current window
- **X-RateLimit-Reset**: Unix timestamp when the window resets

Example:
```
X-RateLimit-Limit: 30
X-RateLimit-Remaining: 25
X-RateLimit-Reset: 1777025196
```

## Rate Limit Exceeded

When a client exceeds the rate limit:

- HTTP Status: **429 Too Many Requests**
- Content-Type: `application/json`
- Response body:
  ```json
  {
    "success": false,
    "message": "Too many requests. Please try again later.",
    "retry_after": 24
  }
  ```
- Additional header: **Retry-After**: Seconds until the limit resets

## Database Schema

The `rate_limits` table structure:

```sql
CREATE TABLE rate_limits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identifier VARCHAR(255) NOT NULL COMMENT 'IP address or user ID',
    endpoint VARCHAR(255) NOT NULL COMMENT 'Route endpoint',
    request_count INT DEFAULT 1 COMMENT 'Number of requests in window',
    window_start TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Start of time window',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_identifier_endpoint (identifier, endpoint),
    INDEX idx_window_start (window_start)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## How It Works

1. **Request Received**: Router receives a request and identifies matching route
2. **Middleware Resolution**: Router resolves middleware groups (e.g., `throttle-api-guest`)
3. **Pipeline Execution**: Middleware pipeline executes RateLimitMiddleware before the controller
4. **Identifier Generation**: Middleware generates identifier based on limit type (IP or user ID)
5. **Endpoint Normalization**: Endpoint is normalized (dynamic parameters replaced with placeholders)
6. **Request Counting**: RateLimitStore increments the request count for the identifier/endpoint pair
7. **Limit Check**: Middleware checks if count exceeds max_requests
8. **Response**:
   - If under limit: Adds rate limit headers and passes to next middleware
   - If over limit: Returns 429 status with error response
9. **Cleanup**: Old records (older than 1 hour) are periodically cleaned up (1 in 100 chance)

## Security Considerations

### IP-Based Limiting
- Uses multiple headers to detect real client IP (Cloudflare, X-Forwarded-For, etc.)
- Vulnerable to IP spoofing in some scenarios
- Suitable for public endpoints and authentication

### User-Based Limiting
- Only applies to authenticated users
- More reliable than IP-based limiting
- Requires authentication middleware to run first
- Suitable for API endpoints and user actions

### Endpoint Normalization
Dynamic parameters are normalized to prevent bypass:
- `/api/products/123` → `/api/products/{id}`
- `/api/messages/abc123...` → `/api/messages/{hash}`

This ensures rate limiting applies to the endpoint pattern, not specific parameter values.

## Testing

### Manual Testing

```bash
# Make multiple requests to a throttled endpoint
for i in {1..35}; do
  curl -i http://localhost:8000/api/products
  sleep 0.1
done
```

### Expected Behavior
- First 30 requests: 200 OK with rate limit headers
- Request 31+: 429 Too Many Requests with Retry-After header

## Troubleshooting

### Rate Limiting Not Working

1. **Check database table exists**:
   ```php
   $pdo->query("SHOW TABLES LIKE 'rate_limits'");
   ```

2. **Check middleware is applied to route**:
   Verify the route has the throttle middleware in `WebRoutes.php`

3. **Check configuration is loaded**:
   Verify `app/config/app.php` has the rate_limit configuration

4. **Check for headers in response**:
   Look for `X-RateLimit-*` headers in HTTP responses

### Too Many 429 Errors

- Increase `max_requests` in configuration
- Increase `window_seconds` for a longer time window
- Consider using user-based limiting instead of IP-based for authenticated users

## Customization

### Adding a New Rate Limit Group

1. Add configuration in `app/config/app.php`:
   ```php
   'custom' => [
       'max_requests' => 50,
       'window_seconds' => 300,
       'limit_type' => 'user',
   ],
   ```

2. Add middleware group in `app/Core/Router.php`:
   ```php
   'throttle-custom' => [
       new \App\Core\Middleware\RateLimitMiddleware(
           $rateLimitConfig['custom']['max_requests'] ?? 50,
           $rateLimitConfig['custom']['window_seconds'] ?? 300,
           $rateLimitConfig['custom']['limit_type'] ?? 'user'
       ),
   ],
   ```

3. Apply to routes in `app/Routes/WebRoutes.php`:
   ```php
   $router->post('/custom/endpoint', fn(Request $req) => $controller->custom($req))
       ->middleware('throttle-custom');
   ```

## Performance Impact

- **Database overhead**: One query per request (UPDATE or INSERT)
- **Indexing**: Optimized with indexes on `identifier_endpoint` and `window_start`
- **Cleanup**: Periodic cleanup (1% chance) to prevent table bloat
- **Memory**: Minimal - middleware is stateless, store uses database

## Best Practices

1. **Use appropriate limits**: Balance security with usability
2. **Use user-based limiting for authenticated endpoints**: More reliable than IP-based
3. **Use IP-based limiting for public endpoints**: Prevents abuse before authentication
4. **Monitor rate limit violations**: Track 429 responses to identify abuse patterns
5. **Adjust limits based on traffic**: Scale limits for high-traffic endpoints
6. **Document rate limits**: Inform API users of rate limits in API documentation

## Related Files

- `app/Core/Middleware/RateLimitMiddleware.php` - Middleware implementation
- `app/Core/RateLimitStore.php` - Database storage layer
- `app/Core/Router.php` - Middleware group definitions
- `app/Routes/WebRoutes.php` - Route middleware application
- `app/config/app.php` - Rate limit configuration
- `storage/sql/schema.sql` - Database table definition
- `app/Core/ServiceProvider.php` - Middleware registration
