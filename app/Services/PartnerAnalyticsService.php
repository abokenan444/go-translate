<?php

namespace App\Services;

use App\Models\Partner;
use App\Models\PartnerApiLog;
use App\Models\PartnerCommission;
use App\Models\PartnerUsageStat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PartnerAnalyticsService
{
    /**
     * Get comprehensive analytics for partner
     */
    public function getAnalytics(Partner $partner, ?string $period = 'month'): array
    {
        $dateRange = $this->getDateRange($period);

        return [
            'overview' => $this->getOverview($partner, $dateRange),
            'usage' => $this->getUsageAnalytics($partner, $dateRange),
            'revenue' => $this->getRevenueAnalytics($partner, $dateRange),
            'api' => $this->getApiAnalytics($partner, $dateRange),
            'trends' => $this->getTrends($partner, $dateRange),
            'top_languages' => $this->getTopLanguages($partner, $dateRange),
            'performance' => $this->getPerformanceMetrics($partner, $dateRange),
        ];
    }

    /**
     * Get overview statistics
     */
    protected function getOverview(Partner $partner, array $dateRange): array
    {
        $stats = PartnerUsageStat::where('partner_id', $partner->id)
            ->whereBetween('date', $dateRange)
            ->selectRaw('
                SUM(api_calls) as total_api_calls,
                SUM(translations_count) as total_translations,
                SUM(characters_translated) as total_characters,
                SUM(revenue_generated) as total_revenue,
                SUM(commission_earned) as total_commission
            ')
            ->first();

        return [
            'api_calls' => $stats->total_api_calls ?? 0,
            'translations' => $stats->total_translations ?? 0,
            'characters' => $stats->total_characters ?? 0,
            'revenue' => $stats->total_revenue ?? 0,
            'commission' => $stats->total_commission ?? 0,
        ];
    }

    /**
     * Get usage analytics with daily breakdown
     */
    protected function getUsageAnalytics(Partner $partner, array $dateRange): array
    {
        $dailyStats = PartnerUsageStat::where('partner_id', $partner->id)
            ->whereBetween('date', $dateRange)
            ->orderBy('date')
            ->get();

        $subscription = $partner->activeSubscription;

        return [
            'quota' => $subscription?->monthly_quota ?? 0,
            'used' => $dailyStats->sum('translations_count'),
            'remaining' => max(0, ($subscription?->monthly_quota ?? 0) - $dailyStats->sum('translations_count')),
            'daily_breakdown' => $dailyStats->map(fn($stat) => [
                'date' => $stat->date,
                'translations' => $stat->translations_count,
                'api_calls' => $stat->api_calls,
                'characters' => $stat->characters_translated,
            ]),
        ];
    }

    /**
     * Get revenue analytics
     */
    protected function getRevenueAnalytics(Partner $partner, array $dateRange): array
    {
        $commissions = PartnerCommission::where('partner_id', $partner->id)
            ->whereBetween('created_at', $dateRange)
            ->selectRaw('
                status,
                COUNT(*) as count,
                SUM(commission_amount) as total
            ')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        return [
            'pending' => [
                'count' => $commissions->get('pending')?->count ?? 0,
                'amount' => $commissions->get('pending')?->total ?? 0,
            ],
            'approved' => [
                'count' => $commissions->get('approved')?->count ?? 0,
                'amount' => $commissions->get('approved')?->total ?? 0,
            ],
            'paid' => [
                'count' => $commissions->get('paid')?->count ?? 0,
                'amount' => $commissions->get('paid')?->total ?? 0,
            ],
            'total_earned' => $commissions->sum('total'),
        ];
    }

    /**
     * Get API analytics
     */
    protected function getApiAnalytics(Partner $partner, array $dateRange): array
    {
        $apiKeys = $partner->apiKeys;
        
        $logs = PartnerApiLog::whereIn('api_key_id', $apiKeys->pluck('id'))
            ->whereBetween('created_at', $dateRange)
            ->selectRaw('
                COUNT(*) as total_requests,
                SUM(CASE WHEN status_code >= 200 AND status_code < 300 THEN 1 ELSE 0 END) as successful,
                SUM(CASE WHEN status_code >= 400 THEN 1 ELSE 0 END) as failed,
                AVG(response_time) as avg_response_time
            ')
            ->first();

        $endpointStats = PartnerApiLog::whereIn('api_key_id', $apiKeys->pluck('id'))
            ->whereBetween('created_at', $dateRange)
            ->selectRaw('endpoint, COUNT(*) as count')
            ->groupBy('endpoint')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return [
            'total_requests' => $logs->total_requests ?? 0,
            'successful' => $logs->successful ?? 0,
            'failed' => $logs->failed ?? 0,
            'success_rate' => $logs->total_requests > 0 
                ? round(($logs->successful / $logs->total_requests) * 100, 2) 
                : 0,
            'avg_response_time' => round($logs->avg_response_time ?? 0, 2),
            'top_endpoints' => $endpointStats,
        ];
    }

    /**
     * Get trend data
     */
    protected function getTrends(Partner $partner, array $dateRange): array
    {
        $stats = PartnerUsageStat::where('partner_id', $partner->id)
            ->whereBetween('date', $dateRange)
            ->orderBy('date')
            ->get();

        return [
            'labels' => $stats->pluck('date')->map(fn($date) => Carbon::parse($date)->format('M d'))->toArray(),
            'translations' => $stats->pluck('translations_count')->toArray(),
            'revenue' => $stats->pluck('revenue_generated')->map(fn($v) => round($v, 2))->toArray(),
            'api_calls' => $stats->pluck('api_calls')->toArray(),
        ];
    }

    /**
     * Get top language pairs
     */
    protected function getTopLanguages(Partner $partner, array $dateRange): array
    {
        // This would require additional tracking in the system
        // For now, return mock data structure
        return [
            ['from' => 'en', 'to' => 'ar', 'count' => 0],
            ['from' => 'ar', 'to' => 'en', 'count' => 0],
            ['from' => 'en', 'to' => 'fr', 'count' => 0],
        ];
    }

    /**
     * Get performance metrics
     */
    protected function getPerformanceMetrics(Partner $partner, array $dateRange): array
    {
        $stats = PartnerUsageStat::where('partner_id', $partner->id)
            ->whereBetween('date', $dateRange)
            ->get();

        $avgDaily = $stats->avg('translations_count') ?? 0;
        $peakDay = $stats->sortByDesc('translations_count')->first();

        return [
            'avg_daily_translations' => round($avgDaily, 0),
            'peak_day' => [
                'date' => $peakDay?->date,
                'count' => $peakDay?->translations_count ?? 0,
            ],
            'avg_characters_per_translation' => $stats->sum('translations_count') > 0
                ? round($stats->sum('characters_translated') / $stats->sum('translations_count'), 0)
                : 0,
        ];
    }

    /**
     * Get date range based on period
     */
    protected function getDateRange(string $period): array
    {
        return match($period) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'week' => [now()->startOfWeek(), now()->endOfWeek()],
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
            'quarter' => [now()->startOfQuarter(), now()->endOfQuarter()],
            'year' => [now()->startOfYear(), now()->endOfYear()],
            default => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }

    /**
     * Export analytics to CSV
     */
    public function exportToCsv(Partner $partner, string $period): string
    {
        $analytics = $this->getAnalytics($partner, $period);
        $filename = storage_path("app/analytics_{$partner->id}_" . time() . ".csv");

        $fp = fopen($filename, 'w');

        // Overview
        fputcsv($fp, ['Overview']);
        fputcsv($fp, ['Metric', 'Value']);
        foreach ($analytics['overview'] as $key => $value) {
            fputcsv($fp, [ucfirst(str_replace('_', ' ', $key)), $value]);
        }

        fputcsv($fp, []);

        // Daily breakdown
        fputcsv($fp, ['Daily Usage']);
        fputcsv($fp, ['Date', 'Translations', 'API Calls', 'Characters']);
        foreach ($analytics['usage']['daily_breakdown'] as $day) {
            fputcsv($fp, [
                $day['date'],
                $day['translations'],
                $day['api_calls'],
                $day['characters'],
            ]);
        }

        fclose($fp);

        return $filename;
    }
}
