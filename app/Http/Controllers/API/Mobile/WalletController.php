<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use App\Models\MinutesWallet;
use App\Models\MobileWalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    public function balance(Request $request)
    {
        $wallet = MinutesWallet::firstOrCreate([
            'user_id' => $request->user()->id,
        ], [
            'balance_seconds' => 0,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'balance_seconds' => (int) $wallet->balance_seconds,
                'balance_minutes' => (int) floor(((int) $wallet->balance_seconds) / 60),
            ],
        ]);
    }

    public function topup(Request $request)
    {
        if (!app()->environment('local')) {
            return response()->json([
                'success' => false,
                'message' => 'Top-up is only available in local environment',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'minutes' => 'required|integer|min:1|max:100000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $wallet = MinutesWallet::firstOrCreate([
            'user_id' => $request->user()->id,
        ], [
            'balance_seconds' => 0,
        ]);

        $minutes = (int) $request->input('minutes');
        $wallet->addMinutes($minutes);

        // Record transaction
        MobileWalletTransaction::create([
            'user_id' => $request->user()->id,
            'type' => 'topup',
            'amount' => (float) $minutes,
            'balance_after' => (float) floor(((int) $wallet->balance_seconds) / 60),
            'description' => 'Manual top-up',
            'metadata' => [
                'source' => 'manual',
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Topped up',
            'data' => [
                'balance_seconds' => (int) $wallet->balance_seconds,
                'balance_minutes' => (int) floor(((int) $wallet->balance_seconds) / 60),
            ],
        ]);
    }

    public function transactions(Request $request)
    {
        $transactions = MobileWalletTransaction::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->limit($request->input('limit', 50))
            ->get()
            ->map(fn($t) => [
                'id' => $t->id,
                'type' => $t->type,
                'amount_minutes' => (float) $t->amount,
                'balance_after_minutes' => (float) $t->balance_after,
                'description' => $t->description,
                'metadata' => $t->metadata,
                'is_credit' => $t->isCredit(),
                'created_at' => $t->created_at->toIso8601String(),
            ]);

        return response()->json([
            'success' => true,
            'transactions' => $transactions,
        ]);
    }

    public function autoTopupSettings(Request $request)
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'settings' => [
                'enabled' => (bool) ($user->auto_topup_enabled ?? false),
                'threshold' => (float) ($user->auto_topup_threshold ?? 5),
                'amount' => (float) ($user->auto_topup_amount ?? 30),
            ],
        ]);
    }

    public function updateAutoTopup(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'enabled' => 'required|boolean',
            'threshold' => 'required_if:enabled,true|numeric|min:1|max:100',
            'amount' => 'required_if:enabled,true|numeric|min:5|max:500',
        ]);

        $user->auto_topup_enabled = $data['enabled'];
        if ($data['enabled']) {
            $user->auto_topup_threshold = $data['threshold'] ?? 5;
            $user->auto_topup_amount = $data['amount'] ?? 30;
        }
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Auto top-up settings updated',
            'settings' => [
                'enabled' => (bool) $user->auto_topup_enabled,
                'threshold' => (float) $user->auto_topup_threshold,
                'amount' => (float) $user->auto_topup_amount,
            ],
        ]);
    }
}
