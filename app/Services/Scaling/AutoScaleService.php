<?php

namespace App\Services\Scaling;

use Illuminate\Support\Facades\Cache;

class AutoScaleService
{
    public function recordSessionCreated(): void
    {
        Cache::increment('scaling:sessions_active');
        $this->check();
    }

    public function recordSessionEnded(): void
    {
        $val = Cache::decrement('scaling:sessions_active');
        if ($val < 0) Cache::put('scaling:sessions_active', 0);
    }

    protected function check(): void
    {
        $cfg = config('realtime.cluster');
        if (!$cfg['enabled']) return;
        $active = Cache::get('scaling:sessions_active', 0);
        $nodes = (int)$cfg['nodes'];
        $perNode = $active / max(1,$nodes);
        $threshold = $cfg['max_sessions_per_node'];
        if ($perNode > $threshold && $cfg['autoscale']['enabled']) {
            Cache::put('scaling:pending_scale_out', now()->toIso8601String(), 300);
        }
    }

    public function status(): array
    {
        return [
            'active_sessions' => Cache::get('scaling:sessions_active',0),
            'scale_out_pending' => Cache::has('scaling:pending_scale_out'),
        ];
    }
}
