<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\GovernmentVerification;

class GovernmentVerificationStats extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $pending = GovernmentVerification::where('status', 'pending')->count();
        $underReview = GovernmentVerification::where('status', 'under_review')->count();
        $approved = GovernmentVerification::where('status', 'approved')->count();
        $rejected = GovernmentVerification::where('status', 'rejected')->count();
        $total = GovernmentVerification::count();

        $thisWeek = GovernmentVerification::where('created_at', '>=', now()->subWeek())->count();
        $lastWeek = GovernmentVerification::whereBetween('created_at', [now()->subWeeks(2), now()->subWeek()])->count();

        $trend = $lastWeek > 0 ? round((($thisWeek - $lastWeek) / $lastWeek) * 100) : 0;

        return [
            Stat::make('Pending Review', $pending)
                ->description('Awaiting initial review')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning')
                ->chart([7, 3, 4, 5, 6, $pending]),

            Stat::make('Under Review', $underReview)
                ->description('Currently being reviewed')
                ->descriptionIcon('heroicon-o-magnifying-glass')
                ->color('info'),

            Stat::make('Approved', $approved)
                ->description('Verified government entities')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Rejected', $rejected)
                ->description('Failed verification')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),

            Stat::make('This Week', $thisWeek)
                ->description($trend >= 0 ? "+{$trend}% from last week" : "{$trend}% from last week")
                ->descriptionIcon($trend >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->color($trend >= 0 ? 'success' : 'danger'),

            Stat::make('Total Requests', $total)
                ->description('All time submissions')
                ->descriptionIcon('heroicon-o-building-library')
                ->color('gray'),
        ];
    }
}
