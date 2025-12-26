<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\PaymentTransaction;
use App\Models\MinutesWallet;
use App\Models\MobileWalletTransaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * Handle Stripe webhook events
     */
    public function handle(Request $request)
    {
        return $this->handleWebhook($request);
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $webhookSecret
            );
        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe webhook: Invalid payload', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe webhook: Invalid signature', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        try {
            switch ($event->type) {
                case 'checkout.session.completed':
                    $this->handleCheckoutCompleted($event->data->object);
                    break;

                case 'customer.subscription.created':
                    $this->handleSubscriptionCreated($event->data->object);
                    break;

                case 'customer.subscription.updated':
                    $this->handleSubscriptionUpdated($event->data->object);
                    break;

                case 'customer.subscription.deleted':
                    $this->handleSubscriptionDeleted($event->data->object);
                    break;

                case 'invoice.payment_succeeded':
                    $this->handleInvoicePaymentSucceeded($event->data->object);
                    break;

                case 'invoice.payment_failed':
                    $this->handleInvoicePaymentFailed($event->data->object);
                    break;

                case 'customer.subscription.trial_will_end':
                    $this->handleTrialWillEnd($event->data->object);
                    break;

                default:
                    Log::info('Stripe webhook: Unhandled event type', ['type' => $event->type]);
            }

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            Log::error('Stripe webhook: Event handling failed', [
                'type' => $event->type,
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Event handling failed'], 500);
        }
    }

    /**
     * Handle checkout.session.completed
     */
    protected function handleCheckoutCompleted($session)
    {
        Log::info('Stripe webhook: Checkout completed', ['session_id' => $session->id]);

        $metadata = (array) ($session->metadata ?? []);
        $minutesRaw = $metadata['minutes'] ?? null;
        $userIdRaw = $metadata['user_id'] ?? null;

        // Only handle minute packages if metadata is present.
        if ($minutesRaw === null || $userIdRaw === null) {
            return;
        }

        $minutes = (int) $minutesRaw;
        $userId = (int) $userIdRaw;
        if ($minutes <= 0 || $userId <= 0) {
            Log::warning('Stripe webhook: Invalid minutes purchase metadata', [
                'session_id' => $session->id,
                'user_id' => $userIdRaw,
                'minutes' => $minutesRaw,
            ]);
            return;
        }

        $paymentStatus = $session->payment_status ?? null;
        if ($paymentStatus && $paymentStatus !== 'paid') {
            Log::info('Stripe webhook: Checkout not paid, skipping minutes credit', [
                'session_id' => $session->id,
                'payment_status' => $paymentStatus,
            ]);
            return;
        }

        $existing = MobileWalletTransaction::query()
            ->where('user_id', $userId)
            ->where('type', 'topup')
            ->where('metadata->stripe_session_id', $session->id)
            ->exists();

        if ($existing) {
            Log::info('Stripe webhook: Minutes purchase already processed', ['session_id' => $session->id]);
            return;
        }

        $user = User::find($userId);
        if (!$user) {
            Log::warning('Stripe webhook: User not found for minutes purchase', [
                'session_id' => $session->id,
                'user_id' => $userId,
            ]);
            return;
        }

        DB::transaction(function () use ($user, $minutes, $session, $metadata) {
            $minutesWallet = MinutesWallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance_seconds' => 0]
            );

            $minutesWallet->balance_seconds += $minutes * 60;
            $minutesWallet->save();

            // Keep LiveCall wallet in sync as well.
            $wallet = Wallet::firstOrCreate(['user_id' => $user->id]);
            $wallet->increment('minutes_balance', $minutes);

            MobileWalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'topup',
                'amount' => (float) $minutes,
                'balance_after' => (float) floor(((int) $minutesWallet->balance_seconds) / 60),
                'description' => 'Stripe minutes package',
                'metadata' => [
                    'source' => 'stripe',
                    'stripe_session_id' => $session->id,
                    'package_id' => $metadata['package_id'] ?? null,
                    'minutes' => $minutes,
                    'currency' => $session->currency ?? null,
                    'amount_total' => $session->amount_total ?? null,
                ],
            ]);
        });
    }

    /**
     * Handle customer.subscription.created
     */
    protected function handleSubscriptionCreated($stripeSubscription)
    {
        Log::info('Stripe webhook: Subscription created', ['subscription_id' => $stripeSubscription->id]);

        $userId = $stripeSubscription->metadata->user_id ?? null;
        $planName = $stripeSubscription->metadata->plan_name ?? 'basic';

        if (!$userId) {
            Log::warning('Stripe webhook: Missing user_id in subscription metadata');
            return;
        }

        $tokensLimit = $this->getTokensLimitForPlan($planName);

        Subscription::updateOrCreate(
            ['stripe_subscription_id' => $stripeSubscription->id],
            [
                'user_id' => $userId,
                'stripe_customer_id' => $stripeSubscription->customer,
                'stripe_price_id' => $stripeSubscription->items->data[0]->price->id,
                'plan_name' => $planName,
                'status' => $stripeSubscription->status,
                'tokens_limit' => $tokensLimit,
                'current_period_start' => now()->createFromTimestamp($stripeSubscription->current_period_start),
                'current_period_end' => now()->createFromTimestamp($stripeSubscription->current_period_end),
                'trial_ends_at' => $stripeSubscription->trial_end 
                    ? now()->createFromTimestamp($stripeSubscription->trial_end) 
                    : null,
            ]
        );
    }

    /**
     * Handle customer.subscription.updated
     */
    protected function handleSubscriptionUpdated($stripeSubscription)
    {
        Log::info('Stripe webhook: Subscription updated', ['subscription_id' => $stripeSubscription->id]);

        $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();

        if (!$subscription) {
            Log::warning('Stripe webhook: Subscription not found in database');
            return;
        }

        $subscription->update([
            'status' => $stripeSubscription->status,
            'current_period_start' => now()->createFromTimestamp($stripeSubscription->current_period_start),
            'current_period_end' => now()->createFromTimestamp($stripeSubscription->current_period_end),
            'trial_ends_at' => $stripeSubscription->trial_end 
                ? now()->createFromTimestamp($stripeSubscription->trial_end) 
                : null,
        ]);

        // Reset token usage on period renewal
        if ($stripeSubscription->status === 'active') {
            $subscription->resetTokenUsage();
        }
    }

    /**
     * Handle customer.subscription.deleted
     */
    protected function handleSubscriptionDeleted($stripeSubscription)
    {
        Log::info('Stripe webhook: Subscription deleted', ['subscription_id' => $stripeSubscription->id]);

        $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();

        if ($subscription) {
            $subscription->update([
                'status' => 'canceled',
                'canceled_at' => now(),
            ]);
        }
    }

    /**
     * Handle invoice.payment_succeeded
     */
    protected function handleInvoicePaymentSucceeded($invoice)
    {
        Log::info('Stripe webhook: Invoice payment succeeded', ['invoice_id' => $invoice->id]);

        $subscription = Subscription::where('stripe_subscription_id', $invoice->subscription)->first();

        if (!$subscription) {
            Log::warning('Stripe webhook: Subscription not found for invoice');
            return;
        }

        // Record successful payment transaction
        PaymentTransaction::create([
            'user_id' => $subscription->user_id,
            'subscription_id' => $subscription->id,
            'stripe_payment_intent_id' => $invoice->payment_intent,
            'stripe_invoice_id' => $invoice->id,
            'amount' => $invoice->amount_paid / 100,
            'currency' => $invoice->currency,
            'status' => 'succeeded',
            'type' => 'subscription',
            'description' => "Subscription payment: {$subscription->plan_name}",
            'metadata' => [
                'billing_reason' => $invoice->billing_reason,
                'period_start' => $invoice->period_start,
                'period_end' => $invoice->period_end,
            ],
        ]);

        // Reset token usage for new billing period
        $subscription->resetTokenUsage();
    }

    /**
     * Handle invoice.payment_failed
     */
    protected function handleInvoicePaymentFailed($invoice)
    {
        Log::warning('Stripe webhook: Invoice payment failed', ['invoice_id' => $invoice->id]);

        $subscription = Subscription::where('stripe_subscription_id', $invoice->subscription)->first();

        if (!$subscription) {
            return;
        }

        // Update subscription status
        $subscription->update(['status' => 'past_due']);

        // Record failed payment transaction
        PaymentTransaction::create([
            'user_id' => $subscription->user_id,
            'subscription_id' => $subscription->id,
            'stripe_invoice_id' => $invoice->id,
            'amount' => $invoice->amount_due / 100,
            'currency' => $invoice->currency,
            'status' => 'failed',
            'type' => 'subscription',
            'description' => "Failed payment: {$subscription->plan_name}",
            'metadata' => [
                'billing_reason' => $invoice->billing_reason,
                'attempt_count' => $invoice->attempt_count,
            ],
        ]);

        // TODO: Send notification email to user about failed payment
    }

    /**
     * Handle customer.subscription.trial_will_end
     */
    protected function handleTrialWillEnd($stripeSubscription)
    {
        Log::info('Stripe webhook: Trial will end', ['subscription_id' => $stripeSubscription->id]);

        // TODO: Send notification email to user about ending trial
        $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();
        
        if ($subscription) {
            Log::info('Trial ending soon for user', ['user_id' => $subscription->user_id]);
            // Send email notification here
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
}
