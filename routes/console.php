<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Real-Time Session Cleanup
use Illuminate\Support\Facades\Schedule;

Schedule::command('realtime:cleanup --days=7')->daily();
Schedule::command('realtime:end-inactive --minutes=30')->hourly();
Schedule::command("realtime:collect-metrics")->everyFiveMinutes();

// Security Alert System
Schedule::command('security:daily-report')->dailyAt('09:00');
Schedule::command('security:cleanup')->weekly();
