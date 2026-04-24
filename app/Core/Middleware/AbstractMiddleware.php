<?php
declare(strict_types=1);

namespace App\Core\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Abstract Middleware
 * 
 * Base class for middleware implementations providing common functionality.
 */
abstract class AbstractMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request and return a response.
     * 
     * This method calls the handle() method which subclasses must implement.
     * If handle() returns true, the request is passed to the next handler.
     * If handle() returns false, the middleware has handled the response.
     *
     * @param ServerRequestInterface $request The request to process
     * @param RequestHandlerInterface $handler The next handler in the pipeline
     * @return mixed The response from the handler
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): mixed
    {
        if ($this->handle($request)) {
            return $handler->handle($request);
        }
        
        return null;
    }

    /**
     * Handle the request.
     * 
     * Subclasses must implement this method to perform their logic.
     * Return true to pass the request to the next handler.
     * Return false to stop the pipeline (middleware has handled the response).
     *
     * @param ServerRequestInterface $request The request to process
     * @return bool True to continue, false to stop
     */
    abstract protected function handle(ServerRequestInterface $request): bool;
}
