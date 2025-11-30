<?php

namespace App\Console\Commands;

use App\Models\SecurityLog;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DailySecurityReport;

class SendDailySecurityReport extends Command
{
    protected $signature = 'security:daily-report';
    protected $description = 'Send daily security summary report to super admins';

    public function handle()
    {
        if (!config('security_alerts.notifications.summarize_daily')) {
            $this->info('Daily reports are disabled in config.');
            return 0;
        }

        // Get yesterday's stats
        $yesterday = now()->subDay();
        
        $stats = [
            'total_attacks' => SecurityLog::whereDate('created_at', $yesterday)->count(),
            'blocked' => SecurityLog::whereDate('created_at', $yesterday)->where('blocked', true)->count(),
            'high_severity' => SecurityLog::whereDate('created_at', $yesterday)->where('severity', 'high')->count(),
            'critical' => SecurityLog::whereDate('created_at', $yesterday)->where('severity', 'critical')->count(),
            'by_type' => SecurityLog::whereDate('created_at', $yesterday)
                ->selectRaw('attack_type, count(*) as count')
                ->groupBy('attack_type')
                ->pluck('count', 'attack_type')
                ->toArray(),
            'top_ips' => SecurityLog::whereDate('created_at', $yesterday)
                ->selectRaw('ip_address, count(*) as count')
                ->groupBy('ip_address')
                ->orderByDesc('count')
                ->limit(10)
                ->pluck('count', 'ip_address')
                ->toArray(),
            'date' => $yesterday->format('Y-m-d'),
        ];

        // Send only if there were attacks
        if ($stats['total_attacks'] > 0) {
            $admins = User::whereHas('roles', function($query) {
                $query->where('name', 'super_admin');
            })->get();

            Notification::send($admins, new DailySecurityReport($stats));
            
            $this->info("Daily security report sent to {$admins->count()} admins.");
            $this->info("Total attacks: {$stats['total_attacks']}");
        } else {
            $this->info('No attacks yesterday. No report sent.');
        }

        return 0;
    }
}
