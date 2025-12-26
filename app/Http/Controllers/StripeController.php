<?php

namespace App\Http\Controllers;

use App\Services\Payment\StripeService;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
    protected StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Show pricing plans
     */
    public function pricing()
    {
        $plans = SubscriptionPlan::where('is_active', 1)
            ->orderBy('sort_order')
            ->get()
            ->map(function($plan) {
                $features = [];
                
                // Handle features - could be JSON string or already decoded array
                if (is_array($plan->features)) {
                    $features = $plan->features;
                } elseif (is_string($plan->features)) {
                    try {
                        $features = json_decode($plan->features, true) ?? [];
                    } catch (\Exception $e) {
                        $features = [];
                    }
                }
                
                // Build features list
                $featuresList = [];
                if ($plan->tokens_limit) {
                    $tokens = $plan->tokens_limit >= 1000000 
                        ? number_format($plan->tokens_limit / 1000000, 1) . 'M' 
                        : number_format($plan->tokens_limit / 1000) . 'K';
                    $featuresList[] = $tokens . ' translation tokens/month';
                }
                if ($plan->api_access) {
                    $featuresList[] = 'API access';
                }
                if ($plan->priority_support) {
                    $featuresList[] = 'Priority support';
                }
                if ($plan->custom_integrations) {
                    $featuresList[] = 'Custom integrations';
                }
                if ($plan->max_team_members) {
                    $featuresList[] = 'Up to ' . $plan->max_team_members . ' team members';
                }
                if ($plan->max_projects) {
                    $featuresList[] = 'Up to ' . $plan->max_projects . ' projects';
                }
                
                // Add custom features from JSON if any
                if (is_array($features)) {
                    $featuresList = array_merge($featuresList, $features);
                }
                
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'slug' => $plan->slug,
                    'price' => $plan->price,
                    'currency' => $plan->currency ?? 'EUR',
                    'price_id' => null, // Will be created dynamically
                    'tokens' => $plan->tokens_limit >= 999999999 ? 'Unlimited' : 
                        ($plan->tokens_limit >= 1000000 ? 
                            number_format($plan->tokens_limit / 1000000, 1) . 'M' : 
                            number_format($plan->tokens_limit / 1000) . 'K'),
                    'features' => $featuresList,
                    'is_popular' => $plan->slug === 'pro' || $plan->slug === 'professional',
                ];
            })
            ->toArray();

        return view('stripe.pricing', compact('plans'));
    }

    /**
     * Create checkout session
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:basic,pro,professional,enterprise',
        ]);

        $user = Auth::user();
        $planSlug = $request->plan;
        
        // Map 'pro' to 'professional' for compatibility
        if ($planSlug === 'pro') {
            $planSlug = 'professional';
        }
        
        // Try to get price from config first
        $priceId = config("services.stripe.prices.{$planSlug}") ?: config("services.stripe.prices.{$request->plan}");
        
        // If not in config, try database
        if (!$priceId) {
            $dbPlan = \App\Models\SubscriptionPlan::where('slug', $planSlug)->first();
            if ($dbPlan && $dbPlan->stripe_price_id) {
                $priceId = $dbPlan->stripe_price_id;
            }
        }

        if (!$priceId) {
            // If still no price ID, show a helpful message
            return back()->with('error', 'Stripe price not configured for this plan. Please contact support or configure STRIPE_PRICE_' . strtoupper($planSlug) . ' in environment.');
        }

        try {
            $session = $this->stripeService->createCheckoutSession($user, $priceId, $planSlug);
            return redirect($session['url']);
        } catch (\Exception $e) {
            Log::error('Checkout failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Unable to process checkout. Please try again.');
        }
    }

    /**
     * Handle successful payment
     */
    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('dashboard')->with('error', 'Invalid session');
        }

        try {
            $subscription = $this->stripeService->handleCheckoutSuccess($sessionId);

            if ($subscription) {
                return redirect()->route('dashboard')
                    ->with('success', 'Subscription activated successfully!');
            }

            return redirect()->route('dashboard')
                ->with('error', 'Payment verification failed. Please contact support.');
        } catch (\Exception $e) {
            Log::error('Payment success handling failed', ['error' => $e->getMessage()]);
            return redirect()->route('dashboard')
                ->with('error', 'An error occurred. Please contact support.');
        }
    }

    /**
     * Handle canceled payment
     */
    public function cancel()
    {
        return redirect()->route('stripe.pricing')
            ->with('info', 'Payment canceled. You can try again anytime.');
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription()
    {
        $user = Auth::user();
        $subscription = $user->subscription;

        if (!$subscription || !$subscription->isActive()) {
            return back()->with('error', 'No active subscription found');
        }

        try {
            $this->stripeService->cancelSubscription($subscription);
            return back()->with('success', 'Subscription canceled successfully');
        } catch (\Exception $e) {
            Log::error('Subscription cancellation failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Unable to cancel subscription. Please try again.');
        }
    }

    /**
     * Resume subscription
     */
    public function resumeSubscription()
    {
        $user = Auth::user();
        $subscription = $user->subscription;

        if (!$subscription || $subscription->status !== 'canceled') {
            return back()->with('error', 'No canceled subscription found');
        }

        try {
            $this->stripeService->resumeSubscription($subscription);
            return back()->with('success', 'Subscription resumed successfully');
        } catch (\Exception $e) {
            Log::error('Subscription resume failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Unable to resume subscription. Please try again.');
        }
    }

    /**
     * Redirect to Stripe customer portal
     */
    public function portal()
    {
        $user = Auth::user();

        try {
            $url = $this->stripeService->createPortalSession($user, route('dashboard'));
            return redirect($url);
        } catch (\Exception $e) {
            Log::error('Portal redirect failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Unable to access billing portal. Please try again.');
        }
    }
}
