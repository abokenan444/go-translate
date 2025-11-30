<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Refresh Zoom/Teams tokens hourly (light load)
        $schedule->command('integrations:refresh-tokens')->hourly()->withoutOverlapping();
        
        // Check autoscale conditions every 5 minutes
        $schedule->command('autoscale:action')->everyFiveMinutes()->withoutOverlapping();
        
        // Optionally daily dataset export snapshot (disabled by default)
        // $schedule->command('dataset:export')->dailyAt('02:15');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
