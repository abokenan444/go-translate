<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SimpleDemoTranslationService
{
    protected $client;
    
    // Language names mapping
    protected $languageNames = [
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
        'nl' => 'Dutch',
        'hi' => 'Hindi',
        'pl' => 'Polish',
    ];
    
    public function __construct()
    {
        $apiKey = env('OPENAI_API_KEY');
        $this->client = \OpenAI::client($apiKey);
    }
    
    /**
     * Simple translation for demo
     */
    public function translate(array $params): array
    {
        $startTime = microtime(true);
        
        $text = $params['text'];
        $sourceLang = $params['source_language'];
        $targetLang = $params['target_language'];
        $tone = $params['tone'] ?? 'professional';
        
        // Get language names
        $sourceName = $this->languageNames[$sourceLang] ?? $sourceLang;
        $targetName = $this->languageNames[$targetLang] ?? $targetLang;
        
        // Check cache
        $cacheKey = 'demo_trans_' . md5($text . $sourceLang . $targetLang . $tone);
        $cached = Cache::get($cacheKey);
        
        if ($cached) {
            Log::info('Demo translation from cache', ['key' => $cacheKey]);
            return [
                'success' => true,
                'translated_text' => $cached,
                'source_language' => $sourceLang,
                'target_language' => $targetLang,
                'tone' => $tone,
                'word_count' => str_word_count($text),
                'tokens_used' => 0,
                'quality_score' => 95.0,
                'cached' => true,
                'response_time_ms' => round((microtime(true) - $startTime) * 1000, 2),
            ];
        }
        
        try {
            // Build simple but effective prompt
            $systemPrompt = "You are a professional translator. Translate the given text from {$sourceName} to {$targetName}.";
            $systemPrompt .= "\n\nIMPORTANT RULES:";
            $systemPrompt .= "\n1. Output ONLY the translated text, nothing else";
            $systemPrompt .= "\n2. Maintain the same tone and style";
            $systemPrompt .= "\n3. Preserve formatting and punctuation";
            $systemPrompt .= "\n4. Do NOT add explanations or notes";
            $systemPrompt .= "\n5. Do NOT translate proper nouns or brand names";
            
            if ($tone === 'professional') {
                $systemPrompt .= "\n6. Use formal and professional language";
            } elseif ($tone === 'casual') {
                $systemPrompt .= "\n6. Use casual and friendly language";
            }
            
            $userPrompt = "Translate this text from {$sourceName} to {$targetName}:\n\n{$text}";
            
            Log::info('Sending translation request', [
                'source' => $sourceLang,
                'target' => $targetLang,
                'text_length' => strlen($text)
            ]);
            
            // Use gpt-3.5-turbo for demo (faster and cheaper)
            $response = $this->client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt
                    ],
                    [
                        'role' => 'user',
                        'content' => $userPrompt
                    ]
                ],
                'temperature' => 0.3,
                'max_tokens' => 1000,
            ]);
            
            $translatedText = trim($response->choices[0]->message->content ?? '');
            $tokensUsed = $response->usage->totalTokens ?? 0;
            
            Log::info('Translation received', [
                'tokens' => $tokensUsed,
                'result_length' => strlen($translatedText)
            ]);
            
            // Simple quality check
            $qualityScore = $this->calculateQuality($text, $translatedText);
            
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Cache for 1 hour
            Cache::put($cacheKey, $translatedText, now()->addHour());
            
            return [
                'success' => true,
                'translated_text' => $translatedText,
                'source_language' => $sourceLang,
                'target_language' => $targetLang,
                'tone' => $tone,
                'word_count' => str_word_count($text),
                'tokens_used' => $tokensUsed,
                'quality_score' => $qualityScore,
                'cached' => false,
                'response_time_ms' => $responseTime,
            ];
            
        } catch (\Exception $e) {
            Log::error('Translation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'response_time_ms' => round((microtime(true) - $startTime) * 1000, 2),
            ];
        }
    }
    
    /**
     * Simple quality score calculation
     */
    protected function calculateQuality($original, $translated): float
    {
        // Basic quality metrics
        $score = 100.0;
        
        // Check if translation is not empty
        if (empty($translated)) {
            return 0.0;
        }
        
        // Check if translation is different from original
        if ($original === $translated) {
            $score -= 50;
        }
        
        // Check length ratio (should be within reasonable bounds)
        $lengthRatio = strlen($translated) / strlen($original);
        if ($lengthRatio < 0.3 || $lengthRatio > 3.0) {
            $score -= 20;
        }
        
        return max(0, min(100, $score));
    }
}
