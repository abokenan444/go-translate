<?php

namespace App\Http\Middleware;

use App\Models\UserSubscription;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireSubscriptionPlan
{
    public function handle(Request $request, Closure $next, string ...$allowedSlugs): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required',
            ], 401);
        }

        $subscription = $request->attributes->get('subscription')
            ?? UserSubscription::getCurrentPlan($user->id);

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscription found',
            ], 403);
        }

        $planSlug = optional($subscription->plan)->slug;

        if (!$planSlug || (!empty($allowedSlugs) && !in_array($planSlug, $allowedSlugs, true))) {
            return response()->json([
                'success' => false,
                'message' => 'This feature requires a dedicated live call plan',
                'required_plans' => $allowedSlugs,
            ], 403);
        }

        return $next($request);
    }
}
