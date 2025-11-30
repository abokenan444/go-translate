<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\Scaling\AutoScaleService;

class AutoscaleAction extends Command
{
    protected $signature = 'autoscale:action 
                            {--clear : Clear the scale-out flag after processing}';

    protected $description = 'Check autoscale conditions and emit scaling events for external orchestration';

    public function handle()
    {
        $service = app(AutoScaleService::class);
        $status = $service->status();
        
        $this->info("Autoscale Status Check");
        $this->line("═══════════════════════════════════════");
        $this->line("Active Sessions: {$status['active_sessions']}");
        $this->line("Scale-out Pending: " . ($status['scale_out_pending'] ? 'YES' : 'NO'));
        
        if ($status['scale_out_pending']) {
            $this->warn("⚠ SCALE-OUT REQUIRED");
            
            // Emit scaling event (can be adapted for Kubernetes, AWS, Azure, etc.)
            $event = [
                'event' => 'scale_out',
                'timestamp' => now()->toIso8601String(),
                'active_sessions' => $status['active_sessions'],
                'threshold' => config('realtime.autoscale.scale_out_threshold', 1000),
                'recommended_action' => 'Increase worker pool capacity',
            ];
            
            // Log for external monitoring systems (Prometheus, Grafana, CloudWatch, etc.)
            Log::channel('stack')->warning('Autoscale scale-out event', $event);
            
            // Write event to metrics cache for external consumption
            Cache::put('autoscale:last_event', $event, 600);
            
            $this->table(
                ['Property', 'Value'],
                [
                    ['Event Type', $event['event']],
                    ['Timestamp', $event['timestamp']],
                    ['Active Sessions', $event['active_sessions']],
                    ['Threshold', $event['threshold']],
                    ['Recommended Action', $event['recommended_action']],
                ]
            );
            
            // Optional: Call external webhook for orchestration
            if ($webhook = config('realtime.autoscale.webhook_url')) {
                try {
                    \Illuminate\Support\Facades\Http::timeout(5)
                        ->post($webhook, $event);
                    $this->info("✓ Webhook notification sent to: {$webhook}");
                } catch (\Exception $e) {
                    $this->error("✗ Webhook failed: {$e->getMessage()}");
                }
            }
            
            if ($this->option('clear')) {
                Cache::forget('scaling:pending_scale_out');
                $this->info("✓ Scale-out flag cleared");
            }
            
            return Command::SUCCESS;
        }
        
        $this->info("✓ No scaling action required");
        return Command::SUCCESS;
    }
}
