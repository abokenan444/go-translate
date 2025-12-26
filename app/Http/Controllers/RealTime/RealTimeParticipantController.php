<?php

namespace App\Http\Controllers\RealTime;

use App\Http\Controllers\Controller;
use App\Models\RealTimeParticipant;
use App\Models\RealTimeSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RealTimeParticipantController extends Controller
{
    /**
     * Join a session as a participant
     */
    public function join(Request $request, string $publicId)
    {
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'role' => 'sometimes|in:moderator,speaker,listener',
            'external_id' => 'sometimes|string',
            'send_language' => 'sometimes|nullable|string|max:10',
            'receive_language' => 'sometimes|nullable|string|max:10',
        ]);

        $session = RealTimeSession::where('public_id', $publicId)->firstOrFail();

        // Check if user already joined
        $userId = Auth::id();
        $externalId = $validated['external_id'] ?? ($userId ? null : Str::uuid());

        $participant = RealTimeParticipant::updateOrCreate(
            [
                'session_id' => $session->id,
                'user_id' => $userId,
                'external_id' => $externalId,
            ],
            [
                'display_name' => $validated['display_name'],
                'send_language' => $validated['send_language'] ?? $session->source_language,
                'receive_language' => $validated['receive_language'] ?? $session->target_language,
                'role' => $validated['role'] ?? 'speaker',
                'status' => 'connected',
                'joined_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'participant' => $participant,
        ]);
    }

    /**
     * Leave a session
     */
    public function leave(Request $request, string $publicId)
    {
        $session = RealTimeSession::where('public_id', $publicId)->firstOrFail();
        
        $participant = RealTimeParticipant::where('session_id', $session->id)
            ->where(function ($query) use ($request) {
                if (Auth::check()) {
                    $query->where('user_id', Auth::id());
                } else {
                    $query->where('external_id', $request->input('external_id'));
                }
            })
            ->firstOrFail();

        $participant->disconnect();

        return response()->json([
            'success' => true,
            'message' => 'Left session successfully',
        ]);
    }

    /**
     * Get all participants in a session
     */
    public function index(string $publicId)
    {
        $session = RealTimeSession::where('public_id', $publicId)->firstOrFail();

        $participants = RealTimeParticipant::where('session_id', $session->id)
            ->where('status', '!=', 'disconnected')
            ->with('user:id,name,email')
            ->get();

        return response()->json([
            'success' => true,
            'participants' => $participants,
        ]);
    }

    /**
     * Update participant status (mute/unmute, role change, etc.)
     */
    public function update(Request $request, string $publicId, int $participantId)
    {
        $validated = $request->validate([
            'is_muted' => 'sometimes|boolean',
            'is_video_enabled' => 'sometimes|boolean',
            'role' => 'sometimes|in:moderator,speaker,listener',
            'status' => 'sometimes|in:connected,disconnected,muted',
            'send_language' => 'sometimes|nullable|string|max:10',
            'receive_language' => 'sometimes|nullable|string|max:10',
        ]);

        $session = RealTimeSession::where('public_id', $publicId)->firstOrFail();
        
        $participant = RealTimeParticipant::where('session_id', $session->id)
            ->where('id', $participantId)
            ->firstOrFail();

        // Check permissions (only moderators can change others' status)
        $currentParticipant = RealTimeParticipant::where('session_id', $session->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($currentParticipant && !$currentParticipant->isModerator() && $participant->id !== $currentParticipant->id) {
            return response()->json([
                'success' => false,
                'message' => 'Only moderators can update other participants',
            ], 403);
        }

        $participant->update($validated);

        return response()->json([
            'success' => true,
            'participant' => $participant,
        ]);
    }

    /**
     * Update the currently authenticated participant (convenience for web UI)
     */
    public function updateMe(Request $request, string $publicId)
    {
        $validated = $request->validate([
            'is_muted' => 'sometimes|boolean',
            'is_video_enabled' => 'sometimes|boolean',
            'send_language' => 'sometimes|nullable|string|max:10',
            'receive_language' => 'sometimes|nullable|string|max:10',
        ]);

        $session = RealTimeSession::where('public_id', $publicId)->firstOrFail();

        $participant = RealTimeParticipant::where('session_id', $session->id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $participant->update($validated);

        return response()->json([
            'success' => true,
            'participant' => $participant,
        ]);
    }

    /**
     * Update connection quality metrics
     */
    public function updateQuality(Request $request, string $publicId)
    {
        $validated = $request->validate([
            'latency' => 'sometimes|numeric',
            'packet_loss' => 'sometimes|numeric',
            'audio_level' => 'sometimes|numeric',
            'video_quality' => 'sometimes|string',
        ]);

        $session = RealTimeSession::where('public_id', $publicId)->firstOrFail();
        
        $participant = RealTimeParticipant::where('session_id', $session->id)
            ->where(function ($query) use ($request) {
                if (Auth::check()) {
                    $query->where('user_id', Auth::id());
                } else {
                    $query->where('external_id', $request->input('external_id'));
                }
            })
            ->firstOrFail();

        $participant->updateConnectionQuality($validated);

        return response()->json([
            'success' => true,
            'message' => 'Quality metrics updated',
        ]);
    }
}
