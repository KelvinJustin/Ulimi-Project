<?php
declare(strict_types=1);

namespace Tests\Core\Middleware;

use App\Core\Middleware\HttpsMiddleware;
use PHPUnit\Framework\TestCase;
use App\Core\Config;

/**
 * Unit tests for HttpsMiddleware
 * 
 * Note: Due to XAMPP environment limitations and the complexity of mocking 
 * PHP superglobals and header functions, these tests focus on basic functionality.
 * For comprehensive testing, use the manual testing procedures in MANUAL_TESTING.md
 */
class HttpsMiddlewareTest extends TestCase
{
    private HttpsMiddleware $middleware;
    private array $originalServer;

    protected function setUp(): void
    {
        $this->middleware = new HttpsMiddleware();
        $this->originalServer = $_SERVER;
        
        // Disable exit for all tests by default
        $this->middleware->setShouldExit(false);
        
        // Reset config to default test values
        Config::set('app', [
            'name' => 'Ulimi 3.0',
            'base_url' => '',
            'env' => 'testing',
        ]);
        Config::set('security', [
            'csrf_key' => 'test-csrf-key',
            'jwt_secret' => 'test-jwt-secret',
            'cookie_secure' => false,
            'force_https' => false,
        ]);
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->originalServer;
        Config::set('security', [
            'csrf_key' => 'test-csrf-key',
            'jwt_secret' => 'test-jwt-secret',
            'cookie_secure' => false,
            'force_https' => false,
        ]);
    }

    public function testMiddlewareCanBeInstantiated(): void
    {
        $this->assertInstanceOf(HttpsMiddleware::class, $this->middleware);
    }

    public function testSetShouldExitMethod(): void
    {
        $this->middleware->setShouldExit(true);
        $this->middleware->setShouldExit(false);
        $this->assertTrue(true, 'setShouldExit method works without errors');
    }

    public function testAllowsHttpsInLocalDevelopment(): void
    {
        // Setup
        $_SERVER['HTTPS'] = 'on';
        Config::set('app', [
            'name' => 'Ulimi 3.0',
            'base_url' => '',
            'env' => 'local',
        ]);
        Config::set('security', [
            'csrf_key' => 'test-csrf-key',
            'jwt_secret' => 'test-jwt-secret',
            'cookie_secure' => false,
            'force_https' => false,
        ]);

        // Create mock PSR-7 request and handler
        $request = $this->createMockPsrRequest('GET');
        $handler = $this->createMock(\Psr\Http\Server\RequestHandlerInterface::class);
        $handler->expects($this->once())
                ->method('handle')
                ->willReturn($this->createMock(\Psr\Http\Message\ResponseInterface::class));

        // Execute using public process() method
        $this->middleware->process($request, $handler);

        // Test passes if no exception was thrown and handler was called
        $this->assertTrue(true, 'HTTPS request should be allowed in local development');
    }

    public function testAllowsHttpInLocalDevelopment(): void
    {
        // Setup
        $_SERVER['HTTPS'] = 'off';
        Config::set('app', [
            'name' => 'Ulimi 3.0',
            'base_url' => '',
            'env' => 'local',
        ]);
        Config::set('security', [
            'csrf_key' => 'test-csrf-key',
            'jwt_secret' => 'test-jwt-secret',
            'cookie_secure' => false,
            'force_https' => false,
        ]);

        // Create mock PSR-7 request and handler
        $request = $this->createMockPsrRequest('GET');
        $handler = $this->createMock(\Psr\Http\Server\RequestHandlerInterface::class);
        $handler->expects($this->once())
                ->method('handle')
                ->willReturn($this->createMock(\Psr\Http\Message\ResponseInterface::class));

        // Execute using public process() method
        $this->middleware->process($request, $handler);

        // Test passes if no exception was thrown and handler was called
        $this->assertTrue(true, 'HTTP should be allowed in local development');
    }

    public function testDetectsReverseProxyHttps(): void
    {
        // Setup
        $_SERVER['HTTPS'] = 'off';
        $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
        Config::set('app', [
            'name' => 'Ulimi 3.0',
            'base_url' => '',
            'env' => 'production',
        ]);
        Config::set('security', [
            'csrf_key' => 'test-csrf-key',
            'jwt_secret' => 'test-jwt-secret',
            'cookie_secure' => false,
            'force_https' => true,
        ]);

        // Create mock PSR-7 request and handler
        $request = $this->createMockPsrRequest('GET');
        $handler = $this->createMock(\Psr\Http\Server\RequestHandlerInterface::class);
        $handler->expects($this->once())
                ->method('handle')
                ->willReturn($this->createMock(\Psr\Http\Message\ResponseInterface::class));

        // Execute using public process() method
        $this->middleware->process($request, $handler);

        // Test passes if no exception was thrown and handler was called
        $this->assertTrue(true, 'Should allow HTTPS when detected via reverse proxy header');
    }

    /**
     * Create a mock PSR-7 ServerRequestInterface
     */
    private function createMockPsrRequest(string $method)
    {
        $request = $this->createMock(\Psr\Http\Message\ServerRequestInterface::class);
        $request->method('getMethod')->willReturn($method);
        return $request;
    }
}
