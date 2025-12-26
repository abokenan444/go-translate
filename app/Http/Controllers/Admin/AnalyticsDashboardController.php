<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class AnalyticsDashboardController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->middleware('auth');
        $this->analyticsService = $analyticsService;
    }

    /**
     * Display main analytics dashboard
     */
    public function index()
    {
        $data = $this->analyticsService->getDashboardData();
        
        return view('admin.analytics.dashboard', $data);
    }

    /**
     * Get analytics data as JSON (for AJAX requests)
     */
    public function getData(Request $request)
    {
        $type = $request->get('type', 'overview');

        $data = match($type) {
            'overview' => $this->analyticsService->getOverviewStats(),
            'revenue' => $this->analyticsService->getRevenueChartData(),
            'users' => $this->analyticsService->getUserRegistrationChartData(),
            'translations' => $this->analyticsService->getTranslationVolumeChartData(),
            'documents' => $this->analyticsService->getDocumentStatusDistribution(),
            'languages' => $this->analyticsService->getTopLanguages(),
            'document_types' => $this->analyticsService->getTopDocumentTypes(),
            'partners' => $this->analyticsService->getPartnerPerformance(),
            'affiliates' => $this->analyticsService->getAffiliatePerformance(),
            'shipping' => $this->analyticsService->getShippingStats(),
            'daily_activity' => $this->analyticsService->getDailyActivityData(),
            'funnel' => $this->analyticsService->getConversionFunnelData(),
            'processing_time' => $this->analyticsService->getAverageProcessingTime(),
            'realtime' => $this->analyticsService->getRealTimeStats(),
            'all' => $this->analyticsService->getDashboardData(),
            default => ['error' => 'Invalid type'],
        };

        return response()->json($data);
    }

    /**
     * Export analytics data
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'json');
        $data = $this->analyticsService->getDashboardData();

        if ($format === 'json') {
            return response()->json($data);
        }

        // CSV export
        if ($format === 'csv') {
            $filename = 'analytics_' . date('Y-m-d_H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($data) {
                $file = fopen('php://output', 'w');
                
                // Write overview stats
                fputcsv($file, ['Metric', 'Value']);
                foreach ($data['overview'] as $key => $value) {
                    fputcsv($file, [ucfirst(str_replace('_', ' ', $key)), $value]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return response()->json(['error' => 'Invalid format'], 400);
    }
}
