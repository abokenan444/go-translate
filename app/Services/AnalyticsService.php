<?php

namespace App\Services;

use App\Models\User;
use App\Models\Translation;
use App\Models\OfficialDocument;
use App\Models\Partner;
use App\Models\Affiliate;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Advanced Analytics Service
 * Provides comprehensive analytics data for dashboard visualizations
 */
class AnalyticsService
{
    /**
     * Get overview statistics
     */
    public function getOverviewStats(): array
    {
        return [
            'total_users' => User::count(),
            'total_translations' => Translation::count(),
            'total_documents' => OfficialDocument::count(),
            'total_partners' => Partner::where('is_certified', true)->count(),
            'total_affiliates' => Affiliate::where('status', 'active')->count(),
            'total_companies' => Company::where('status', 'active')->count(),
            'total_revenue' => OfficialDocument::where('payment_status', 'paid')->sum('amount'),
            'monthly_revenue' => OfficialDocument::where('payment_status', 'paid')
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->sum('amount'),
        ];
    }

    /**
     * Get revenue data for chart (last 12 months)
     */
    public function getRevenueChartData(): array
    {
        $data = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = OfficialDocument::where('payment_status', 'paid')
                ->whereMonth('paid_at', $date->month)
                ->whereYear('paid_at', $date->year)
                ->sum('amount');
            
            $data[] = [
                'month' => $date->format('M Y'),
                'revenue' => (float) $revenue,
            ];
        }

        return $data;
    }

    /**
     * Get user registration data for chart (last 12 months)
     */
    public function getUserRegistrationChartData(): array
    {
        $data = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = User::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            $data[] = [
                'month' => $date->format('M Y'),
                'users' => $count,
            ];
        }

        return $data;
    }

    /**
     * Get translation volume data (last 12 months)
     */
    public function getTranslationVolumeChartData(): array
    {
        $data = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Translation::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            $data[] = [
                'month' => $date->format('M Y'),
                'translations' => $count,
            ];
        }

        return $data;
    }

    /**
     * Get document status distribution
     */
    public function getDocumentStatusDistribution(): array
    {
        $statuses = OfficialDocument::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $data = [];
        foreach ($statuses as $status) {
            $data[] = [
                'status' => ucfirst($status->status),
                'count' => $status->count,
            ];
        }

        return $data;
    }

    /**
     * Get top languages by translation count
     */
    public function getTopLanguages(int $limit = 10): array
    {
        $sourceLanguages = Translation::select('source_language', DB::raw('count(*) as count'))
            ->groupBy('source_language')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get();

        $data = [];
        foreach ($sourceLanguages as $lang) {
            $data[] = [
                'language' => $lang->source_language,
                'count' => $lang->count,
            ];
        }

        return $data;
    }

    /**
     * Get top document types
     */
    public function getTopDocumentTypes(int $limit = 10): array
    {
        $types = OfficialDocument::select('document_type', DB::raw('count(*) as count'))
            ->groupBy('document_type')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get();

        $data = [];
        foreach ($types as $type) {
            $data[] = [
                'type' => ucfirst(str_replace('_', ' ', $type->document_type)),
                'count' => $type->count,
            ];
        }

        return $data;
    }

    /**
     * Get partner performance data
     */
    public function getPartnerPerformance(): array
    {
        $partners = Partner::where('is_certified', true)
            ->withCount('assignedDocuments')
            ->orderBy('assigned_documents_count', 'desc')
            ->limit(10)
            ->get();

        $data = [];
        foreach ($partners as $partner) {
            $data[] = [
                'name' => $partner->company_name,
                'documents' => $partner->assigned_documents_count,
            ];
        }

        return $data;
    }

    /**
     * Get affiliate performance data
     */
    public function getAffiliatePerformance(): array
    {
        $affiliates = Affiliate::where('status', 'active')
            ->orderBy('total_earnings', 'desc')
            ->limit(10)
            ->get();

        $data = [];
        foreach ($affiliates as $affiliate) {
            $data[] = [
                'name' => $affiliate->user->name ?? 'Unknown',
                'earnings' => (float) $affiliate->total_earnings,
                'referrals' => $affiliate->total_referrals,
            ];
        }

        return $data;
    }

    /**
     * Get shipping statistics
     */
    public function getShippingStats(): array
    {
        return [
            'total_physical_copies' => OfficialDocument::where('physical_copy_requested', true)->count(),
            'pending_shipment' => OfficialDocument::where('shipping_status', 'pending')->count(),
            'in_transit' => OfficialDocument::where('shipping_status', 'shipped')->count(),
            'delivered' => OfficialDocument::where('shipping_status', 'delivered')->count(),
            'total_shipping_revenue' => OfficialDocument::where('physical_copy_requested', true)
                ->sum('physical_copy_price'),
        ];
    }

    /**
     * Get daily activity data (last 30 days)
     */
    public function getDailyActivityData(): array
    {
        $data = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            
            $translations = Translation::whereDate('created_at', $date->toDateString())->count();
            $documents = OfficialDocument::whereDate('created_at', $date->toDateString())->count();
            $users = User::whereDate('created_at', $date->toDateString())->count();
            
            $data[] = [
                'date' => $date->format('M d'),
                'translations' => $translations,
                'documents' => $documents,
                'users' => $users,
            ];
        }

        return $data;
    }

    /**
     * Get conversion funnel data
     */
    public function getConversionFunnelData(): array
    {
        $totalVisitors = User::count(); // Simplified - in production, track actual visitors
        $registered = User::count();
        $requestedTranslation = Translation::distinct('user_id')->count('user_id');
        $paid = OfficialDocument::where('payment_status', 'paid')->distinct('user_id')->count('user_id');
        $completed = OfficialDocument::where('status', 'completed')->distinct('user_id')->count('user_id');

        return [
            ['stage' => 'Visitors', 'count' => $totalVisitors],
            ['stage' => 'Registered', 'count' => $registered],
            ['stage' => 'Requested Translation', 'count' => $requestedTranslation],
            ['stage' => 'Paid', 'count' => $paid],
            ['stage' => 'Completed', 'count' => $completed],
        ];
    }

    /**
     * Get average processing time
     */
    public function getAverageProcessingTime(): array
    {
        $documents = OfficialDocument::whereNotNull('created_at')
            ->whereNotNull('updated_at')
            ->where('status', 'completed')
            ->get();

        if ($documents->isEmpty()) {
            return [
                'average_hours' => 0,
                'fastest_hours' => 0,
                'slowest_hours' => 0,
            ];
        }

        $times = [];
        foreach ($documents as $doc) {
            $times[] = $doc->created_at->diffInHours($doc->updated_at);
        }

        return [
            'average_hours' => round(array_sum($times) / count($times), 2),
            'fastest_hours' => min($times),
            'slowest_hours' => max($times),
        ];
    }

    /**
     * Get real-time statistics (last 24 hours)
     */
    public function getRealTimeStats(): array
    {
        $last24Hours = now()->subHours(24);

        return [
            'new_users' => User::where('created_at', '>=', $last24Hours)->count(),
            'new_translations' => Translation::where('created_at', '>=', $last24Hours)->count(),
            'new_documents' => OfficialDocument::where('created_at', '>=', $last24Hours)->count(),
            'revenue_24h' => OfficialDocument::where('payment_status', 'paid')
                ->where('paid_at', '>=', $last24Hours)
                ->sum('amount'),
        ];
    }

    /**
     * Get comprehensive dashboard data
     */
    public function getDashboardData(): array
    {
        return [
            'overview' => $this->getOverviewStats(),
            'revenue_chart' => $this->getRevenueChartData(),
            'user_registration_chart' => $this->getUserRegistrationChartData(),
            'translation_volume_chart' => $this->getTranslationVolumeChartData(),
            'document_status' => $this->getDocumentStatusDistribution(),
            'top_languages' => $this->getTopLanguages(),
            'top_document_types' => $this->getTopDocumentTypes(),
            'partner_performance' => $this->getPartnerPerformance(),
            'affiliate_performance' => $this->getAffiliatePerformance(),
            'shipping_stats' => $this->getShippingStats(),
            'daily_activity' => $this->getDailyActivityData(),
            'conversion_funnel' => $this->getConversionFunnelData(),
            'processing_time' => $this->getAverageProcessingTime(),
            'realtime' => $this->getRealTimeStats(),
        ];
    }
}
