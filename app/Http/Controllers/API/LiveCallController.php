<?php

namespace App\Http\Controllers\Api;

use App\Events\LiveCall\SignalEvent;
use App\Http\Controllers\Controller;
use App\Models\LiveCallSession;
use App\Models\Wallet;
use App\Services\LiveCall\LiveCallBillingService;
use App\Services\LiveCall\LiveCallPricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LiveCallController extends Controller
{
    public function __construct(
        private LiveCallPricingService $pricing,
        private LiveCallBillingService $billing
    ) {}

    /**
     * POST /api/livecall/sessions
     * Create a room as caller
     */
    public function createSession(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'caller_send_lang' => 'required|string|max:10',
            'caller_receive_lang' => 'required|string|max:10',
            'billing_mode' => 'nullable|in:prepaid,payg',
            'callee_user_id' => 'nullable|integer|exists:users,id',
        ]);

        $wallet = Wallet::firstOrCreate(['user_id' => $user->id]);

        $billingMode = $data['billing_mode'] ?? 'prepaid';
        if ($billingMode === 'payg' && !$wallet->payg_enabled) {
            $billingMode = 'prepaid';
        }

        $price = $this->pricing->pricePerMinute($user);

        $session = LiveCallSession::create([
            'caller_user_id' => $user->id,
            'callee_user_id' => $data['callee_user_id'] ?? null,
            'mode' => 'webrtc',
            'status' => 'created',
            'caller_send_lang' => $data['caller_send_lang'],
            'caller_receive_lang' => $data['caller_receive_lang'],
            'billing_mode' => $billingMode,
            'price_per_minute_snapshot' => $price,
        ]);

        Log::info('LiveCall session created', [
            'room_id' => $session->room_id,
            'caller_id' => $user->id,
        ]);

        return response()->json([
            'room_id' => $session->room_id,
            'status' => $session->status,
            'ws_channel' => "private-livecall.{$session->room_id}",
            'reverb' => [
                'host' => env('REVERB_HOST', '127.0.0.1'),
                'port' => env('REVERB_PORT', 8080),
                'scheme' => env('REVERB_SCHEME', 'http'),
                'app_key' => env('REVERB_APP_KEY'),
            ],
            'price_per_minute' => (float)$session->price_per_minute_snapshot,
            'billing_mode' => $session->billing_mode,
            'wallet_minutes_balance' => (int)$wallet->minutes_balance,
        ]);
    }

    /**
     * POST /api/livecall/sessions/{roomId}/join
     * Callee joins and sets language preferences
     */
    public function joinSession(Request $request, string $roomId)
    {
        $user = $request->user();

        $session = LiveCallSession::where('room_id', $roomId)->firstOrFail();

        // Ensure callee is allowed
        if ($session->callee_user_id && $session->callee_user_id !== $user->id) {
            abort(403, 'Not allowed to join this session');
        }

        $data = $request->validate([
            'callee_send_lang' => 'required|string|max:10',
            'callee_receive_lang' => 'required|string|max:10',
        ]);

        $session->update([
            'callee_user_id' => $session->callee_user_id ?? $user->id,
            'callee_send_lang' => $data['callee_send_lang'],
            'callee_receive_lang' => $data['callee_receive_lang'],
            'status' => 'ringing',
        ]);

        Log::info('LiveCall session joined', [
            'room_id' => $roomId,
            'callee_id' => $user->id,
        ]);

        // Notify room
        event(new SignalEvent($roomId, [
            'type' => 'room_state',
            'status' => 'ringing',
        ]));

        return response()->json([
            'room_id' => $session->room_id,
            'status' => $session->status,
            'reverb' => [
                'host' => env('REVERB_HOST', '127.0.0.1'),
                'port' => env('REVERB_PORT', 8080),
                'scheme' => env('REVERB_SCHEME', 'http'),
                'app_key' => env('REVERB_APP_KEY'),
            ],
        ]);
    }

    /**
     * POST /api/livecall/signal
     * Relay signaling messages via broadcast:
     * offer/answer/ice_candidate/hangup
     */
    public function signal(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'room_id' => 'required|uuid',
            'type' => 'required|in:offer,answer,ice_candidate,hangup',
            'payload' => 'required|array',
        ]);

        $session = LiveCallSession::where('room_id', $data['room_id'])->firstOrFail();

        if (!in_array($user->id, [$session->caller_user_id, $session->callee_user_id], true)) {
            abort(403, 'Not a participant');
        }

        // When offer/answer happens, move to active if appropriate
        if ($data['type'] === 'answer' && $session->status !== 'active') {
            $session->update([
                'status' => 'active',
                'started_at' => now(),
            ]);

            Log::info('LiveCall session activated', ['room_id' => $data['room_id']]);

            event(new SignalEvent($data['room_id'], [
                'type' => 'room_state',
                'status' => 'active',
                'started_at' => $session->started_at?->toIso8601String(),
            ]));
        }

        // Broadcast the original message to the other peer(s)
        event(new SignalEvent($data['room_id'], [
            'type' => $data['type'],
            'from_user_id' => $user->id,
            ...$data['payload'],
        ]));

        // Hangup => end session
        if ($data['type'] === 'hangup') {
            $session->update([
                'status' => 'ended',
                'ended_at' => now(),
            ]);

            Log::info('LiveCall session ended', ['room_id' => $data['room_id']]);

            event(new SignalEvent($data['room_id'], [
                'type' => 'room_state',
                'status' => 'ended',
                'ended_at' => $session->ended_at?->toIso8601String(),
            ]));
        }

        return response()->json(['ok' => true]);
    }

    /**
     * POST /api/livecall/sessions/{roomId}/bill-tick
     * Called by client every 60 seconds while connected (MVP).
     * Later SFU will compute actual processed seconds.
     */
    public function billTick(Request $request, string $roomId)
    {
        $user = $request->user();
        $session = LiveCallSession::where('room_id', $roomId)->firstOrFail();

        if ($session->status !== 'active') {
            return response()->json(['ok' => true, 'status' => $session->status]);
        }

        if (!in_array($user->id, [$session->caller_user_id, $session->callee_user_id], true)) {
            abort(403, 'Not a participant');
        }

        $data = $request->validate([
            'seconds' => 'required|integer|min:1|max:120',
        ]);

        try {
            $this->billing->billSeconds($session, (int)$data['seconds'], $user->id);
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'INSUFFICIENT_MINUTES') {
                // Notify hangup reason
                event(new SignalEvent($roomId, [
                    'type' => 'billing',
                    'status' => 'insufficient_minutes',
                ]));

                // End session
                $session->update(['status' => 'ended', 'ended_at' => now()]);
                event(new SignalEvent($roomId, ['type' => 'hangup', 'reason' => 'insufficient_minutes']));
            }
            throw $e;
        }

        $wallet = Wallet::firstOrCreate(['user_id' => $user->id]);

        return response()->json([
            'ok' => true,
            'wallet_minutes_balance' => (int)$wallet->minutes_balance,
            'session_billed_seconds' => (int)$session->billed_seconds,
        ]);
    }
}
