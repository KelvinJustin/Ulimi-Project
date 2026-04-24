<?php
declare(strict_types=1);

namespace App\Core\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * PSR-15 Middleware Interface
 * 
 * Defines the contract for middleware components that can process HTTP requests
 * and delegate to the next middleware in the pipeline.
 */
interface MiddlewareInterface
{
    /**
     * Process an incoming server request and return a response.
     *
     * @param ServerRequestInterface $request The request to process
     * @param RequestHandlerInterface $handler The next handler in the pipeline
     * @return mixed The response from the handler
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): mixed;
}
