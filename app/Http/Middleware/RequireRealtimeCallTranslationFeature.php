<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireRealtimeCallTranslationFeature
{
    public function handle(Request $request, Closure $next): Response
    {
        $subscription = $request->attributes->get('subscription');
        $plan = $subscription?->plan;

        $features = (array)($plan?->features ?? []);
        $allowed = (bool)($plan?->is_custom) || in_array('realtime_call_translation', $features, true);

        if (!$allowed) {
            $payload = [
                'success' => false,
                'message' => 'Live call translation requires a dedicated plan',
                'action' => 'upgrade',
                'required_feature' => 'realtime_call_translation',
            ];

            if ($request->expectsJson()) {
                return response()->json($payload, 403);
            }

            return redirect('/pricing');
        }

        return $next($request);
    }
}
