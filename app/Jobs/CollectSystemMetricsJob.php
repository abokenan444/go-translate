<?php

namespace App\Jobs;

use App\Services\RealTimeMonitoringService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CollectSystemMetricsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(RealTimeMonitoringService $monitoring): void
    {
        $status = $monitoring->getSystemStatus();
        
        // Track key metrics
        foreach ($status['metrics'] as $name => $value) {
            $monitoring->trackMetric($name, $value);
        }
        
        // Track service health
        foreach ($status['services'] as $service => $details) {
            $monitoring->trackMetric(
                "service.{$service}.status",
                $details['status'] === 'up' ? 1 : 0,
                ['service' => $service]
            );
            
            if (isset($details['latency_ms'])) {
                $monitoring->trackMetric(
                    "service.{$service}.latency",
                    $details['latency_ms'],
                    ['service' => $service]
                );
            }
        }
    }
}
