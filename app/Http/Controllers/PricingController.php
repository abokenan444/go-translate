<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Governance\GovernanceService;
use App\Models\SubscriptionPlan;

class PricingController extends Controller
{
    public function __construct(protected GovernanceService $gov = new GovernanceService()) {}
    /**
     * Display pricing page
     */
    public function index()
    {
        $plans = SubscriptionPlan::where('is_active', 1)
            ->orderBy('sort_order')
            ->get();
        
        \Log::info('Pricing Plans Query', [
            'count' => $plans->count(),
            'plans' => $plans->toArray()
        ]);
        
        return view('pages.pricing', compact('plans'));
    }
    
    /**
     * Get pricing data for API
     */
    public function api()
    {
        $plans = SubscriptionPlan::where('is_active', 1)
            ->orderBy('sort_order')
            ->get()
            ->map(function($plan) {
                $features = [];
                try {
                    $features = is_array($plan->features) ? $plan->features : (json_decode($plan->features ?? '[]', true) ?? []);
                } catch (\Exception $e) {
                    $features = [];
                }
                
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'slug' => $plan->slug,
                    'display_name' => $plan->name,
                    'description' => $plan->description ?? '',
                    'price' => (float)($plan->price ?? 0),
                    'currency' => $plan->currency ?? 'EUR',
                    'billing_period' => $plan->billing_period ?? 'monthly',
                    'interval' => $plan->billing_period ?? 'monthly',
                    'character_limit' => (int)($plan->tokens_limit ?? 0),
                    'max_projects' => (int)($plan->max_projects ?? 0),
                    'api_calls' => (int)($plan->tokens_limit ?? 0),
                    'team_members' => (int)($plan->max_team_members ?? 1),
                    'features' => [
                        'api_access' => (bool)$plan->api_access,
                        'priority_support' => (bool)$plan->priority_support,
                        'custom_integrations' => (bool)$plan->custom_integrations,
                    ],
                    'type' => $plan->is_custom ? 'custom' : 'subscription',
                ];
            });
        
        return response()->json([
            'success' => true,
            'plans' => $plans->values()->toArray(),
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
        
        $features = [];
        try {
            $features = json_decode($plan->features ?? '[]', true) ?? [];
        } catch (\Exception $e) {
            $features = [];
        }
        
        return response()->json([
            'success' => true,
            'plan' => [
                'id' => $plan->id,
                'name' => $plan->name,
                'slug' => $plan->slug,
                'display_name' => $plan->name,
                'description' => $plan->description ?? '',
                'price' => (float)($plan->price ?? 0),
                'currency' => $plan->currency ?? 'USD',
                'interval' => $plan->billing_period ?? 'monthly',
                'character_limit' => (int)($plan->max_translations ?? 0),
                'max_projects' => (int)($plan->max_projects ?? 0),
                'max_pages' => (int)($plan->max_pages ?? 0),
                'has_api_access' => true,
                'has_bulk_translation' => true,
                'has_advanced_features' => (bool)($plan->is_featured ?? false),
                'has_priority_support' => (bool)($plan->is_featured ?? false),
                'type' => 'subscription',
                'features' => $features,
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
            'payment_method' => 'nullable|string', // 'card' or 'free'
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
        
        // If plan requires payment (price > 0), redirect to Stripe Checkout
        if (($plan->price ?? 0) > 0 && $request->input('payment_method') !== 'free') {
            // Check if Stripe is configured
            $stripeKey = config('services.stripe.key');
            $stripeSecret = config('services.stripe.secret');
            
            if (!$stripeKey || !$stripeSecret) {
                // Stripe not configured, activate plan directly for demo
                return $this->activatePlanDirectly($plan);
            }
            
            try {
                // Create Stripe Checkout Session
                $user = \Illuminate\Support\Facades\Auth::user();
                $stripeService = app(\App\Services\Payment\StripeService::class);
                
                // Get or create Stripe price ID for this plan
                $priceId = $this->getStripePriceId($plan);
                
                if (!$priceId) {
                    // No Stripe price configured, activate directly
                    return $this->activatePlanDirectly($plan);
                }
                
                $session = $stripeService->createCheckoutSession($user, $priceId, $plan->name);
                
                return response()->json([
                    'success' => true,
                    'redirect_url' => $session['url'],
                    'session_id' => $session['session_id'],
                ]);
                
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Stripe checkout failed', [
                    'error' => $e->getMessage(),
                    'plan_id' => $plan->id,
                ]);
                
                // Fallback: activate plan directly
                return $this->activatePlanDirectly($plan);
            }
        }
        
        // Free plan or direct activation
        return $this->activatePlanDirectly($plan);
    }
    
    /**
     * Activate plan directly without payment
     */
    private function activatePlanDirectly($plan)
    {
        DB::beginTransaction();
        try {
            DB::table('user_subscriptions')
                ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                ->where('status', 'active')
                ->update(['status' => 'cancelled', 'cancelled_at' => now(), 'updated_at' => now()]);

            $startsAt = now();
            $expiresAt = null;
            $billing_period = $plan->type === 'subscription' ? 'monthly' : null;
            if ($billing_period === 'monthly') {
                $expiresAt = now()->addMonth();
            } elseif ($billing_period === 'yearly') {
                $expiresAt = now()->addYear();
            }

            DB::table('user_subscriptions')->insert([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'pricing_plan_id' => $plan->id,
                'status' => 'active',
                'starts_at' => $startsAt,
                'expires_at' => $expiresAt,
                'current_period_start' => $startsAt->format('Y-m-d'),
                'current_period_end' => $expiresAt ? $expiresAt->format('Y-m-d') : null,
                'current_balance' => (int)($plan->daily_translation_limit ?? 0),
                'credit_balance' => 0,
                'monthly_usage_count' => 0,
                'total_usage_count' => 0,
                'auto_recharge' => false,
                'auto_recharge_amount' => null,
                'auto_recharge_threshold' => null,
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
     * Get Stripe Price ID for plan
     */
    private function getStripePriceId($plan)
    {
        // Map plan slugs to Stripe price IDs from config
        $priceMap = [
            'basic' => config('services.stripe.prices.basic'),
            'pro' => config('services.stripe.prices.pro'),
            'professional' => config('services.stripe.prices.pro'),
            'enterprise' => config('services.stripe.prices.enterprise'),
        ];
        
        return $priceMap[$plan->slug] ?? null;
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
