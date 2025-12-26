<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get dashboard statistics
        $stats = $this->getDashboardStats();
        
        // Get recent activities
        $recentActivities = $this->getRecentActivities();
        
        // Get system health
        $systemHealth = $this->getSystemHealth();
        
        return view('admin.dashboard', compact('stats', 'recentActivities', 'systemHealth'));
    }

    /**
     * Get dashboard statistics.
     */
    private function getDashboardStats()
    {
        return Cache::remember('admin_dashboard_stats', 300, function () {
            return [
                'total_users' => DB::table('users')->count(),
                'total_translations' => DB::table('translations')->count() ?? 0,
                'total_documents' => DB::table('official_documents')->count() ?? 0,
                'total_revenue' => DB::table('payments')->where('status', 'completed')->sum('amount') ?? 0,
                'active_subscriptions' => DB::table('subscriptions')->where('status', 'active')->count() ?? 0,
                'pending_verifications' => DB::table('official_documents')->where('status', 'pending')->count() ?? 0,
                
                // Growth metrics (last 30 days)
                'new_users_this_month' => DB::table('users')
                    ->where('created_at', '>=', now()->subDays(30))
                    ->count(),
                'translations_this_month' => DB::table('translations')
                    ->where('created_at', '>=', now()->subDays(30))
                    ->count() ?? 0,
                'revenue_this_month' => DB::table('payments')
                    ->where('status', 'completed')
                    ->where('created_at', '>=', now()->subDays(30))
                    ->sum('amount') ?? 0,
            ];
        });
    }

    /**
     * Get recent activities.
     */
    private function getRecentActivities()
    {
        return Cache::remember('admin_recent_activities', 60, function () {
            // Placeholder - implement based on your activity log system
            return collect([
                [
                    'type' => 'user_registered',
                    'description' => 'New user registered',
                    'user' => 'John Doe',
                    'timestamp' => now()->subMinutes(5),
                ],
                [
                    'type' => 'translation_completed',
                    'description' => 'Translation completed',
                    'user' => 'Jane Smith',
                    'timestamp' => now()->subMinutes(15),
                ],
                [
                    'type' => 'payment_received',
                    'description' => 'Payment received',
                    'amount' => '$99.00',
                    'timestamp' => now()->subMinutes(30),
                ],
            ]);
        });
    }

    /**
     * Get system health status.
     */
    private function getSystemHealth()
    {
        return [
            'database' => $this->checkDatabaseHealth(),
            'redis' => $this->checkRedisHealth(),
            'storage' => $this->checkStorageHealth(),
            'queue' => $this->checkQueueHealth(),
        ];
    }

    /**
     * Check database health.
     */
    private function checkDatabaseHealth()
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'healthy', 'message' => 'Database connection OK'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Database connection failed'];
        }
    }

    /**
     * Check Redis health.
     */
    private function checkRedisHealth()
    {
        try {
            Cache::store('redis')->get('health_check');
            return ['status' => 'healthy', 'message' => 'Redis connection OK'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Redis connection failed'];
        }
    }

    /**
     * Check storage health.
     */
    private function checkStorageHealth()
    {
        try {
            $diskSpace = disk_free_space(storage_path());
            $totalSpace = disk_total_space(storage_path());
            $usedPercentage = (($totalSpace - $diskSpace) / $totalSpace) * 100;
            
            return [
                'status' => $usedPercentage < 90 ? 'healthy' : 'warning',
                'message' => sprintf('Disk usage: %.1f%%', $usedPercentage),
                'free_space' => $this->formatBytes($diskSpace),
                'total_space' => $this->formatBytes($totalSpace),
            ];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Storage check failed'];
        }
    }

    /**
     * Check queue health.
     */
    private function checkQueueHealth()
    {
        try {
            // Placeholder - implement based on your queue system
            return ['status' => 'healthy', 'message' => 'Queue is running'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Queue check failed'];
        }
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Get chart data for dashboard.
     */
    public function getChartData(Request $request)
    {
        $type = $request->input('type', 'users');
        $period = $request->input('period', '30'); // days
        
        $data = [];
        
        switch ($type) {
            case 'users':
                $data = $this->getUsersChartData($period);
                break;
            case 'translations':
                $data = $this->getTranslationsChartData($period);
                break;
            case 'revenue':
                $data = $this->getRevenueChartData($period);
                break;
        }
        
        return response()->json($data);
    }

    /**
     * Get users chart data.
     */
    private function getUsersChartData($days)
    {
        $data = DB::table('users')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return [
            'labels' => $data->pluck('date'),
            'values' => $data->pluck('count'),
        ];
    }

    /**
     * Get translations chart data.
     */
    private function getTranslationsChartData($days)
    {
        // Placeholder - implement based on your translations table
        return [
            'labels' => [],
            'values' => [],
        ];
    }

    /**
     * Get revenue chart data.
     */
    private function getRevenueChartData($days)
    {
        // Placeholder - implement based on your payments table
        return [
            'labels' => [],
            'values' => [],
        ];
    }
}
