<?php

namespace App\Console\Commands;

use App\Models\SecurityLog;
use Illuminate\Console\Command;

class CleanupSecurityLogs extends Command
{
    protected $signature = 'security:cleanup';
    protected $description = 'Clean up old security logs based on retention policy';

    public function handle()
    {
        if (!config('security_alerts.retention.auto_cleanup')) {
            $this->info('Auto cleanup is disabled in config.');
            return 0;
        }

        $retentionDays = config('security_alerts.retention.days', 90);
        $cutoffDate = now()->subDays($retentionDays);

        $deleted = SecurityLog::where('created_at', '<', $cutoffDate)->delete();

        $this->info("Deleted {$deleted} security logs older than {$retentionDays} days.");
        
        return 0;
    }
}
