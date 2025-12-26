<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use App\Models\MobileInvite;
use App\Notifications\ContactAddedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class InvitesController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Ensure user has a referral code
        if (empty($user->referral_code)) {
            $user->referral_code = strtoupper(Str::random(6));
            $user->save();
        }

        $invites = MobileInvite::where('inviter_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        // Calculate stats
        $totalInvites = $invites->count();
        $successfulInvites = $invites->where('status', 'accepted')->count();
        $earnedMinutes = $successfulInvites * 5; // 5 minutes per successful invite

        return response()->json([
            'success' => true,
            'invites' => $invites,
            'invite_code' => $user->referral_code,
            'referral_link' => config('app.url') . '/invite/' . $user->referral_code,
            'reward_minutes_per_invite' => (int) config('livecall.referral_reward_minutes', 5),
            'stats' => [
                'total_invites' => $totalInvites,
                'successful_invites' => $successfulInvites,
                'earned_minutes' => $earnedMinutes,
            ],
        ]);
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'name' => 'nullable|string|max:255',
            'contact_id' => 'nullable|integer',
        ]);

        $user = $request->user();

        // Check if already invited this email/phone
        if (!empty($data['email'])) {
            $existing = MobileInvite::where('inviter_id', $user->id)
                ->where('invited_email', $data['email'])
                ->where('status', 'pending')
                ->first();
            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already invited this email.',
                ], 422);
            }
        }

        // Create invite
        $invite = MobileInvite::create([
            'inviter_id' => $user->id,
            'invite_code' => MobileInvite::generateCode(),
            'invited_email' => $data['email'] ?? null,
            'invited_phone' => $data['phone'] ?? null,
            'invited_name' => $data['name'] ?? null,
            'status' => 'pending',
        ]);

        // Send invitation email
        if (!empty($data['email'])) {
            try {
                Notification::route('mail', $data['email'])
                    ->notify(new ContactAddedNotification($user, false));
            } catch (\Exception $e) {
                \Log::warning('Failed to send invite email', [
                    'email' => $data['email'],
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'invite' => $invite,
            'message' => 'Invitation sent successfully!',
        ], 201);
    }
}
