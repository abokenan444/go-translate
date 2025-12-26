<?php

namespace App\Http\Controllers\RealTime;

use App\Events\RealTimeTurnCreated;
use App\Http\Controllers\Controller;
use App\Models\RealTimeSession;
use App\Services\RealTime\RealTimeVoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RealTimeStreamController extends Controller
{
    public function __construct(
        protected RealTimeVoiceService $voiceService
    ) {}

    public function handleAudio(Request $request, string $publicId)
    {
        $session = RealTimeSession::where('public_id', $publicId)
            ->where('is_active', true)
            ->firstOrFail();

        $request->validate([
            'audio' => 'required|file|mimetypes:audio/webm,audio/wav,audio/mpeg,video/webm',
            'direction' => 'nullable|string|max:64',
            'external_id' => 'nullable|string|max:64',
            'duration_ms' => 'nullable|integer|min:1|max:30000',
        ]);

        $file = $request->file('audio');

        $path = $file->storeAs(
            'realtime/' . $session->public_id,
            uniqid('chunk_', true) . '.' . $file->getClientOriginalExtension(),
            'local'
        );

        $direction = $request->input('direction', 'custom');
        $userId = $request->user()?->id ?? $request->input('external_id');

        // Deduct tokens for this turn (local MVP billing)
        $subscription = $request->attributes->get('subscription');
        $durationMs = (int) $request->input('duration_ms', 2000);
        $tokensPerSecond = (int) config('realtime.billing.voice_tokens_per_second', 1);
        $tokensToCharge = max(1, (int) ceil(($durationMs / 1000) * $tokensPerSecond));
        if ($subscription) {
            $subscription->useTokens($tokensToCharge, 'realtime_voice_turn', null, [
                'session_public_id' => $session->public_id,
                'duration_ms' => $durationMs,
            ]);
        }

        // Determine participant language preferences (1:1 call)
        $participant = $session->participants()
            ->where(function ($q) use ($request, $userId) {
                if ($request->user()) {
                    $q->where('user_id', $request->user()->id);
                } else {
                    $q->where('external_id', $userId);
                }
            })
            ->first();

        if (!$participant) {
            return response()->json([
                'ok' => false,
                'message' => 'Participant not joined',
            ], 409);
        }

        $otherParticipant = $session->participants()
            ->where('id', '!=', $participant->id)
            ->where('status', '!=', 'disconnected')
            ->orderByDesc('id')
            ->first();

        $sourceLang = $participant->send_language ?: 'auto';
        $targetLang = $otherParticipant?->receive_language ?: ($session->target_language ?: 'en');

        try {
            $turn = $this->voiceService->processAudioTurnWithLanguages(
                session: $session,
                userId: $userId,
                audioPath: $path,
                sourceLang: $sourceLang,
                targetLang: $targetLang,
                direction: $direction,
            );
        } finally {
            // Local MVP privacy: don't keep uploaded chunks
            Storage::disk('local')->delete($path);
        }

        // بث الحدث للحاضرين
        broadcast(new RealTimeTurnCreated($turn))->toOthers();

        $translatedUrl = $turn->audio_path_translated
            ? asset('storage/' . ltrim($turn->audio_path_translated, '/'))
            : null;

        return response()->json([
            'ok' => true,
            'turn_id' => $turn->id,
            'direction' => $turn->direction,
            'source_text' => $turn->source_text,
            'translated_text' => $turn->translated_text,
            'latency_ms' => $turn->latency_ms,
            'translated_audio_url' => $translatedUrl,
            'source_language' => $turn->source_language,
            'target_language' => $turn->target_language,
        ]);
    }

    public function pollText(string $publicId)
    {
        $session = RealTimeSession::where('public_id', $publicId)->firstOrFail();

        $turns = $session->turns()
            ->latest('id')
            ->take(50)
            ->get(['id', 'user_id', 'external_id', 'direction', 'source_text', 'translated_text', 'source_language', 'target_language', 'audio_path_translated', 'latency_ms', 'created_at']);

        $turns = $turns->map(function ($turn) {
            $turn->translated_audio_url = $turn->audio_path_translated
                ? asset('storage/' . ltrim($turn->audio_path_translated, '/'))
                : null;
            return $turn;
        });

        return response()->json([
            'ok' => true,
            'turns' => $turns,
        ]);
    }
}
