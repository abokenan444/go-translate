<?php

namespace App\Http\Controllers\API\Mobile;

use App\Events\RealTimeTurnCreated;
use App\Http\Controllers\Controller;
use App\Models\MinutesWallet;
use App\Models\MobileNotification;
use App\Models\RealTimeSession;
use App\Services\RealTime\RealTimeVoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MobileRealTimeStreamController extends Controller
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
            'audio' => 'required|file|mimetypes:audio/webm,audio/wav,audio/mpeg,video/webm,audio/mp4,audio/x-m4a,application/octet-stream',
            'direction' => 'nullable|string|max:64',
            'duration_ms' => 'nullable|integer|min:1|max:30000',
        ]);

        $durationMs = (int) $request->input('duration_ms', 2000);
        $secondsToCharge = max(1, (int) ceil($durationMs / 1000));

        $user = $request->user();

        $chargeResult = DB::transaction(function () use ($user, $secondsToCharge) {
            $wallet = MinutesWallet::where('user_id', $user->id)->lockForUpdate()->first();
            if (!$wallet) {
                $wallet = MinutesWallet::create([
                    'user_id' => $user->id,
                    'balance_seconds' => 0,
                ]);
            }

            $before = (int) $wallet->balance_seconds;
            $charged = $wallet->debitSeconds($secondsToCharge);
            $after = (int) $wallet->balance_seconds;

            return [
                'charged' => $charged,
                'before' => $before,
                'after' => $after,
            ];
        });

        $charged = (bool) ($chargeResult['charged'] ?? false);
        if (!$charged) {
            return response()->json([
                'ok' => false,
                'message' => 'No minutes remaining',
            ], 402);
        }

        $this->maybeNotifyLowBalance(
            userId: (int) $user->id,
            beforeSeconds: (int) ($chargeResult['before'] ?? 0),
            afterSeconds: (int) ($chargeResult['after'] ?? 0),
        );

        $file = $request->file('audio');

        $path = $file->storeAs(
            'realtime/' . $session->public_id,
            uniqid('chunk_', true) . '.' . $file->getClientOriginalExtension(),
            'local'
        );

        $direction = $request->input('direction', 'mobile');
        $userId = $user->id;

        $participant = $session->participants()
            ->where('user_id', $userId)
            ->first();

        if (!$participant) {
            Storage::disk('local')->delete($path);
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
            Storage::disk('local')->delete($path);
        }

        broadcast(new RealTimeTurnCreated($turn))->toOthers();

        $translatedUrl = $turn->audio_path_translated
            ? asset('storage/' . ltrim($turn->audio_path_translated, '/'))
            : null;

        $afterSeconds = (int) ($chargeResult['after'] ?? 0);
        $warningMinutes = (int) config('livecall.low_balance_warning_minutes', 5);

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
            'billed_seconds' => $secondsToCharge,

            'wallet_balance_seconds' => $afterSeconds,
            'wallet_balance_minutes' => (int) floor(max(0, $afterSeconds) / 60),
            'low_balance_warning_minutes' => $warningMinutes,
        ]);
    }

    private function maybeNotifyLowBalance(int $userId, int $beforeSeconds, int $afterSeconds): void
    {
        $thresholdMinutes = (int) config('livecall.low_balance_warning_minutes', 5);
        $thresholdSeconds = max(0, $thresholdMinutes) * 60;

        if ($thresholdSeconds <= 0) {
            return;
        }

        // Only notify once per "crossing" from above threshold to below/equal.
        if (!($beforeSeconds > $thresholdSeconds && $afterSeconds <= $thresholdSeconds)) {
            return;
        }

        $remainingMinutes = (int) floor(max(0, $afterSeconds) / 60);

        MobileNotification::create([
            'user_id' => $userId,
            'type' => 'wallet_low_balance',
            'title' => 'رصيد الدقائق منخفض',
            'body' => "تبقى لديك {$remainingMinutes} دقيقة تقريباً. اشحن رصيدك لتجنب توقف الترجمة.",
            'data' => [
                'threshold_minutes' => $thresholdMinutes,
                'remaining_minutes' => $remainingMinutes,
            ],
        ]);
    }

    public function pollText(string $publicId)
    {
        $session = RealTimeSession::where('public_id', $publicId)->firstOrFail();

        $turns = $session->turns()
            ->latest('id')
            ->take(50)
            ->get([
                'id',
                'user_id',
                'external_id',
                'direction',
                'source_text',
                'translated_text',
                'source_language',
                'target_language',
                'audio_path_translated',
                'latency_ms',
                'created_at'
            ]);

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
