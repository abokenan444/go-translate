<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Services\MTE\AsrService;
use App\Services\MTE\TtsService;
use App\Services\CulturalPromptEngine;
use App\Models\VoiceTranslation;
use App\Models\UserSubscription;
use App\Models\UsageRecord;

class VoiceTranslationController extends Controller
{
    protected $asr;
    protected $tts;
    protected $culturalEngine;

    public function __construct(
        AsrService $asr,
        TtsService $tts,
        CulturalPromptEngine $culturalEngine
    ) {
        $this->asr = $asr;
        $this->tts = $tts;
        $this->culturalEngine = $culturalEngine;
    }

    /**
     * Translate voice with subscription check
     */
    public function translateVoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'audio' => 'required|file|mimes:wav,mp3,m4a,ogg,flac,webm|max:10240', // 10MB max
            'source_lang' => 'nullable|string|max:10',
            'target_lang' => 'required|string|max:10',
            'output_voice' => 'nullable|string',
            'return_audio' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();
        $subscription = $request->attributes->get('subscription');
        $plan = $subscription->pricingPlan;

        // Check if plan has voice translation feature
        if (!$plan->has_advanced_features && $plan->type !== 'custom') {
            return response()->json([
                'success' => false,
                'message' => 'Voice translation requires advanced plan',
                'action' => 'upgrade',
                'required_plan' => 'Professional or higher',
            ], 403);
        }

        $startTime = microtime(true);
        $audioFile = $request->file('audio');
        $audioPath = $audioFile->store('voice-translations', 'local');
        $fullPath = Storage::disk('local')->path($audioPath);

        // Get audio duration and size
        $audioDuration = $this->getAudioDuration($fullPath);
        $audioSize = $audioFile->getSize();

        // Calculate cost
        $cost = VoiceTranslation::calculateCost($audioDuration, 'per_second');

        // Check balance for pay-per-use
        if ($plan->type === 'pay_per_use') {
            if ($subscription->credit_balance < $cost) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient credits for voice translation',
                    'required' => $cost,
                    'available' => $subscription->credit_balance,
                    'action' => 'add_credits',
                ], 402);
            }
        }

        try {
            // Step 1: Transcribe audio to text
            $transcriptionResult = $this->asr->transcribe(
                $fullPath,
                $request->input('source_lang', 'auto')
            );

            if (!($transcriptionResult['success'] ?? false)) {
                throw new \Exception('Transcription failed: ' . ($transcriptionResult['error'] ?? 'Unknown error'));
            }

            $transcribedText = $transcriptionResult['text'] ?? '';
            $confidence = $transcriptionResult['confidence'] ?? 0;
            $detectedLang = $transcriptionResult['language'] ?? $request->input('source_lang', 'en');

            // Step 2: Translate text
            $translatedText = $this->culturalEngine->translateWithContext(
                $transcribedText,
                $detectedLang,
                $request->input('target_lang'),
                'casual',
                null,
                null,
                null
            );

            // Step 3: Synthesize speech (if requested)
            $outputAudioPath = null;
            $outputDuration = 0;

            if ($request->input('return_audio', true)) {
                $ttsResult = $this->tts->synthesize(
                    $translatedText,
                    $request->input('target_lang'),
                    $request->input('output_voice')
                );

                if ($ttsResult['success'] ?? false) {
                    $outputAudioPath = $ttsResult['audio_path'] ?? null;
                    $outputDuration = $ttsResult['duration'] ?? 0;
                }
            }

            $processingTime = round((microtime(true) - $startTime) * 1000, 2);

            // Create voice translation record
            $voiceTranslation = VoiceTranslation::create([
                'user_id' => $user->id,
                'user_subscription_id' => $subscription->id,
                'audio_file_path' => $audioPath,
                'source_language' => $detectedLang,
                'target_language' => $request->input('target_lang'),
                'audio_duration_seconds' => $audioDuration,
                'audio_file_size' => $audioSize,
                'transcribed_text' => $transcribedText,
                'transcription_confidence' => $confidence,
                'translated_text' => $translatedText,
                'output_audio_path' => $outputAudioPath,
                'voice_name' => $request->input('output_voice'),
                'output_duration_seconds' => $outputDuration,
                'cost' => $cost,
                'pricing_model' => 'per_second',
                'status' => 'completed',
                'processing_time_ms' => $processingTime,
                'ip_address' => $request->ip(),
            ]);

            // Record usage and deduct cost
            $subscription->recordUsage($cost, [
                'service_type' => 'voice_translation',
                'source_lang' => $detectedLang,
                'target_lang' => $request->input('target_lang'),
                'character_count' => mb_strlen($transcribedText),
                'word_count' => str_word_count($transcribedText),
                'from_cache' => false,
                'unit_price' => 0.001,
                'pricing_model' => 'voice_per_second',
                'ip_address' => $request->ip(),
                'metadata' => [
                    'audio_duration' => $audioDuration,
                    'confidence' => $confidence,
                ],
            ]);

            // Prepare response
            $response = [
                'success' => true,
                'data' => [
                    'id' => $voiceTranslation->id,
                    'transcribed_text' => $transcribedText,
                    'translated_text' => $translatedText,
                    'source_language' => $detectedLang,
                    'target_language' => $request->input('target_lang'),
                    'confidence' => $confidence,
                    'audio_duration' => $audioDuration,
                    'processing_time_ms' => $processingTime,
                ],
                'usage' => [
                    'cost' => round($cost, 4),
                    'duration_seconds' => $audioDuration,
                    'monthly_usage' => $subscription->monthly_usage_count,
                ],
                'billing' => [
                    'credit_balance' => round($subscription->credit_balance, 2),
                    'current_balance' => round($subscription->current_balance, 2),
                ],
            ];

            // Add audio URL if available
            if ($outputAudioPath && Storage::disk('local')->exists($outputAudioPath)) {
                $response['data']['audio_url'] = route('voice.audio', ['id' => $voiceTranslation->id]);
                $response['data']['output_duration'] = $outputDuration;
            }

            return response()->json($response);

        } catch (\Exception $e) {
            // Record failed translation
            VoiceTranslation::create([
                'user_id' => $user->id,
                'user_subscription_id' => $subscription->id,
                'audio_file_path' => $audioPath,
                'source_language' => $request->input('source_lang', 'auto'),
                'target_language' => $request->input('target_lang'),
                'audio_duration_seconds' => $audioDuration,
                'audio_file_size' => $audioSize,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'processing_time_ms' => round((microtime(true) - $startTime) * 1000, 2),
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Voice translation failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get voice translation audio
     */
    public function getAudio($id)
    {
        $voiceTranslation = VoiceTranslation::findOrFail($id);

        // Check authorization
        if ($voiceTranslation->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if (!$voiceTranslation->output_audio_path || !Storage::disk('local')->exists($voiceTranslation->output_audio_path)) {
            abort(404, 'Audio file not found');
        }

        $path = Storage::disk('local')->path($voiceTranslation->output_audio_path);
        
        return response()->file($path, [
            'Content-Type' => 'audio/mpeg',
            'Content-Disposition' => 'inline; filename="translation_' . $id . '.mp3"',
        ]);
    }

    /**
     * Get voice translation statistics
     */
    public function getStatistics(Request $request)
    {
        $user = $request->user();
        $period = $request->input('period', 'month');

        $stats = VoiceTranslation::getStatistics($user->id, $period);

        return response()->json([
            'success' => true,
            'period' => $period,
            'statistics' => $stats,
        ]);
    }

    /**
     * Get audio duration in seconds
     */
    private function getAudioDuration($filePath): int
    {
        try {
            // Try using getID3 if available
            if (class_exists('\getID3')) {
                $getID3 = new \getID3();
                $fileInfo = $getID3->analyze($filePath);
                return (int) ($fileInfo['playtime_seconds'] ?? 0);
            }

            // Fallback: estimate based on file size (rough approximation)
            $fileSize = filesize($filePath);
            // Assume 128kbps MP3 = 16KB/second
            return (int) ($fileSize / 16000);

        } catch (\Exception $e) {
            return 0;
        }
    }
}
