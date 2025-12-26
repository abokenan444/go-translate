<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use App\Models\MobileInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $invites = MobileInvite::where('inviter_id', $user->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($inv) => [
                'id' => $inv->id,
                'invite_code' => $inv->invite_code,
                'invited_email' => $inv->invited_email,
                'invited_phone' => $inv->invited_phone,
                'status' => $inv->status,
                'reward_minutes' => (float) $inv->reward_minutes,
                'created_at' => $inv->created_at->toIso8601String(),
            ]);

        return response()->json([
            'success' => true,
            'my_referral_code' => $user->referral_code,
            'invites' => $invites,
        ]);
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
        ]);

        if (empty($data['email']) && empty($data['phone'])) {
            return response()->json([
                'success' => false,
                'message' => 'Email or phone required',
            ], 422);
        }

        $invite = MobileInvite::create([
            'inviter_id' => Auth::id(),
            'invite_code' => MobileInvite::generateCode(),
            'invited_email' => $data['email'] ?? null,
            'invited_phone' => $data['phone'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'invite' => [
                'id' => $invite->id,
                'invite_code' => $invite->invite_code,
                'invited_email' => $invite->invited_email,
                'invited_phone' => $invite->invited_phone,
                'status' => $invite->status,
            ],
        ], 201);
    }

    public function getMyReferralCode()
    {
        $user = Auth::user();
        
        if (!$user->referral_code) {
            $user->referral_code = strtoupper(\Illuminate\Support\Str::random(8));
            $user->save();
        }

        return response()->json([
            'success' => true,
            'referral_code' => $user->referral_code,
            'referral_link' => config('app.url') . '/invite/' . $user->referral_code,
        ]);
    }
}
