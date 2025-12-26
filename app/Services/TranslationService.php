<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\TrainingData;
use Illuminate\Support\Facades\Auth;

class TranslationService
{
    protected $apiKey;
    /**
     * OpenAI model ID (defaults to GPT-5.2; override via OPENAI_MODEL).
     */
    protected string $model;

    /**
     * OpenAI Chat Completions API endpoint.
     */
    protected string $endpoint = 'https://api.openai.com/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
        $this->model = env('OPENAI_MODEL', 'gpt-5.2');
    }

    /**
     * Minimal wrapper around the Chat Completions API.
     *
     * @param array $messages Chat-style messages: [['role'=>'system'|'user'|'assistant','content'=>string], ...]
     * @param int $maxOutputTokens
     * @param float|null $temperature
     */
    protected function callOpenAI(array $messages, int $maxOutputTokens = 1024, ?float $temperature = null): array
    {
        $body = [
            'model' => $this->model,
            'messages' => $messages,
            'max_tokens' => $maxOutputTokens,
        ];

        if ($temperature !== null) {
            $body['temperature'] = $temperature;
        }

        $resp = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(60)->post($this->endpoint, $body);

        return [
            'ok' => $resp->successful(),
            'json' => $resp->successful() ? ($resp->json() ?? []) : [],
            'body' => $resp->body(),
            'status' => $resp->status(),
        ];
    }

    /**
     * Extract output text from Chat Completions API response.
     */
    protected function extractOutputText(array $payload): string
    {
        // Standard Chat Completions API response format
        if (!empty($payload['choices']) && is_array($payload['choices'])) {
            $firstChoice = $payload['choices'][0] ?? null;
            if ($firstChoice && isset($firstChoice['message']['content'])) {
                return $firstChoice['message']['content'];
            }
        }

        return '';
    }

    /**
     * Translate text with cultural context
     */
    public function translateCultural($text, $sourceLang, $targetLang, $context = null, $tone = 'professional')
    {
        $prompt = $this->buildCulturalPrompt($text, $sourceLang, $targetLang, $context, $tone);
        
        try {
            $api = $this->callOpenAI([
                [
                    'role' => 'system',
                    'content' => 'You are a professional cultural translator. Your job is to translate text while preserving cultural context, emotional tone, and brand voice. Always provide natural, culturally appropriate translations that resonate with the target audience.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ],
            ], 2000, 0.3);

            if ($api['ok']) {
                $data = $api['json'];
                $translation = $this->extractOutputText($data);
                
                $result = [
                    'success' => true,
                    'translation' => trim($translation),
                    'translated_text' => trim($translation),
                    'source_text' => $text,
                    'source_language' => $sourceLang,
                    'target_language' => $targetLang,
                    'tone' => $tone,
                    'context' => $context,
                    'word_count' => str_word_count($text ?? ''),
                    // Usage shape differs between APIs; keep it defensive.
                    'total_tokens' => $data['usage']['total_tokens'] ?? ($data['usage']['output_tokens'] ?? 0),
                ];

                // Save training data asynchronously (won't affect response time)
                $this->saveTrainingData($result, $text, $sourceLang, $targetLang, $context, $tone);

                return $result;
            }

            return [
                'success' => false,
                'error' => 'API request failed',
                'message' => $api['body']
            ];
        } catch (\Exception $e) {
            Log::error('Translation error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Translation failed',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Save translation data for training
     */
    protected function saveTrainingData($translationResult, $text, $sourceLang, $targetLang, $context, $tone)
    {
        try {
            // Only save if translation was successful
            if (!$translationResult['success']) {
                return;
            }

            // Check for sensitive data patterns (basic check)
            $containsSensitive = $this->containsSensitiveData($text);

            TrainingData::create([
                'source_text' => $text,
                'source_language' => $sourceLang,
                'target_language' => $targetLang,
                'translated_text' => $translationResult['translated_text'],
                'tone' => $tone,
                'context' => $context,
                'model_used' => $this->model,
                'word_count' => str_word_count($text ?? ''),
                'tokens_used' => $translationResult['total_tokens'] ?? 0,
                'user_id' => Auth::id(),
                'is_suitable_for_training' => !$containsSensitive,
                'contains_sensitive_data' => $containsSensitive,
                'data_quality' => 'pending',
            ]);
        } catch (\Exception $e) {
            // Silently fail - don't affect the translation process
            Log::warning('Failed to save training data: ' . $e->getMessage());
        }
    }

    /**
     * Check if text contains sensitive data
     */
    protected function containsSensitiveData($text)
    {
        // Basic patterns for sensitive data
        $patterns = [
            '/\b\d{4}[\s-]?\d{4}[\s-]?\d{4}[\s-]?\d{4}\b/', // Credit card
            '/\b\d{3}-\d{2}-\d{4}\b/', // SSN
            '/\b\d{10,}\b/', // Phone numbers (10+ digits)
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Build cultural translation prompt
     */
    protected function buildCulturalPrompt($text, $sourceLang, $targetLang, $context, $tone)
    {
        $languageNames = [
            'en' => 'English',
            'ar' => 'Arabic',
            'es' => 'Spanish',
            'fr' => 'French',
            'de' => 'German',
            'it' => 'Italian',
            'pt' => 'Portuguese',
            'ru' => 'Russian',
            'zh' => 'Chinese',
            'ja' => 'Japanese',
            'ko' => 'Korean',
            'tr' => 'Turkish',
            'hi' => 'Hindi',
        ];

        $sourceLanguage = $languageNames[$sourceLang] ?? $sourceLang;
        $targetLanguage = $languageNames[$targetLang] ?? $targetLang;

        $prompt = "Translate the following text from {$sourceLanguage} to {$targetLanguage}.\n\n";
        $prompt .= "**Important Instructions:**\n";
        $prompt .= "- Preserve the cultural context and emotional tone\n";
        $prompt .= "- Adapt idioms and expressions to be culturally appropriate\n";
        $prompt .= "- Maintain the brand voice and style\n";
        $prompt .= "- Use natural, native-sounding language\n";
        $prompt .= "- Keep the same level of formality: {$tone}\n";
        
        if ($context) {
            $prompt .= "- Context: {$context}\n";
        }
        
        $prompt .= "\n**Text to translate:**\n{$text}\n\n";
        $prompt .= "**Translation:**";

        return $prompt;
    }

    /**
     * Detect language
     */
    public function detectLanguage($text)
    {
        try {
            $api = $this->callOpenAI([
                [
                    'role' => 'system',
                    'content' => 'You are a language detection expert. Respond with only the ISO 639-1 language code (e.g., en, ar, es, fr).'
                ],
                [
                    'role' => 'user',
                    'content' => "Detect the language of this text and respond with only the language code:\n\n{$text}"
                ]
            ], 10, 0.0);

            if ($api['ok']) {
                $data = $api['json'];
                $langCode = trim($this->extractOutputText($data) ?: 'en');
                return strtolower($langCode);
            }

            return 'en'; // Default to English
        } catch (\Exception $e) {
            Log::error('Language detection error: ' . $e->getMessage());
            return 'en';
        }
    }

    /**
     * Get supported languages
     */
    public function getSupportedLanguages()
    {
        return [
            'en' => 'English',
            'ar' => 'العربية',
            'es' => 'Español',
            'fr' => 'Français',
            'de' => 'Deutsch',
            'it' => 'Italiano',
            'pt' => 'Português',
            'ru' => 'Русский',
            'zh' => '中文',
            'ja' => '日本語',
            'ko' => '한국어',
            'tr' => 'Türkçe',
            'hi' => 'हिन्दी',
        ];
    }

    /**
     * Get tone options
     */
    public function getToneOptions()
    {
        return [
            'professional' => 'Professional',
            'casual' => 'Casual',
            'formal' => 'Formal',
            'friendly' => 'Friendly',
            'technical' => 'Technical',
            'marketing' => 'Marketing',
            'creative' => 'Creative',
        ];
    }
}
