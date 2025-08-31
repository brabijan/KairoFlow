<?php
declare(strict_types=1);

namespace Tests\Unit\Api;

use App\Model\System\HealthCheckService;
use App\Module\Api\HealthPresenter;
use Mockery;
use Nette\Application\Request;
use Nette\Application\Responses\JsonResponse;
use Nette\Http\Request as HttpRequest;
use Nette\Http\IResponse as HttpResponse;
use Nette\Http\UrlScript;
use PHPUnit\Framework\TestCase;

final class HealthPresenterTest extends TestCase
{
    private HealthCheckService $healthCheckService;
    private HealthPresenter $presenter;
    private HttpResponse $httpResponse;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->healthCheckService = Mockery::mock(HealthCheckService::class);
        $this->httpResponse = Mockery::mock(HttpResponse::class);
        
        // Set default expectations for HttpResponse
        $this->httpResponse->shouldReceive('isSent')->andReturn(false)->byDefault();
        $this->httpResponse->shouldReceive('addHeader')->byDefault();
        $this->httpResponse->shouldReceive('getCode')->andReturn(200)->byDefault();
        
        $this->presenter = new HealthPresenter($this->healthCheckService);
        
        $httpRequest = new HttpRequest(new UrlScript('http://localhost/health'));
        $this->presenter->injectPrimary(
            $httpRequest,
            $this->httpResponse
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testHealthEndpointReturns200WhenHealthy(): void
    {
        $healthData = [
            'status' => 'healthy',
            'timestamp' => time(),
            'checks' => [
                'database' => ['status' => 'healthy', 'message' => 'Database connection successful'],
                'redis' => ['status' => 'healthy', 'message' => 'Redis connection successful'],
                'disk_space' => ['status' => 'healthy', 'message' => 'Disk space OK: 50% used'],
                'php' => ['status' => 'healthy', 'version' => PHP_VERSION],
            ],
        ];
        
        $this->healthCheckService
            ->shouldReceive('checkHealth')
            ->once()
            ->andReturn($healthData);
        
        $this->httpResponse
            ->shouldReceive('setCode')
            ->once()
            ->with(200);
        
        $request = new Request('Api:Health', 'default');
        $response = $this->presenter->run($request);
        
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function testHealthEndpointReturns503WhenUnhealthy(): void
    {
        $healthData = [
            'status' => 'unhealthy',
            'timestamp' => time(),
            'checks' => [
                'database' => ['status' => 'unhealthy', 'message' => 'Database connection failed'],
                'redis' => ['status' => 'healthy', 'message' => 'Redis connection successful'],
                'disk_space' => ['status' => 'healthy', 'message' => 'Disk space OK: 50% used'],
                'php' => ['status' => 'healthy', 'version' => PHP_VERSION],
            ],
        ];
        
        $this->healthCheckService
            ->shouldReceive('checkHealth')
            ->once()
            ->andReturn($healthData);
        
        $this->httpResponse
            ->shouldReceive('setCode')
            ->once()
            ->with(503);
        
        $request = new Request('Api:Health', 'default');
        $response = $this->presenter->run($request);
        
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function testHealthEndpointReturns200WhenDegraded(): void
    {
        $healthData = [
            'status' => 'degraded',
            'timestamp' => time(),
            'checks' => [
                'database' => ['status' => 'healthy', 'message' => 'Database connection successful'],
                'redis' => ['status' => 'healthy', 'message' => 'Redis connection successful'],
                'disk_space' => ['status' => 'degraded', 'message' => 'Disk space warning: 85% used'],
                'php' => ['status' => 'healthy', 'version' => PHP_VERSION],
            ],
        ];
        
        $this->healthCheckService
            ->shouldReceive('checkHealth')
            ->once()
            ->andReturn($healthData);
        
        $this->httpResponse
            ->shouldReceive('setCode')
            ->once()
            ->with(200);
        
        $request = new Request('Api:Health', 'default');
        $response = $this->presenter->run($request);
        
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function testJsonResponseStructure(): void
    {
        $timestamp = time();
        $healthData = [
            'status' => 'healthy',
            'timestamp' => $timestamp,
            'checks' => [
                'database' => ['status' => 'healthy', 'message' => 'Database connection successful'],
                'redis' => ['status' => 'healthy', 'message' => 'Redis connection successful'],
                'disk_space' => ['status' => 'healthy', 'message' => 'Disk space OK: 50% used', 'percent_used' => 50],
                'php' => ['status' => 'healthy', 'version' => PHP_VERSION, 'required' => '^8.4'],
            ],
        ];
        
        $this->healthCheckService
            ->shouldReceive('checkHealth')
            ->once()
            ->andReturn($healthData);
        
        $this->httpResponse
            ->shouldReceive('setCode')
            ->once()
            ->with(200);
        
        $request = new Request('Api:Health', 'default');
        $response = $this->presenter->run($request);
        
        $this->assertInstanceOf(JsonResponse::class, $response);
        
        $payload = $response->getPayload();
        $this->assertIsArray($payload);
        $this->assertArrayHasKey('status', $payload);
        $this->assertArrayHasKey('timestamp', $payload);
        $this->assertArrayHasKey('checks', $payload);
        $this->assertIsArray($payload['checks']);
        $this->assertArrayHasKey('database', $payload['checks']);
        $this->assertArrayHasKey('redis', $payload['checks']);
        $this->assertArrayHasKey('disk_space', $payload['checks']);
        $this->assertArrayHasKey('php', $payload['checks']);
    }
}