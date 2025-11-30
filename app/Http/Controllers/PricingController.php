<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Governance\GovernanceService;

class PricingController extends Controller
{
    public function __construct(protected GovernanceService $gov = new GovernanceService()) {}
    /**
     * Display pricing page
     */
    public function index()
    {
        $plans = DB::table('subscription_plans')
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->get();
        
        return view('pages.pricing', compact('plans'));
    }
    
    /**
     * Get pricing data for API
     */
    public function api()
    {
        $plans = DB::table('subscription_plans')
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->get()
            ->map(function($plan) {
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'display_name' => $plan->name, // Use name as display_name
                    'description' => $plan->description ?? '',
                    'price' => $plan->price,
                    'currency' => $plan->currency ?? 'USD',
                    'interval' => $plan->billing_period ?? 'monthly',
                    'character_limit' => $plan->tokens_limit ?? 0,
                    'team_members' => $plan->max_team_members ?? 1,
                    'api_calls' => $plan->tokens_limit / 10 ?? 1000, // Estimate API calls
                    'features' => [
                        'cultural_adaptation' => true, // Default features
                        'voice_translation' => true,
                        'real_time_translation' => true,
                        'custom_integrations' => (bool) ($plan->custom_integrations ?? false),
                        'priority_support' => (bool) ($plan->priority_support ?? false),
                        'api_access' => (bool) ($plan->api_access ?? false),
                    ],
                ];
            });
        
        return response()->json([
            'success' => true,
            'plans' => $plans,
        ]);
    }
    
    /**
     * Get plan details
     */
    public function show($id)
    {
        $plan = DB::table('subscription_plans')
            ->where('id', $id)
            ->where('is_active', 1)
            ->first();
        
        if (!$plan) {
            return response()->json([
                'success' => false,
                'message' => 'Plan not found',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'plan' => [
                'id' => $plan->id,
                'name' => $plan->name,
                'display_name' => $plan->name,
                'description' => $plan->description ?? '',
                'price' => $plan->price,
                'currency' => $plan->currency ?? 'USD',
                'interval' => $plan->billing_period ?? 'monthly',
                'character_limit' => $plan->tokens_limit ?? 0,
                'team_members' => $plan->max_team_members ?? 1,
                'api_calls' => $plan->tokens_limit / 10 ?? 1000,
                'features' => [
                    'cultural_adaptation' => true,
                    'voice_translation' => true,
                    'real_time_translation' => true,
                    'custom_integrations' => (bool) ($plan->custom_integrations ?? false),
                    'priority_support' => (bool) ($plan->priority_support ?? false),
                    'api_access' => (bool) ($plan->api_access ?? false),
                ],
            ],
        ]);
    }
    
    /**
     * Subscribe/upgrade to a plan (authenticated)
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'plan' => 'nullable', // slug or id
            'plan_id' => 'nullable', // numeric id alternative
        ]);
        
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return response()->json(['success' => false, 'error' => 'Unauthenticated'], 401);
        }
        
        $planRef = $request->input('plan') ?? $request->input('plan_id');
        $plan = DB::table('subscription_plans')
            ->where(function ($q) use ($planRef) {
                if (!is_null($planRef)) {
                    $q->where('slug', $planRef);
                    if (is_numeric($planRef)) {
                        $q->orWhere('id', (int)$planRef);
                    }
                }
            })
            ->where('is_active', 1)
            ->first();
        
        if (!$plan) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or inactive plan',
            ], 422);
        }
        
        // Cancel existing active user_subscriptions and create a new one
        DB::beginTransaction();
        try {
            DB::table('user_subscriptions')
                ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                ->where('status', 'active')
                ->update(['status' => 'cancelled', 'cancelled_at' => now(), 'updated_at' => now()]);

            $startsAt = now();
            $expiresAt = null;
            if (($plan->billing_period ?? 'monthly') === 'monthly') {
                $expiresAt = now()->addMonth();
            } elseif ($plan->billing_period === 'yearly') {
                $expiresAt = now()->addYear();
            }

            DB::table('user_subscriptions')->insert([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'subscription_plan_id' => $plan->id,
                'status' => 'active',
                'tokens_used' => 0,
                'tokens_remaining' => (int)($plan->tokens_limit ?? 0),
                'starts_at' => $startsAt,
                'expires_at' => $expiresAt,
                'last_token_reset_at' => $startsAt,
                'auto_renew' => ($plan->billing_period ?? 'monthly') !== 'lifetime',
                'low_tokens_notified' => false,
                'expiry_notified' => false,
                'cancellation_reason' => null,
                'metadata' => json_encode(['upgraded_via' => 'dashboard']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            $this->gov->log(\Illuminate\Support\Facades\Auth::id(), 'subscription_changed', 'user_subscription', 0, [
                'plan_id' => $plan->id,
                'plan_slug' => $plan->slug ?? null,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => 'Subscription failed'], 500);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Subscription activated successfully',
            'plan' => $plan->slug,
        ]);
    }
    
    /**
     * Contact for custom plan
     */
    public function contactCustomPlan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'company' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);
        
        // Store contact request
        DB::table('contact_requests')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'company' => $request->company,
            'message' => $request->message,
            'type' => 'custom_plan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Thank you! We will contact you soon.',
        ]);
    }
}
