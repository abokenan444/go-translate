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
            'direction' => 'nullable|string|in:source_to_target,target_to_source',
            'external_id' => 'nullable|string|max:64',
        ]);

        $file = $request->file('audio');

        $path = $file->storeAs(
            'realtime/' . $session->public_id,
            uniqid('chunk_', true) . '.' . $file->getClientOriginalExtension(),
            'local'
        );

        $direction = $request->input('direction', 'source_to_target');
        $userId = $request->user()?->id ?? $request->input('external_id');

        $turn = $this->voiceService->processAudioTurn(
            session: $session,
            userId: $userId,
            audioPath: $path,
            direction: $direction,
        );

        // بث الحدث للحاضرين
        broadcast(new RealTimeTurnCreated($turn))->toOthers();

        $translatedUrl = Storage::disk('local')->url($turn->audio_path_translated);

        return response()->json([
            'ok' => true,
            'turn_id' => $turn->id,
            'direction' => $turn->direction,
            'source_text' => $turn->source_text,
            'translated_text' => $turn->translated_text,
            'latency_ms' => $turn->latency_ms,
            'translated_audio_url' => $translatedUrl,
        ]);
    }

    public function pollText(string $publicId)
    {
        $session = RealTimeSession::where('public_id', $publicId)->firstOrFail();

        $turns = $session->turns()
            ->latest('id')
            ->take(50)
            ->get(['id', 'direction', 'source_text', 'translated_text', 'latency_ms', 'created_at']);

        return response()->json([
            'ok' => true,
            'turns' => $turns,
        ]);
    }
}
