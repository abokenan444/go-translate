<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Translation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * Display reports dashboard
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'total_translations' => Translation::count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
        ];

        return view('admin.reports.index', compact('stats'));
    }

    /**
     * Users report
     */
    public function users(Request $request)
    {
        $period = $request->get('period', '30days');
        $startDate = $this->getStartDate($period);

        $data = [
            'new_users' => User::where('created_at', '>=', $startDate)->count(),
            'active_users' => User::where('last_login_at', '>=', $startDate)->count(),
            'users_by_day' => User::where('created_at', '>=', $startDate)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'users_by_country' => User::select('country', DB::raw('COUNT(*) as count'))
                ->groupBy('country')
                ->orderByDesc('count')
                ->limit(10)
                ->get(),
        ];

        return view('admin.reports.users', compact('data', 'period'));
    }

    /**
     * Subscriptions report
     */
    public function subscriptions(Request $request)
    {
        $period = $request->get('period', '30days');
        $startDate = $this->getStartDate($period);

        $data = [
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'new_subscriptions' => Subscription::where('created_at', '>=', $startDate)->count(),
            'cancelled_subscriptions' => Subscription::where('status', 'cancelled')
                ->where('updated_at', '>=', $startDate)
                ->count(),
            'subscriptions_by_plan' => Subscription::join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
                ->select('subscription_plans.name', DB::raw('COUNT(*) as count'))
                ->where('subscriptions.status', 'active')
                ->groupBy('subscription_plans.name')
                ->get(),
            'mrr' => Subscription::where('status', 'active')
                ->join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
                ->sum('subscription_plans.price'),
        ];

        return view('admin.reports.subscriptions', compact('data', 'period'));
    }

    /**
     * Revenue report
     */
    public function revenue(Request $request)
    {
        $period = $request->get('period', '30days');
        $startDate = $this->getStartDate($period);

        $data = [
            'total_revenue' => Payment::where('status', 'completed')
                ->where('created_at', '>=', $startDate)
                ->sum('amount'),
            'revenue_by_day' => Payment::where('status', 'completed')
                ->where('created_at', '>=', $startDate)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'revenue_by_plan' => Payment::where('status', 'completed')
                ->where('payments.created_at', '>=', $startDate)
                ->join('subscriptions', 'payments.subscription_id', '=', 'subscriptions.id')
                ->join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
                ->select('subscription_plans.name', DB::raw('SUM(payments.amount) as total'))
                ->groupBy('subscription_plans.name')
                ->get(),
            'average_revenue_per_user' => Payment::where('status', 'completed')
                ->where('created_at', '>=', $startDate)
                ->avg('amount'),
        ];

        return view('admin.reports.revenue', compact('data', 'period'));
    }

    /**
     * Performance report
     */
    public function performance(Request $request)
    {
        $period = $request->get('period', '30days');
        $startDate = $this->getStartDate($period);

        $data = [
            'total_translations' => Translation::where('created_at', '>=', $startDate)->count(),
            'translations_by_day' => Translation::where('created_at', '>=', $startDate)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'translations_by_language' => Translation::where('created_at', '>=', $startDate)
                ->select('target_language', DB::raw('COUNT(*) as count'))
                ->groupBy('target_language')
                ->orderByDesc('count')
                ->limit(10)
                ->get(),
            'average_translation_time' => Translation::where('created_at', '>=', $startDate)
                ->whereNotNull('completed_at')
                ->avg(DB::raw('TIMESTAMPDIFF(SECOND, created_at, completed_at)')),
        ];

        return view('admin.reports.performance', compact('data', 'period'));
    }

    /**
     * Export report to CSV
     */
    public function export(Request $request)
    {
        $type = $request->get('type');
        $period = $request->get('period', '30days');

        // Generate CSV based on report type
        $filename = "{$type}_report_" . date('Y-m-d') . ".csv";

        return response()->streamDownload(function () use ($type, $period) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date', 'Value']); // Header

            // Add data based on report type
            // Implementation depends on report type

            fclose($handle);
        }, $filename);
    }

    /**
     * Get start date based on period
     */
    private function getStartDate($period)
    {
        return match ($period) {
            '7days' => Carbon::now()->subDays(7),
            '30days' => Carbon::now()->subDays(30),
            '90days' => Carbon::now()->subDays(90),
            '1year' => Carbon::now()->subYear(),
            default => Carbon::now()->subDays(30),
        };
    }
}
