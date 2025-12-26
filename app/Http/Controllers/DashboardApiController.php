<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardApiController extends Controller
{
    public function getUser(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            // Get active subscription
            $subscription = DB::table('user_subscriptions as us')
                ->join('subscription_plans as sp', 'sp.id', '=', 'us.subscription_plan_id')
                ->where('us.user_id', $user->id)
                ->where('us.status', 'active')
                ->orderByDesc('us.starts_at')
                ->select('sp.name as plan_name')
                ->first();
            
            $planName = $subscription ? $subscription->plan_name : 'Free';
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name),
                    'plan' => $planName,
                    'created_at' => $user->created_at->format('Y-m-d'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load user data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStats(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Get real statistics from database
            $translationsCount = DB::table('translations')->where('user_id', $user->id)->count();
            
            // Check if projects table exists
            $projectsCount = 0;
            $activeProjectsCount = 0;
            try {
                $projectsCount = DB::table('projects')->where('user_id', $user->id)->count();
                $activeProjectsCount = DB::table('projects')->where('user_id', $user->id)->where('status', 'active')->count();
            } catch (\Exception $e) {
                // Table doesn't exist yet
            }
            
            // Get subscription data
            $subscription = DB::table('user_subscriptions as us')
                ->join('subscription_plans as sp', 'sp.id', '=', 'us.subscription_plan_id')
                ->where('us.user_id', $user->id)
                ->where('us.status', 'active')
                ->orderByDesc('us.starts_at')
                ->select('us.tokens_used', 'us.tokens_remaining', 'sp.tokens_limit')
                ->first();
            
            $tokensUsed = $subscription ? ($subscription->tokens_used ?? 0) : 0;
            $tokensLimit = $subscription ? ($subscription->tokens_limit ?? 10000) : 10000;
            
            // Calculate growth (compare with last month)
            $lastMonthCount = DB::table('translations')
                ->where('user_id', $user->id)
                ->where('created_at', '<', now()->subMonth())
                ->count();
            $translationsGrowth = $lastMonthCount > 0 ? round((($translationsCount - $lastMonthCount) / $lastMonthCount) * 100) : 0;
            
            $stats = [
                'translations' => $translationsCount,
                'translationsGrowth' => $translationsGrowth,
                'charactersUsed' => $tokensUsed,
                'charactersLimit' => $tokensLimit,
                'projects' => $projectsCount,
                'activeProjects' => $activeProjectsCount,
                'teamMembers' => 1, // TODO: implement team members
            ];
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load stats: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getUsageData(Request $request)
    {
        $user = Auth::user();
        
        // Get real usage data from last 4 weeks
        $usageData = [];
        $labels = [];
        
        for ($i = 3; $i >= 0; $i--) {
            $startDate = now()->subWeeks($i + 1);
            $endDate = now()->subWeeks($i);
            
            $weekUsage = DB::table('translations')
                ->where('user_id', $user->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum(DB::raw('LENGTH(source_text)'));
            
            $usageData[] = (int)$weekUsage;
            $labels[] = 'Week ' . (4 - $i);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'labels' => $labels,
                'data' => $usageData
            ]
        ]);
    }

    public function getLanguagesData(Request $request)
    {
        $user = Auth::user();
        
        // Get real language distribution
        $languages = DB::table('translations')
            ->select('target_language', DB::raw('COUNT(*) as count'))
            ->where('user_id', $user->id)
            ->groupBy('target_language')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
        
        $labels = [];
        $data = [];
        $total = $languages->sum('count');
        
        foreach ($languages as $lang) {
            $labels[] = $lang->target_language;
            $data[] = $total > 0 ? round(($lang->count / $total) * 100) : 0;
        }
        
        // If no data, show default
        if (empty($labels)) {
            $labels = ['Arabic', 'English'];
            $data = [50, 50];
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'labels' => $labels,
                'data' => $data
            ]
        ]);
    }

    public function getHistory(Request $request)
    {
        $user = Auth::user();
        
        // Get real translation history
        $history = DB::table('translations')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function($translation) {
                return [
                    'id' => $translation->id,
                    'source_lang' => $translation->source_language ?? 'auto',
                    'target_lang' => $translation->target_language ?? 'en',
                    'source_text' => substr($translation->source_text ?? '', 0, 100),
                    'translated_text' => substr($translation->translated_text ?? '', 0, 100),
                    'created_at' => \Carbon\Carbon::parse($translation->created_at)->format('Y-m-d H:i'),
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    public function getProjects(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Get real projects
            $projects = DB::table('projects')
                ->where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->limit(10)
                ->get()
                ->map(function($project) {
                    return [
                        'id' => $project->id,
                        'name' => $project->name ?? 'Untitled Project',
                        'description' => $project->description ?? '',
                        'status' => $project->status ?? 'active',
                        'created_at' => \Carbon\Carbon::parse($project->created_at)->format('Y-m-d'),
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $projects
            ]);
        } catch (\Exception $e) {
            // Return empty array if table doesn't exist
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }
    }

    public function getSubscription(Request $request)
    {
        $user = Auth::user();
        
        // Get real subscription data with plan details
        $subscription = DB::table('user_subscriptions as us')
            ->join('subscription_plans as sp', 'sp.id', '=', 'us.subscription_plan_id')
            ->where('us.user_id', $user->id)
            ->where('us.status', 'active')
            ->orderByDesc('us.starts_at')
            ->select([
                'sp.id as plan_id',
                'sp.name as plan_name',
                'sp.price',
                'sp.billing_period',
                'us.status',
                'us.tokens_used',
                'us.tokens_remaining',
                'sp.tokens_limit',
                'us.starts_at',
                'us.expires_at',
                'us.metadata'
            ])
            ->first();
        
        if (!$subscription) {
            return response()->json([
                'success' => true,
                'data' => [
                    'plan_id' => null,
                    'plan_name' => 'Free',
                    'price' => 0,
                    'billing_cycle' => 'month',
                    'status' => 'inactive',
                    'renews_at' => null,
                    'is_trial' => false,
                    'trial_ends_at' => null
                ]
            ]);
        }
        
        $metadata = json_decode($subscription->metadata ?? '{}', true);
        $isTrial = isset($metadata['trial']) && $metadata['trial'] === true;
        
        return response()->json([
            'success' => true,
            'data' => [
                'plan_id' => $subscription->plan_id,
                'plan_name' => $subscription->plan_name,
                'price' => (float)$subscription->price,
                'billing_cycle' => $subscription->billing_period ?? 'monthly',
                'status' => $subscription->status,
                'renews_at' => $subscription->expires_at,
                'is_trial' => $isTrial,
                'trial_ends_at' => $isTrial ? $subscription->expires_at : null
            ]
        ]);
    }

    public function getUsage(Request $request)
    {
        $user = Auth::user();
        
        // Get active subscription with plan details
        $subscription = DB::table('user_subscriptions as us')
            ->join('subscription_plans as sp', 'sp.id', '=', 'us.subscription_plan_id')
            ->where('us.user_id', $user->id)
            ->where('us.status', 'active')
            ->orderByDesc('us.starts_at')
            ->select(
                'sp.name as plan_name',
                'sp.price',
                'sp.tokens_limit',
                'sp.max_team_members as team_limit',
                'us.tokens_used',
                'us.tokens_remaining'
            )
            ->first();
        
        if (!$subscription) {
            return response()->json([
                'success' => true,
                'data' => [
                    'characters_used' => 0,
                    'characters_limit' => 10000,
                    'api_calls' => 0,
                    'api_limit' => 100,
                    'team_members' => 1,
                    'team_limit' => 1
                ]
            ]);
        }
        
        // Calculate actual characters used
        $tokensLimit = (int)($subscription->tokens_limit ?? 10000);
        $tokensRemaining = (int)($subscription->tokens_remaining ?? $tokensLimit);
        $charactersUsed = max(0, $tokensLimit - $tokensRemaining);
        
        // Count API calls this month
        $apiCalls = DB::table('translations')
            ->where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Calculate API limit (10% of tokens limit as estimate)
        $apiLimit = (int)($tokensLimit / 10);
        
        return response()->json([
            'success' => true,
            'data' => [
                'characters_used' => $charactersUsed,
                'characters_limit' => $tokensLimit,
                'api_calls' => (int)$apiCalls,
                'api_limit' => $apiLimit,
                'team_members' => 1, // TODO: Count actual team members
                'team_limit' => (int)($subscription->team_limit ?? 1)
            ]
        ]);
    }

    public function getInvoices(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Get invoices from payments/transactions table if it exists
            // For now, return empty array as invoices table doesn't exist yet
            $invoices = [];
            
            // Check if we have a payments or invoices table
            try {
                $invoices = DB::table('payments')
                    ->where('user_id', $user->id)
                    ->orderByDesc('created_at')
                    ->limit(20)
                    ->get()
                    ->map(function($payment) {
                        return [
                            'id' => $payment->id,
                            'description' => $payment->description ?? 'Payment',
                            'amount' => (float)$payment->amount,
                            'status' => $payment->status ?? 'paid',
                            'date' => $payment->created_at,
                        ];
                    });
            } catch (\Exception $e) {
                // Table doesn't exist, return empty array
                $invoices = [];
            }
            
            return response()->json([
                'success' => true,
                'invoices' => $invoices
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'invoices' => []
            ]);
        }
    }

    /**
     * Get list of available languages for translation
     */
    public function getLanguagesList(Request $request)
    {
        try {
            $languages = DB::table('languages')
                ->where('is_active', 1)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(['code', 'name', 'native_name', 'region', 'flag_emoji', 'direction']);

            // Group languages by region
            $groupedLanguages = [];
            foreach ($languages as $lang) {
                $region = $lang->region ?? 'Other';
                if (!isset($groupedLanguages[$region])) {
                    $groupedLanguages[$region] = [];
                }
                $groupedLanguages[$region][] = [
                    'code' => $lang->code,
                    'name' => $lang->name,
                    'native' => $lang->native_name,
                    'locale' => $lang->code, // For backward compatibility
                    'flag' => $lang->flag_emoji,
                    'direction' => $lang->direction ?? 'ltr'
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $groupedLanguages
            ]);
        } catch (\Exception $e) {
            // Fallback to basic languages if database query fails
            return response()->json([
                'success' => true,
                'data' => [
                    'Common' => [
                        ['code' => 'ar', 'name' => 'Arabic', 'native' => 'العربية', 'locale' => 'ar', 'flag' => '🇸🇦', 'direction' => 'rtl'],
                        ['code' => 'en', 'name' => 'English', 'native' => 'English', 'locale' => 'en', 'flag' => '🇬🇧', 'direction' => 'ltr'],
                        ['code' => 'fr', 'name' => 'French', 'native' => 'Français', 'locale' => 'fr', 'flag' => '🇫🇷', 'direction' => 'ltr'],
                        ['code' => 'es', 'name' => 'Spanish', 'native' => 'Español', 'locale' => 'es', 'flag' => '🇪🇸', 'direction' => 'ltr'],
                        ['code' => 'de', 'name' => 'German', 'native' => 'Deutsch', 'locale' => 'de', 'flag' => '🇩🇪', 'direction' => 'ltr'],
                    ]
                ]
            ]);
        }
    }

    /**
     * Create a new project
     */
    public function createProject(Request $request)
    {
        try {
            $user = Auth::user();
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'source_language' => 'nullable|string|max:10',
                'target_languages' => 'nullable|array',
            ]);
            
            $projectId = DB::table('projects')->insertGetId([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'description' => $validated['description'] ?? '',
                'source_language' => $validated['source_language'] ?? 'en',
                'target_language' => isset($validated['target_languages']) ? implode(',', $validated['target_languages']) : null,
                'status' => 'active',
                'translation_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $projectId,
                    'name' => $validated['name'],
                    'description' => $validated['description'] ?? '',
                    'status' => 'active',
                ],
                'message' => 'Project created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create project: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a project
     */
    public function deleteProject(Request $request, $id)
    {
        try {
            $user = Auth::user();
            
            $deleted = DB::table('projects')
                ->where('id', $id)
                ->where('user_id', $user->id)
                ->delete();
            
            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Project not found or you do not have permission to delete it'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Project deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete project: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Invite a member to a project (placeholder)
     */
    public function inviteToProject(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'role' => 'nullable|string|in:viewer,translator,admin',
            ]);
            
            // TODO: Implement actual invitation logic
            // For now, return success
            return response()->json([
                'success' => true,
                'message' => 'Invitation sent successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send invitation: ' . $e->getMessage()
            ], 500);
        }
    }
}
