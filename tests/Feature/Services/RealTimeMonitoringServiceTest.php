<?php

namespace Tests\Feature\Services;

use App\Services\RealTimeMonitoringService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class RealTimeMonitoringServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $monitoringService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->monitoringService = app(RealTimeMonitoringService::class);
    }

    /** @test */
    public function can_track_metric()
    {
        $metric = $this->monitoringService->trackMetric('test_metric', 100, ['tag' => 'test']);

        $this->assertEquals('test_metric', $metric['name']);
        $this->assertEquals(100, $metric['value']);
        $this->assertArrayHasKey('timestamp', $metric);
    }

    /** @test */
    public function can_get_metrics()
    {
        $this->monitoringService->trackMetric('test_metric', 100);
        $this->monitoringService->trackMetric('test_metric', 200);

        $metrics = $this->monitoringService->getMetrics('test_metric', 60);

        $this->assertIsArray($metrics);
        $this->assertGreaterThanOrEqual(2, count($metrics));
    }

    /** @test */
    public function can_get_system_status()
    {
        $status = $this->monitoringService->getSystemStatus();

        $this->assertArrayHasKey('timestamp', $status);
        $this->assertArrayHasKey('services', $status);
        $this->assertArrayHasKey('metrics', $status);
        $this->assertArrayHasKey('health', $status);
    }

    /** @test */
    public function system_health_checks_database()
    {
        $status = $this->monitoringService->getSystemStatus();

        $this->assertArrayHasKey('database', $status['services']);
        $this->assertEquals('up', $status['services']['database']['status']);
    }

    /** @test */
    public function tracks_request_counts()
    {
        $this->monitoringService->trackRequest(true);
        $this->monitoringService->trackRequest(true);
        $this->monitoringService->trackRequest(false);

        $total = Redis::get('metrics:requests:total');
        $errors = Redis::get('metrics:requests:errors');

        $this->assertEquals(3, $total);
        $this->assertEquals(1, $errors);
    }

    /** @test */
    public function calculates_error_rate()
    {
        Redis::set('metrics:requests:total', 100);
        Redis::set('metrics:requests:errors', 5);

        $errorRate = $this->monitoringService->getErrorRate();

        $this->assertEquals(5.0, $errorRate);
    }

    protected function tearDown(): void
    {
        Redis::flushdb();
        parent::tearDown();
    }
}
