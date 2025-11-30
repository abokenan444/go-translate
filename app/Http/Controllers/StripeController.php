<?php

namespace App\Http\Controllers;

use App\Services\Payment\StripeService;
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
        $plans = [
            'basic' => [
                'name' => 'Basic',
                'price' => 29,
                'price_id' => config('services.stripe.prices.basic'),
                'tokens' => '100K',
                'features' => [
                    '100,000 translation tokens/month',
                    'Basic cultural adaptation',
                    'Email support',
                    'API access',
                ],
            ],
            'pro' => [
                'name' => 'Professional',
                'price' => 99,
                'price_id' => config('services.stripe.prices.pro'),
                'tokens' => '500K',
                'features' => [
                    '500,000 translation tokens/month',
                    'Advanced cultural intelligence',
                    'Industry-specific vocabulary',
                    'Priority support',
                    'Custom glossaries',
                    'Team collaboration',
                ],
            ],
            'enterprise' => [
                'name' => 'Enterprise',
                'price' => 299,
                'price_id' => config('services.stripe.prices.enterprise'),
                'tokens' => 'Unlimited',
                'features' => [
                    'Unlimited translation tokens',
                    'Full cultural AI suite',
                    'Dedicated account manager',
                    '24/7 priority support',
                    'Custom integrations',
                    'SLA guarantee',
                    'Advanced analytics',
                ],
            ],
        ];

        return view('stripe.pricing', compact('plans'));
    }

    /**
     * Create checkout session
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:basic,pro,enterprise',
        ]);

        $user = Auth::user();
        $plan = $request->plan;
        $priceId = config("services.stripe.prices.{$plan}");

        if (!$priceId) {
            return back()->with('error', 'Invalid plan selected');
        }

        try {
            $session = $this->stripeService->createCheckoutSession($user, $priceId, $plan);
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
