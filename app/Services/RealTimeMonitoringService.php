<?php

namespace App\Services;

use App\Events\SystemMetricUpdated;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class RealTimeMonitoringService
{
    /**
     * Track system metrics in real-time
     */
    public function trackMetric(string $metricName, $value, array $tags = [])
    {
        $timestamp = now();
        
        $metric = [
            'name' => $metricName,
            'value' => $value,
            'tags' => $tags,
            'timestamp' => $timestamp->toIso8601String(),
        ];

        // Store in Redis for real-time access
        $key = "metrics:{$metricName}:" . $timestamp->format('YmdHis');
        Redis::setex($key, 3600, json_encode($metric));

        // Add to time-series
        $seriesKey = "metrics:series:{$metricName}";
        Redis::zadd($seriesKey, $timestamp->timestamp, json_encode($metric));
        Redis::expire($seriesKey, 86400); // Keep 24 hours

        // Broadcast event
        broadcast(new SystemMetricUpdated($metric));

        // Check thresholds and alert if needed
        $this->checkThresholds($metricName, $value, $tags);

        return $metric;
    }

    /**
     * Get real-time metrics
     */
    public function getMetrics(string $metricName, $minutes = 60)
    {
        $seriesKey = "metrics:series:{$metricName}";
        $since = now()->subMinutes($minutes)->timestamp;
        
        $metrics = Redis::zrangebyscore($seriesKey, $since, '+inf');
        
        return array_map(function($metric) {
            return json_decode($metric, true);
        }, $metrics);
    }

    /**
     * Get current system status
     */
    public function getSystemStatus()
    {
        return Cache::remember('system:status', 30, function() {
            $status = [
                'timestamp' => now()->toIso8601String(),
                'services' => $this->checkServices(),
                'metrics' => $this->getCurrentMetrics(),
                'health' => 'healthy',
            ];

            // Determine overall health
            foreach ($status['services'] as $service) {
                if ($service['status'] !== 'up') {
                    $status['health'] = 'degraded';
                    break;
                }
            }

            return $status;
        });
    }

    /**
     * Check all services
     */
    private function checkServices()
    {
        return [
            'database' => $this->checkDatabase(),
            'redis' => $this->checkRedis(),
            'queue' => $this->checkQueue(),
            'storage' => $this->checkStorage(),
            'ai_services' => $this->checkAIServices(),
        ];
    }

    /**
     * Check database connection
     */
    private function checkDatabase()
    {
        try {
            DB::connection()->getPdo();
            $latency = $this->measureLatency(function() {
                DB::select('SELECT 1');
            });

            return [
                'status' => 'up',
                'latency_ms' => $latency,
                'connections' => DB::connection()->select('SHOW STATUS LIKE "Threads_connected"')[0]->Value ?? 0,
            ];
        } catch (\Exception $e) {
            Log::error('Database health check failed', ['error' => $e->getMessage()]);
            return ['status' => 'down', 'error' => $e->getMessage()];
        }
    }

    /**
     * Check Redis connection
     */
    private function checkRedis()
    {
        try {
            $latency = $this->measureLatency(function() {
                Redis::ping();
            });

            return [
                'status' => 'up',
                'latency_ms' => $latency,
                'used_memory' => Redis::info()['used_memory_human'] ?? 'unknown',
            ];
        } catch (\Exception $e) {
            Log::error('Redis health check failed', ['error' => $e->getMessage()]);
            return ['status' => 'down', 'error' => $e->getMessage()];
        }
    }

    /**
     * Check queue status
     */
    private function checkQueue()
    {
        try {
            $pendingJobs = DB::table('jobs')->count();
            $failedJobs = DB::table('failed_jobs')->count();

            return [
                'status' => $failedJobs > 100 ? 'degraded' : 'up',
                'pending_jobs' => $pendingJobs,
                'failed_jobs' => $failedJobs,
            ];
        } catch (\Exception $e) {
            Log::error('Queue health check failed', ['error' => $e->getMessage()]);
            return ['status' => 'down', 'error' => $e->getMessage()];
        }
    }

    /**
     * Check storage
     */
    private function checkStorage()
    {
        try {
            $disk = disk_free_space(storage_path());
            $total = disk_total_space(storage_path());
            $usedPercent = (($total - $disk) / $total) * 100;

            return [
                'status' => $usedPercent > 90 ? 'warning' : 'up',
                'free_space_gb' => round($disk / 1024 / 1024 / 1024, 2),
                'used_percent' => round($usedPercent, 2),
            ];
        } catch (\Exception $e) {
            Log::error('Storage health check failed', ['error' => $e->getMessage()]);
            return ['status' => 'down', 'error' => $e->getMessage()];
        }
    }

    /**
     * Check AI services
     */
    private function checkAIServices()
    {
        $services = [];
        
        // Check OpenAI
        if (config('services.openai.api_key')) {
            $services['openai'] = $this->checkOpenAI();
        }

        // Check Claude
        if (config('services.anthropic.api_key')) {
            $services['claude'] = $this->checkClaude();
        }

        return $services;
    }

    /**
     * Check OpenAI availability
     */
    private function checkOpenAI()
    {
        try {
            $client = new \OpenAI\Client(config('services.openai.api_key'));
            $latency = $this->measureLatency(function() use ($client) {
                $client->models()->list();
            });

            return [
                'status' => 'up',
                'latency_ms' => $latency,
            ];
        } catch (\Exception $e) {
            return ['status' => 'down', 'error' => $e->getMessage()];
        }
    }

    /**
     * Check Claude availability
     */
    private function checkClaude()
    {
        // Implement Claude health check
        return ['status' => 'up', 'latency_ms' => 0];
    }

    /**
     * Get current metrics
     */
    private function getCurrentMetrics()
    {
        return [
            'active_users' => $this->getActiveUsers(),
            'active_translations' => $this->getActiveTranslations(),
            'avg_response_time' => $this->getAverageResponseTime(),
            'error_rate' => $this->getErrorRate(),
        ];
    }

    /**
     * Get active users count
     */
    private function getActiveUsers()
    {
        return Cache::remember('metrics:active_users', 60, function() {
            return DB::table('sessions')
                ->where('last_activity', '>', now()->subMinutes(15)->timestamp)
                ->count();
        });
    }

    /**
     * Get active translations count
     */
    private function getActiveTranslations()
    {
        return Cache::remember('metrics:active_translations', 60, function() {
            return DB::table('documents')
                ->whereIn('status', ['pending', 'in_progress', 'under_review'])
                ->count();
        });
    }

    /**
     * Get average response time
     */
    private function getAverageResponseTime()
    {
        $metrics = $this->getMetrics('response_time', 5);
        if (empty($metrics)) {
            return 0;
        }

        $sum = array_sum(array_column($metrics, 'value'));
        return round($sum / count($metrics), 2);
    }

    /**
     * Get error rate
     */
    private function getErrorRate()
    {
        $total = Redis::get('metrics:requests:total') ?? 0;
        $errors = Redis::get('metrics:requests:errors') ?? 0;

        if ($total == 0) {
            return 0;
        }

        return round(($errors / $total) * 100, 2);
    }

    /**
     * Check thresholds and alert
     */
    private function checkThresholds(string $metricName, $value, array $tags)
    {
        $thresholds = config('monitoring.thresholds', []);
        
        if (!isset($thresholds[$metricName])) {
            return;
        }

        $threshold = $thresholds[$metricName];
        
        if (isset($threshold['max']) && $value > $threshold['max']) {
            $this->sendAlert($metricName, $value, 'exceeded', $threshold['max'], $tags);
        }

        if (isset($threshold['min']) && $value < $threshold['min']) {
            $this->sendAlert($metricName, $value, 'below', $threshold['min'], $tags);
        }
    }

    /**
     * Send alert
     */
    private function sendAlert(string $metricName, $value, string $type, $threshold, array $tags)
    {
        Log::warning("Metric threshold {$type}", [
            'metric' => $metricName,
            'value' => $value,
            'threshold' => $threshold,
            'tags' => $tags,
        ]);

        // Send notification (implement based on your notification system)
        // notification()->send(...)
    }

    /**
     * Measure execution latency
     */
    private function measureLatency(callable $callback)
    {
        $start = microtime(true);
        $callback();
        $end = microtime(true);
        
        return round(($end - $start) * 1000, 2);
    }

    /**
     * Track request
     */
    public function trackRequest(bool $success = true)
    {
        Redis::incr('metrics:requests:total');
        
        if (!$success) {
            Redis::incr('metrics:requests:errors');
        }

        // Expire counters after 1 hour
        Redis::expire('metrics:requests:total', 3600);
        Redis::expire('metrics:requests:errors', 3600);
    }
}
