<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use App\Models\MobileCallHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CallHistoryController extends Controller
{
    public function index(Request $request)
    {
        $calls = MobileCallHistory::where('user_id', Auth::id())
            ->with('contact:id,name,phone,avatar_url')
            ->orderByDesc('started_at')
            ->limit($request->input('limit', 50))
            ->get()
            ->map(fn($call) => [
                'id' => $call->id,
                'session_public_id' => $call->session_public_id,
                'direction' => $call->direction,
                'status' => $call->status,
                'contact' => $call->contact ? [
                    'id' => $call->contact->id,
                    'name' => $call->contact->name,
                    'avatar_url' => $call->contact->avatar_url,
                ] : null,
                'duration' => $call->formatted_duration,
                'duration_seconds' => $call->duration_seconds,
                'minutes_used' => (float) $call->minutes_used,
                'languages' => [
                    'caller_send' => $call->caller_send_language,
                    'caller_receive' => $call->caller_receive_language,
                    'receiver_send' => $call->receiver_send_language,
                    'receiver_receive' => $call->receiver_receive_language,
                ],
                'started_at' => $call->started_at?->toIso8601String(),
                'ended_at' => $call->ended_at?->toIso8601String(),
            ]);

        return response()->json(['success' => true, 'calls' => $calls]);
    }

    public function show(MobileCallHistory $call)
    {
        if ($call->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        return response()->json([
            'success' => true,
            'call' => [
                'id' => $call->id,
                'session_public_id' => $call->session_public_id,
                'direction' => $call->direction,
                'status' => $call->status,
                'duration_seconds' => $call->duration_seconds,
                'minutes_used' => (float) $call->minutes_used,
                'started_at' => $call->started_at?->toIso8601String(),
                'ended_at' => $call->ended_at?->toIso8601String(),
            ],
        ]);
    }
}
