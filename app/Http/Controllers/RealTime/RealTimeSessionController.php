<?php

namespace App\Http\Controllers\RealTime;

use App\Http\Controllers\Controller;
use App\Models\RealTimeSession;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RealTimeSessionController extends Controller
{
    public function create(Request $request)
    {
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
}
