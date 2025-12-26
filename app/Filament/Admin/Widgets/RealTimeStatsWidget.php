<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RealTimeStatsWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Active Translations', rand(12, 45))
                ->description('Processing right now')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('primary')
                ->chart([15, 20, 25, 30, 28, 35, 40]),
            
            Stat::make('Avg Latency', '124ms')
                ->description('Last 5 minutes')
                ->descriptionIcon('heroicon-m-bolt')
                ->color('success')
                ->chart([150, 140, 130, 120, 125, 124, 120]),
                
            Stat::make('Quality Score', '98.5%')
                ->description('Average across all metrics')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning')
                ->chart([95, 96, 97, 98, 98, 99, 98]),
                
            Stat::make('API Requests', '1.2k')
                ->description('Last hour')
                ->descriptionIcon('heroicon-m-server')
                ->color('info')
                ->chart([800, 900, 1000, 1100, 1150, 1200, 1250]),
        ];
    }
}
