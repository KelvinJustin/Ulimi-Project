<?php
declare(strict_types=1);

namespace App\Core\Middleware;

use App\Core\Request;
use App\Core\RateLimitStore;
use App\Core\Auth;

final class RateLimitMiddleware
{
    private RateLimitStore $store;
    private int $maxRequests;
    private int $windowSeconds;
    private string $limitType;

    public function __construct(
        int $maxRequests = 60,
        int $windowSeconds = 60,
        string $limitType = 'ip'
    ) {
        $this->store = new RateLimitStore();
        $this->maxRequests = $maxRequests;
        $this->windowSeconds = $windowSeconds;
        $this->limitType = $limitType; // 'ip' or 'user'
    }

    public function handle(Request $request, callable $next): void
    {
        // Get identifier based on limit type
        $identifier = $this->getIdentifier();
        
        // Get current endpoint
        $endpoint = $this->getEndpoint();
        
        // Clean up old records periodically (1 in 100 chance)
        if (rand(1, 100) === 1) {
            $this->store->cleanup();
        }
        
        // Increment request count
        $requestCount = $this->store->increment($identifier, $endpoint, $this->windowSeconds);
        
        // Check if limit exceeded
        if ($requestCount > $this->maxRequests) {
            $resetTime = $this->store->getResetTime($identifier, $endpoint, $this->windowSeconds);
            
            http_response_code(429);
            header('Content-Type: application/json');
            header('Retry-After: ' . $resetTime);
            header('X-RateLimit-Limit: ' . $this->maxRequests);
            header('X-RateLimit-Remaining: 0');
            header('X-RateLimit-Reset: ' . (time() + $resetTime));
            
            echo json_encode([
                'success' => false,
                'message' => 'Too many requests. Please try again later.',
                'retry_after' => $resetTime
            ]);
            exit;
        }
        
        // Add rate limit headers
        header('X-RateLimit-Limit: ' . $this->maxRequests);
        header('X-RateLimit-Remaining: ' . ($this->maxRequests - $requestCount));
        header('X-RateLimit-Reset: ' . (time() + $this->store->getResetTime($identifier, $endpoint, $this->windowSeconds)));
        
        // Continue to next middleware
        $next($request);
    }

    /**
     * Get identifier for rate limiting (IP or user ID)
     */
    private function getIdentifier(): string
    {
        if ($this->limitType === 'user' && Auth::check()) {
            return 'user:' . Auth::user()['id'];
        }
        
        return 'ip:' . $this->getClientIp();
    }

    /**
     * Get client IP address
     */
    private function getClientIp(): string
    {
        $ipHeaders = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ($ipHeaders as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                return trim($ips[0]);
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    /**
     * Get normalized endpoint for rate limiting
     */
    private function getEndpoint(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Normalize dynamic parameters
        $endpoint = preg_replace('/\d+/', '{id}', $uri);
        $endpoint = preg_replace('/[a-f0-9]{32}/', '{hash}', $endpoint);
        
        return $endpoint;
    }
}
