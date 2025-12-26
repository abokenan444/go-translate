<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\MonthlyPayoutJob;

class AffiliatesAdminController extends Controller
{
    public function triggerPayout(Request $request, ?string $period = null)
    {
        $user = $request->user();
        if (!$user) {
            abort(403);
        }

        // Basic authorization: require admin boolean column or super admin role via Filament
        if (method_exists($user, 'isAdmin') && !$user->isAdmin()) {
            abort(403);
        }

        $period = $period ?: now()->format('Y-m');
        MonthlyPayoutJob::dispatch($period);

        return response()->json(['status' => 'ok', 'message' => 'MonthlyPayoutJob dispatched', 'period' => $period]);
    }
}
