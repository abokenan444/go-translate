<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;

class MinutesBillingController extends Controller
{
    public function createCheckout(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'package_id' => 'required|string',
        ]);

        $packages = collect(config('livecall.minute_packages'));
        $pkg = $packages->firstWhere('id', $data['package_id']);
        abort_unless($pkg, 422, 'Invalid package');

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = CheckoutSession::create([
            'mode' => 'payment',
            'customer_email' => $user->email,
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => (int) round($pkg['price_eur'] * 100),
                    'product_data' => [
                        'name' => "Cultural Translate - Minutes Package ({$pkg['minutes']} minutes)",
                    ],
                ],
            ]],
            'success_url' => config('app.url') . '/billing/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => config('app.url') . '/billing/cancelled',
            'metadata' => [
                'user_id' => (string)$user->id,
                'package_id' => $pkg['id'],
                'minutes' => (string)$pkg['minutes'],
            ],
        ]);

        return response()->json([
            'checkout_url' => $session->url,
        ]);
    }

    // Show available packages
    public function packages()
    {
        return response()->json(config('livecall.minute_packages'));
    }
}
