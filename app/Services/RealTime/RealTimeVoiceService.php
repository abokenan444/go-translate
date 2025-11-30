<?php

namespace App\Services\RealTime;

use App\Models\RealTimeSession;
use App\Models\RealTimeTurn;
use App\Services\CulturalPromptEngine;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use OpenAI;

class RealTimeVoiceService
{
    public function __construct(
        protected CulturalPromptEngine $culturalEngine,
    ) {}

    /**
     * Process audio turn with cultural integration
     */
    public function processAudioTurn(
        RealTimeSession $session,
        int|string|null $userId,
        string $audioPath,
        string $direction = 'source_to_target'
    ): RealTimeTurn {
        $start = hrtime(true);
        $client = OpenAI::client(config('services.openai.api_key'));

        // Determine languages
        $sourceLang = $direction === 'source_to_target'
            ? $session->source_language
            : $session->target_language;
        $targetLang = $direction === 'source_to_target'
            ? $session->target_language
            : $session->source_language;

        // STT (Speech-to-Text)
        $audioFullPath = Storage::disk('local')->path($audioPath);
        
        try {
            $stt = $client->audio()->transcribe([
                'model' => 'whisper-1',
                'file'  => fopen($audioFullPath, 'r'),
                'response_format' => 'json',
                'language' => $sourceLang,
            ]);
            
            $sourceText = $stt->text ?? '';
            
            if (empty($sourceText)) {
                throw new \Exception('No text transcribed from audio');
            }

        } catch (\Exception $e) {
            Log::error('STT Error: ' . $e->getMessage());
            throw $e;
        }

        // Cultural Translation with adaptive level
        $culturalAdaptationLevel = $session->cultural_adaptation_level ?? 'standard';
        
        try {
            $translationResult = $this->translateWithCulturalContext(
                $session,
                $sourceText,
                $sourceLang,
                $targetLang,
                $direction,
                $culturalAdaptationLevel
            );
            
            $translatedText = $translationResult['translated_text'] ?? $translationResult['output'] ?? $sourceText;
            
        } catch (\Exception $e) {
            Log::error('Cultural Translation Error: ' . $e->getMessage());
            // Fallback to simple translation
            $translatedText = $this->fallbackTranslation($sourceText, $sourceLang, $targetLang);
            $translationResult = ['translated_text' => $translatedText, 'fallback' => true];
        }

        // TTS (Text-to-Speech)
        try {
            $voice = $this->selectVoiceForLanguage($targetLang);
            
            $ttsOutput = $client->audio()->speech([
                'model' => 'tts-1',
                'voice' => $voice,
                'input' => $translatedText,
                'format' => 'mp3',
            ]);

            $translatedAudioPath = 'realtime/' . $session->public_id . '/' . uniqid('tts_', true) . '.mp3';
            Storage::disk('public')->put($translatedAudioPath, $ttsOutput);
            
        } catch (\Exception $e) {
            Log::error('TTS Error: ' . $e->getMessage());
            $translatedAudioPath = null;
        }

        // Calculate latency
        $latencyMs = (int) ((hrtime(true) - $start) / 1_000_000);

        // Save turn
        $turn = new RealTimeTurn();
        $turn->session_id = $session->id;
        $turn->user_id = is_numeric($userId) ? $userId : null;
        $turn->external_id = is_string($userId) && !is_numeric($userId) ? $userId : null;
        $turn->direction = $direction;
        $turn->source_text = $sourceText;
        $turn->translated_text = $translatedText;
        $turn->source_language = $sourceLang;
        $turn->target_language = $targetLang;
        $turn->audio_path_source = $audioPath;
        $turn->audio_path_translated = $translatedAudioPath;
        $turn->latency_ms = $latencyMs;
        $turn->raw_stt = method_exists($stt, 'toArray') ? $stt->toArray() : null;
        $turn->raw_llm = $translationResult;
        $turn->save();

        return $turn;
    }

    /**
     * Translate with cultural context integration
     */
    protected function translateWithCulturalContext(
        RealTimeSession $session,
        string $sourceText,
        string $sourceLang,
        string $targetLang,
        string $direction,
        string $adaptationLevel = 'standard'
    ): array {
        // Get cultural codes
        $sourceCultureCode = $direction === 'source_to_target'
            ? $session->source_culture_code
            : $session->target_culture_code;
        $targetCultureCode = $direction === 'source_to_target'
            ? $session->target_culture_code
            : $session->source_culture_code;

        // Determine cultural adaptation settings based on level
        $settings = $this->getCulturalAdaptationSettings($adaptationLevel);

        // Use CulturalPromptEngine for translation
        try {
            $result = $this->culturalEngine->translateWithCulturalContext(
                $sourceText,
                $sourceLang,
                $targetLang,
                [
                    'formality_level' => $settings['formality_level'],
                    'preserve_tone' => $settings['preserve_tone'],
                    'adapt_idioms' => $settings['adapt_idioms'],
                    'source_culture' => $sourceCultureCode,
                    'target_culture' => $targetCultureCode,
                    'context' => $session->type ?? 'conversation',
                ]
            );

            return [
                'translated_text' => $result,
                'adaptation_level' => $adaptationLevel,
                'settings' => $settings,
            ];
            
        } catch (\Exception $e) {
            Log::error('Cultural Engine Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get cultural adaptation settings based on level
     */
    protected function getCulturalAdaptationSettings(string $level): array
    {
        return match($level) {
            'minimal' => [
                'formality_level' => 'neutral',
                'preserve_tone' => true,
                'adapt_idioms' => false,
            ],
            'standard' => [
                'formality_level' => 'neutral',
                'preserve_tone' => true,
                'adapt_idioms' => true,
            ],
            'high' => [
                'formality_level' => 'formal',
                'preserve_tone' => true,
                'adapt_idioms' => true,
            ],
            'maximum' => [
                'formality_level' => 'formal',
                'preserve_tone' => true,
                'adapt_idioms' => true,
            ],
            default => [
                'formality_level' => 'neutral',
                'preserve_tone' => true,
                'adapt_idioms' => true,
            ],
        };
    }

    /**
     * Select appropriate voice for language
     */
    protected function selectVoiceForLanguage(string $language): string
    {
        return match($language) {
            'ar' => 'alloy',  // OpenAI doesn't have specific Arabic voice, using alloy
            'en' => 'alloy',
            'es' => 'nova',
            'fr' => 'shimmer',
            'de' => 'onyx',
            'it' => 'fable',
            'pt' => 'echo',
            'ja' => 'alloy',
            'ko' => 'alloy',
            'zh' => 'alloy',
            default => 'alloy',
        };
    }

    /**
     * Fallback translation (simple, no cultural context)
     */
    protected function fallbackTranslation(string $text, string $sourceLang, string $targetLang): string
    {
        try {
            $client = OpenAI::client(config('services.openai.api_key'));
            
            $response = $client->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a translator. Translate the following text accurately.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Translate from {$sourceLang} to {$targetLang}: {$text}"
                    ]
                ],
                'max_tokens' => 500,
            ]);

            return $response->choices[0]->message->content ?? $text;
            
        } catch (\Exception $e) {
            Log::error('Fallback Translation Error: ' . $e->getMessage());
            return $text;
        }
    }
}
