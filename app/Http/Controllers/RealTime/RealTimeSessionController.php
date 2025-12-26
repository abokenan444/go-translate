<?php

namespace App\Http\Controllers\RealTime;

use App\Http\Controllers\Controller;
use App\Models\RealTimeSession;
use App\Models\MobileContact;
use App\Models\MobileNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class RealTimeSessionController extends Controller
{
    public function create(Request $request)
    {
        Log::info('RealTimeSession create called', [
            'input' => $request->all(),
            'user_id' => $request->user()?->id,
        ]);
        
        $data = $request->validate([
            'type' => 'nullable|string|in:meeting,call,game,webinar',
            'title' => 'nullable|string|max:255',
            'source_language' => 'required|string|max:10',
            'target_language' => 'required|string|max:10',
            'source_culture_code' => 'nullable|string|max:32',
            'target_culture_code' => 'nullable|string|max:32',
            'bi_directional' => 'boolean',
            'record_audio' => 'boolean',
            'record_transcript' => 'boolean',
            'max_participants' => 'nullable|integer|min:2|max:50',
            'contact_id' => 'nullable|integer|exists:mobile_contacts,id',
        ]);

        $user = $request->user();

        $session = RealTimeSession::create([
            'public_id' => Str::uuid()->toString(),
            'owner_id' => $user?->id ?? 1,
            'type' => $data['type'] ?? 'meeting',
            'title' => $data['title'] ?? null,
            'source_language' => $data['source_language'],
            'target_language' => $data['target_language'],
            'source_culture_code' => $data['source_culture_code'] ?? null,
            'target_culture_code' => $data['target_culture_code'] ?? null,
            'bi_directional' => $data['bi_directional'] ?? true,
            'record_audio' => $data['record_audio'] ?? false,
            'record_transcript' => $data['record_transcript'] ?? true,
            'max_participants' => $data['max_participants'] ?? config('realtime.max_participants', 8),
            'started_at' => now(),
            'metadata' => [
                'created_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        ]);

        // If calling a contact, send notification to the receiver
        if (!empty($data['contact_id'])) {
            Log::info('Contact ID provided, looking for contact', ['contact_id' => $data['contact_id']]);
            $contact = MobileContact::find($data['contact_id']);
            Log::info('Contact found', ['contact' => $contact?->toArray()]);
            
            if ($contact && $contact->contact_user_id) {
                Log::info('Creating incoming_call notification for user', ['receiver_user_id' => $contact->contact_user_id]);
                MobileNotification::create([
                    'user_id' => $contact->contact_user_id,
                    'type' => 'incoming_call',
                    'title' => '📞 مكالمة واردة',
                    'body' => "{$user->name} يتصل بك الآن",
                    'data' => [
                        'session_id' => $session->public_id,
                        'caller_id' => $user->id,
                        'caller_name' => $user->name,
                        'source_language' => $data['source_language'],
                        'target_language' => $data['target_language'],
                    ],
                ]);
                Log::info('Notification created successfully');
            } else {
                Log::warning('Contact has no contact_user_id', ['contact_id' => $data['contact_id']]);
            }
        } else {
            Log::info('No contact_id provided in request');
        }

        return response()->json([
            'ok' => true,
            'session' => [
                'public_id' => $session->public_id,
                'join_url' => route('realtime.meeting.show', $session->public_id),
            ],
        ]);
    }

    public function showMeeting(string $publicId)
    {
        $session = RealTimeSession::where('public_id', $publicId)
            ->where('is_active', true)
            ->firstOrFail();

        return view('realtime.meeting', compact('session'));
    }

    /**
     * End a realtime session
     */
    public function end(Request $request, string $publicId)
    {
        $session = RealTimeSession::where('public_id', $publicId)->first();

        if (!$session) {
            return response()->json([
                'ok' => true,
                'message' => 'Session already ended or not found',
            ]);
        }

        // Update session
        $session->update([
            'is_active' => false,
            'ended_at' => now(),
        ]);

        // Disconnect all participants
        $session->participants()->update([
            'status' => 'disconnected',
            'left_at' => now(),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Session ended successfully',
            'duration_seconds' => $session->started_at ? now()->diffInSeconds($session->started_at) : 0,
        ]);
    }
}
