<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\PartnerSubscription;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function index()
    {
        $partner = auth()->user()->partner;
        $currentSubscription = $partner->activeSubscription;
        
        $tiers = [
            [
                'tier' => 'basic',
                'name' => 'Basic',
                'price' => 99,
                'quota' => 1000,
                'api_calls' => 1000,
                'features' => [
                    '1,000 translations/month',
                    '1,000 API calls/month',
                    'Standard support',
                    'Basic analytics',
                ],
            ],
            [
                'tier' => 'professional',
                'name' => 'Professional',
                'price' => 299,
                'quota' => 10000,
                'api_calls' => 10000,
                'features' => [
                    '10,000 translations/month',
                    '10,000 API calls/month',
                    'White Label branding',
                    'Priority support',
                    'Advanced analytics',
                ],
                'popular' => true,
            ],
            [
                'tier' => 'enterprise',
                'name' => 'Enterprise',
                'price' => 999,
                'quota' => -1,
                'api_calls' => 100000,
                'features' => [
                    'Unlimited translations',
                    '100,000 API calls/month',
                    'White Label + Custom domain',
                    '24/7 premium support',
                    'Dedicated account manager',
                    'SSO integration',
                ],
            ],
        ];
        
        return view('partners.subscription-plans', compact('partner', 'currentSubscription', 'tiers'));
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'tier' => 'required|in:basic,professional,enterprise',
        ]);

        $partner = auth()->user()->partner;
        
        $prices = [
            'basic' => 99,
            'professional' => 299,
            'enterprise' => 999,
        ];

        $tier = $request->tier;
        $price = $prices[$tier];

        // Create Stripe checkout session
        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => ucfirst($tier) . ' Partner Subscription',
                        'description' => 'Monthly subscription for ' . $partner->company_name,
                    ],
                    'unit_amount' => $price * 100,
                    'recurring' => [
                        'interval' => 'month',
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('partner.subscription.success') . '?session_id={CHECKOUT_SESSION_ID}&tier=' . $tier,
            'cancel_url' => route('partner.subscription'),
            'client_reference_id' => $partner->id,
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $partner = auth()->user()->partner;
        $tier = $request->tier;
        
        $quotas = [
            'basic' => 1000,
            'professional' => 10000,
            'enterprise' => -1,
        ];
        
        $apiLimits = [
            'basic' => 1000,
            'professional' => 10000,
            'enterprise' => 100000,
        ];
        
        $prices = [
            'basic' => 99,
            'professional' => 299,
            'enterprise' => 999,
        ];

        // Cancel existing subscription
        if ($partner->activeSubscription) {
            $partner->activeSubscription->update(['status' => 'cancelled']);
        }

        // Create new subscription
        PartnerSubscription::create([
            'partner_id' => $partner->id,
            'subscription_tier' => $tier,
            'monthly_quota' => $quotas[$tier],
            'api_calls_limit' => $apiLimits[$tier],
            'white_label_enabled' => in_array($tier, ['professional', 'enterprise']),
            'custom_domain_enabled' => $tier === 'enterprise',
            'price' => $prices[$tier],
            'billing_cycle' => 'monthly',
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
        ]);

        return redirect()->route('partner.dashboard')
            ->with('success', 'Subscription activated successfully!');
    }
}
