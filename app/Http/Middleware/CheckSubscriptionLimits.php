<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserSubscription;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionLimits
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required',
            ], 401);
        }

        // Get active subscription
        $subscription = UserSubscription::getCurrentPlan($user->id);

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscription found',
                'action' => 'subscribe',
            ], 403);
        }

        // Check if can translate
        $canTranslate = $subscription->canTranslate();

        if (!$canTranslate['allowed']) {
            $response = [
                'success' => false,
                'reason' => $canTranslate['reason'],
                'message' => $canTranslate['message'],
            ];

            // Add specific details based on reason
            switch ($canTranslate['reason']) {
                case 'monthly_limit_reached':
                    $response['limit'] = $canTranslate['limit'];
                    $response['action'] = 'upgrade';
                    break;
                case 'insufficient_balance':
                    $response['balance'] = $canTranslate['balance'];
                    $response['action'] = 'add_credits';
                    break;
                case 'subscription_inactive':
                    $response['action'] = 'renew';
                    break;
            }

            return response()->json($response, 429);
        }

        // Attach subscription to request for later use
        $request->attributes->set('subscription', $subscription);

        return $next($request);
    }
}
