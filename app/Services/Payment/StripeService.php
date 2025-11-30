<?php

namespace App\Services\Payment;

use App\Models\Subscription;
use App\Models\PaymentTransaction;
use App\Models\User;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;

class StripeService
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * Create or retrieve Stripe customer
     */
    public function getOrCreateCustomer(User $user): string
    {
        $subscription = $user->subscription;
        
        if ($subscription && $subscription->stripe_customer_id) {
            return $subscription->stripe_customer_id;
        }

        try {
            $customer = $this->stripe->customers->create([
                'email' => $user->email,
                'name' => $user->name,
                'metadata' => [
                    'user_id' => $user->id,
                ],
            ]);

            return $customer->id;
        } catch (ApiErrorException $e) {
            Log::error('Stripe customer creation failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Create checkout session for subscription
     */
    public function createCheckoutSession(User $user, string $priceId, string $planName): array
    {
        try {
            $customerId = $this->getOrCreateCustomer($user);

            $session = $this->stripe->checkout->sessions->create([
                'customer' => $customerId,
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $priceId,
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.cancel'),
                'metadata' => [
                    'user_id' => $user->id,
                    'plan_name' => $planName,
                ],
                'subscription_data' => [
                    'metadata' => [
                        'user_id' => $user->id,
                        'plan_name' => $planName,
                    ],
                ],
            ]);

            return [
                'session_id' => $session->id,
                'url' => $session->url,
            ];
        } catch (ApiErrorException $e) {
            Log::error('Stripe checkout session creation failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Handle successful checkout session
     */
    public function handleCheckoutSuccess(string $sessionId): ?Subscription
    {
        try {
            $session = $this->stripe->checkout->sessions->retrieve($sessionId, [
                'expand' => ['subscription']
            ]);

            if ($session->payment_status !== 'paid') {
                return null;
            }

            $userId = $session->metadata->user_id;
            $planName = $session->metadata->plan_name;
            $stripeSubscription = $session->subscription;

            $tokensLimit = $this->getTokensLimitForPlan($planName);

            $subscription = Subscription::updateOrCreate(
                ['user_id' => $userId],
                [
                    'stripe_subscription_id' => $stripeSubscription->id,
                    'stripe_customer_id' => $session->customer,
                    'stripe_price_id' => $stripeSubscription->items->data[0]->price->id,
                    'plan_name' => $planName,
                    'status' => $stripeSubscription->status,
                    'tokens_limit' => $tokensLimit,
                    'tokens_used' => 0,
                    'current_period_start' => now()->createFromTimestamp($stripeSubscription->current_period_start),
                    'current_period_end' => now()->createFromTimestamp($stripeSubscription->current_period_end),
                ]
            );

            // Record transaction
            PaymentTransaction::create([
                'user_id' => $userId,
                'subscription_id' => $subscription->id,
                'stripe_payment_intent_id' => $session->payment_intent,
                'amount' => $session->amount_total / 100,
                'currency' => $session->currency,
                'status' => 'succeeded',
                'type' => 'subscription',
                'description' => "Subscription: {$planName}",
            ]);

            return $subscription;
        } catch (ApiErrorException $e) {
            Log::error('Stripe checkout success handling failed', [
                'session_id' => $sessionId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription(Subscription $subscription): bool
    {
        try {
            $this->stripe->subscriptions->cancel($subscription->stripe_subscription_id);

            $subscription->update([
                'status' => 'canceled',
                'canceled_at' => now(),
            ]);

            return true;
        } catch (ApiErrorException $e) {
            Log::error('Stripe subscription cancellation failed', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Resume subscription
     */
    public function resumeSubscription(Subscription $subscription): bool
    {
        try {
            $this->stripe->subscriptions->update($subscription->stripe_subscription_id, [
                'cancel_at_period_end' => false,
            ]);

            $subscription->update([
                'status' => 'active',
                'canceled_at' => null,
            ]);

            return true;
        } catch (ApiErrorException $e) {
            Log::error('Stripe subscription resume failed', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get tokens limit based on plan
     */
    protected function getTokensLimitForPlan(string $planName): int
    {
        return match($planName) {
            'basic' => 100000,
            'pro' => 500000,
            'enterprise' => -1, // Unlimited
            default => 10000,
        };
    }

    /**
     * Create portal session for managing subscription
     */
    public function createPortalSession(User $user, string $returnUrl): string
    {
        try {
            $customerId = $user->subscription->stripe_customer_id ?? $this->getOrCreateCustomer($user);

            $session = $this->stripe->billingPortal->sessions->create([
                'customer' => $customerId,
                'return_url' => $returnUrl,
            ]);

            return $session->url;
        } catch (ApiErrorException $e) {
            Log::error('Stripe portal session creation failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
