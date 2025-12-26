<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $partner = Partner::where('user_id', $user->id)->with(['activeSubscription', 'apiKeys', 'projects'])->first();
        
        if (!$partner) {
            return redirect()->route('partners')->with('error', 'Partner account not found');
        }

        $stats = $this->getStats($partner);
        $usage = $this->getUsageStats($partner);
        $config = $this->getPartnerConfig($partner->partner_type);
        
        // Load type-specific dashboard
        $viewMap = [
            'law_firm' => 'partner.dashboards.law-firm',
            'translation_agency' => 'partner.dashboards.translation-agency',
            'university' => 'partner.dashboards.university',
            'corporate' => 'partner.dashboards.corporate',
            'certified_translator' => 'partner.dashboards.certified-translator',
        ];
        
        $view = $viewMap[$partner->partner_type] ?? 'partner.dashboards.default';
        
        // Get type-specific data
        $typeSpecificData = $this->getTypeSpecificData($partner);
        
        return view($view, array_merge([
            'partner' => $partner,
            'stats' => $stats,
            'usage' => $usage,
            'config' => $config,
        ], $typeSpecificData));
    }

    public function apiKeys()
    {
        $user = Auth::user();
        $partner = Partner::where('user_id', $user->id)->with('apiKeys')->first();
        
        if (!$partner) {
            return redirect()->route('partners')->with('error', 'Partner account not found');
        }

        return view('partner.api-keys', compact('partner'));
    }

    public function earnings()
    {
        $user = Auth::user();
        $partner = Partner::where('user_id', $user->id)
            ->with(['commissions' => function($q) {
                $q->orderBy('created_at', 'desc');
            }])
            ->first();
        
        if (!$partner) {
            return redirect()->route('partners')->with('error', 'Partner account not found');
        }

        $totalEarnings = $partner->getTotalEarnings();
        $pendingEarnings = $partner->getPendingEarnings();
        $paidEarnings = $partner->commissions()->where('status', 'paid')->sum('commission_amount');
        
        return view('partner.earnings', compact('partner', 'totalEarnings', 'pendingEarnings', 'paidEarnings'));
    }

    public function subscription()
    {
        $user = Auth::user();
        $partner = Partner::where('user_id', $user->id)->with('activeSubscription')->first();
        
        if (!$partner) {
            return redirect()->route('partners')->with('error', 'Partner account not found');
        }

        return view('partner.subscription', compact('partner'));
    }

    private function getStats($partner)
    {
        $currentMonth = now()->startOfMonth();
        
        $monthlyStats = $partner->usageStats()
            ->where('date', '>=', $currentMonth)
            ->selectRaw('
                SUM(api_calls) as total_api_calls,
                SUM(translations_count) as total_translations,
                SUM(characters_translated) as total_characters,
                SUM(revenue_generated) as total_revenue,
                SUM(commission_earned) as total_commission
            ')
            ->first();

        return [
            'total_earnings' => $partner->getTotalEarnings(),
            'pending_earnings' => $partner->getPendingEarnings(),
            'monthly_translations' => $monthlyStats->total_translations ?? 0,
            'monthly_revenue' => $monthlyStats->total_revenue ?? 0,
            'monthly_commission' => $monthlyStats->total_commission ?? 0,
            'api_calls' => $monthlyStats->total_api_calls ?? 0,
            'total_projects' => $partner->projects()->count(),
            'active_projects' => $partner->projects()->where('status', 'active')->count(),
        ];
    }

    private function getUsageStats($partner)
    {
        $subscription = $partner->activeSubscription;
        $currentMonth = now()->startOfMonth();
        
        $used = $partner->usageStats()
            ->where('date', '>=', $currentMonth)
            ->sum('translations_count');
        
        return [
            'quota' => $subscription?->monthly_quota ?? 0,
            'used' => $used,
            'remaining' => max(0, ($subscription?->monthly_quota ?? 0) - $used),
            'percentage' => $subscription && $subscription->monthly_quota > 0 
                ? round(($used / $subscription->monthly_quota) * 100, 1) 
                : 0,
        ];
    }

    private function getTypeSpecificData($partner)
    {
        switch ($partner->partner_type) {
            case 'law_firm':
                return [
                    'recentCases' => $partner->projects()
                        ->where('metadata->type', 'case')
                        ->latest()
                        ->limit(5)
                        ->get()
                        ->map(function($project) {
                            return (object)[
                                'case_number' => $project->metadata['case_number'] ?? $project->name,
                                'client_name' => $project->metadata['client_name'] ?? 'N/A',
                                'status' => $project->status,
                                'created_at' => $project->created_at,
                            ];
                        }),
                    'stats' => array_merge($this->getStats($partner), [
                        'active_cases' => $partner->projects()->where('status', 'active')->count(),
                        'total_clients' => $partner->projects()->distinct('metadata->client_id')->count('metadata->client_id'),
                    ]),
                ];

            case 'translation_agency':
                return [
                    'recentOrders' => $partner->projects()
                        ->latest()
                        ->limit(10)
                        ->get(),
                    'stats' => array_merge($this->getStats($partner), [
                        'pending_orders' => $partner->projects()->where('status', 'pending')->count(),
                        'completed_orders' => $partner->projects()->where('status', 'completed')->count(),
                    ]),
                ];

            case 'university':
                return [
                    'recentProjects' => $partner->projects()->latest()->limit(5)->get(),
                    'stats' => array_merge($this->getStats($partner), [
                        'total_students' => $partner->metadata['total_students'] ?? 0,
                        'active_departments' => $partner->metadata['departments_count'] ?? 0,
                    ]),
                ];

            case 'corporate':
                return [
                    'recentProjects' => $partner->projects()->latest()->limit(5)->get(),
                    'stats' => array_merge($this->getStats($partner), [
                        'total_teams' => $partner->metadata['teams_count'] ?? 0,
                        'total_users' => $partner->metadata['users_count'] ?? 0,
                    ]),
                ];

            case 'certified_translator':
                return [
                    'recentDocuments' => [],
                    'stats' => $this->getStats($partner),
                ];

            default:
                return [];
        }
    }

    private function getPartnerConfig($type)
    {
        $configs = [
            'law_firm' => [
                'name' => 'Law Firm',
                'icon' => 'scale',
                'color' => 'blue',
                'features' => ['Legal Translation', 'Client Management', 'Case Tracking'],
            ],
            'translation_agency' => [
                'name' => 'Translation Agency',
                'icon' => 'globe',
                'color' => 'green',
                'features' => ['Order Management', 'Client Portal', 'Invoice Generation', 'Analytics'],
            ],
            'university' => [
                'name' => 'University',
                'icon' => 'academic-cap',
                'color' => 'purple',
                'features' => ['Student Management', 'Department Tracking', 'Research Translation', 'Usage Reports'],
            ],
            'corporate' => [
                'name' => 'Corporate',
                'icon' => 'building-office',
                'color' => 'indigo',
                'features' => ['Team Management', 'Budget Control', 'SSO Integration', 'Advanced Reports'],
            ],
            'certified_translator' => [
                'name' => 'Certified Translator',
                'icon' => 'document-check',
                'color' => 'amber',
                'features' => ['Document Certification', 'Seal Management', 'Print Queue'],
            ],
        ];

        return $configs[$type] ?? $configs['certified_translator'];
    }
}
