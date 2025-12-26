<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PremiumTranslationService
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
    
    // Cultural context for each language
    protected $culturalContext = [
        'ar' => 'Arabic culture values formality, respect, and indirect communication. Use appropriate honorifics.',
        'zh' => 'Chinese culture emphasizes harmony, respect for hierarchy, and indirect communication.',
        'ja' => 'Japanese culture values politeness, humility, and context-dependent communication.',
        'ko' => 'Korean culture emphasizes respect for hierarchy and formal language levels.',
        'es' => 'Spanish culture varies by region but generally values warmth and directness.',
        'fr' => 'French culture values eloquence, formality in business, and precision.',
        'de' => 'German culture values directness, precision, and formality in professional settings.',
        'it' => 'Italian culture values expressiveness, warmth, and relationship-building.',
        'pt' => 'Portuguese culture values warmth, hospitality, and personal connections.',
        'ru' => 'Russian culture values directness, formality in business, and emotional expression.',
        'tr' => 'Turkish culture values hospitality, respect for elders, and indirect communication.',
        'nl' => 'Dutch culture values directness, egalitarianism, and practical communication.',
        'hi' => 'Hindi culture values respect, formality with elders, and indirect communication.',
        'pl' => 'Polish culture values politeness, formality, and respect for tradition.',
    ];
    
    public function __construct()
    {
        $apiKey = env('OPENAI_API_KEY');
        $this->client = \OpenAI::client($apiKey);
    }
    
    /**
     * Premium translation with cultural adaptation
     * 
     * @param array $params Translation parameters
     * @param string $planType User's subscription plan (basic, professional, enterprise)
     * @return array
     */
    public function translate(array $params, string $planType = 'basic'): array
    {
        $startTime = microtime(true);
        
        $text = $params['text'];
        $sourceLang = $params['source_language'];
        $targetLang = $params['target_language'];
        $tone = $params['tone'] ?? 'professional';
        $context = $params['context'] ?? null;
        $industry = $params['industry'] ?? null;
        
        // Select model based on plan
        $model = $this->selectModel($planType);
        
        // Get language names
        $sourceName = $this->languageNames[$sourceLang] ?? $sourceLang;
        $targetName = $this->languageNames[$targetLang] ?? $targetLang;
        
        // Check cache (only for basic plan)
        $cacheKey = null;
        if ($planType === 'basic') {
            $cacheKey = 'premium_trans_' . md5($text . $sourceLang . $targetLang . $tone . $context . $industry);
            $cached = Cache::get($cacheKey);
            
            if ($cached) {
                Log::info('Premium translation from cache', ['plan' => $planType]);
                return [
                    'success' => true,
                    'translated_text' => $cached['text'],
                    'source_language' => $sourceLang,
                    'target_language' => $targetLang,
                    'tone' => $tone,
                    'word_count' => str_word_count($text),
                    'tokens_used' => 0,
                    'quality_score' => $cached['quality'],
                    'model_used' => $model,
                    'cached' => true,
                    'response_time_ms' => round((microtime(true) - $startTime) * 1000, 2),
                ];
            }
        }
        
        try {
            // Build advanced prompt with cultural context
            $systemPrompt = $this->buildSystemPrompt($sourceName, $targetName, $targetLang, $tone, $industry);
            $userPrompt = $this->buildUserPrompt($text, $sourceName, $targetName, $context);
            
            Log::info('Premium translation request', [
            Log::info("System prompt", ["prompt" => substr($systemPrompt, 0, 500)]);
                'plan' => $planType,
                'model' => $model,
                'source' => $sourceLang,
                'target' => $targetLang,
                'text_length' => strlen($text),
                'has_context' => !empty($context),
                'has_industry' => !empty($industry)
            ]);
            
            // Call OpenAI with selected model
            $response = $this->client->chat()->create([
                'model' => $model,
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
                'max_tokens' => 2000,
            ]);
            
            $translatedText = trim($response->choices[0]->message->content ?? '');
            $tokensUsed = $response->usage->totalTokens ?? 0;
            
            Log::info('Premium translation received', [
                'plan' => $planType,
                'model' => $model,
                'tokens' => $tokensUsed,
                'result_length' => strlen($translatedText)
            ]);
            
            // Calculate quality score
            $qualityScore = $this->calculateQuality($text, $translatedText, $planType);
            
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Cache for basic plan only (1 hour)
            if ($planType === 'basic' && $cacheKey) {
                Cache::put($cacheKey, [
                    'text' => $translatedText,
                    'quality' => $qualityScore
                ], now()->addHour());
            }
            
            return [
                'success' => true,
                'translated_text' => $translatedText,
                'source_language' => $sourceLang,
                'target_language' => $targetLang,
                'tone' => $tone,
                'word_count' => str_word_count($text),
                'tokens_used' => $tokensUsed,
                'quality_score' => $qualityScore,
                'model_used' => $model,
                'cached' => false,
                'response_time_ms' => $responseTime,
            ];
            
        } catch (\Exception $e) {
            Log::error('Premium translation failed', [
                'plan' => $planType,
                'model' => $model,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'model_used' => $model,
                'response_time_ms' => round((microtime(true) - $startTime) * 1000, 2),
            ];
        }
    }
    
    /**
     * Select AI model based on subscription plan
     */
    protected function selectModel(string $planType): string
    {
        switch ($planType) {
            case 'enterprise':
                return 'gpt-5'; // Latest and most capable
            case 'professional':
                return 'gpt-5'; // High quality
            case 'basic':
            default:
                return 'gpt-5'; // Still good quality for basic
        }
    }
    
    /**
     * Build advanced system prompt with cultural context
     */
    protected function buildSystemPrompt(string $sourceName, string $targetName, string $targetLang, string $tone, ?string $industry): string
    {
        $prompt = "You are CulturalTranslate, an advanced AI translation engine specialized in cultural adaptation and context-aware translation.\n\n";
        $prompt .= "**Translation Task:**\n";
        $prompt .= "Translate the following text from {$sourceName} to {$targetName} ({$targetLang}).\n";
        $prompt .= "CRITICAL: Output MUST be in {$targetName} ({$targetLang}) language ONLY. NO exceptions.\n\n";
        
        // Add cultural context
        if (isset($this->culturalContext[$targetLang])) {
            $prompt .= "**Cultural Context for {$targetName}:**\n";
            $prompt .= $this->culturalContext[$targetLang] . "\n\n";
        }
        
        // Add tone instructions
        $prompt .= "**Tone:** ";
        switch ($tone) {
            case 'professional':
                $prompt .= "Use formal, professional language appropriate for business communication.\n";
                break;
            case 'casual':
                $prompt .= "Use casual, friendly language while maintaining respect.\n";
                break;
            case 'technical':
                $prompt .= "Use precise technical language, maintaining accuracy of terminology.\n";
                break;
            case 'marketing':
                $prompt .= "Use persuasive, engaging language that resonates with the target audience.\n";
                break;
            default:
                $prompt .= "Use appropriate language for the context.\n";
        }
        
        // Add industry-specific instructions
        if ($industry) {
            $prompt .= "\n**Industry Context:** {$industry}\n";
            $prompt .= "Use industry-specific terminology and conventions.\n";
        }
        
        $prompt .= "\n**CRITICAL RULES:**\n";
        $prompt .= "1. Output ONLY the translated text in {$targetName}\n";
        $prompt .= "2. Do NOT add explanations, notes, or meta-commentary\n";
        $prompt .= "3. Preserve formatting, line breaks, and punctuation style\n";
        $prompt .= "4. Adapt idioms and expressions to cultural equivalents\n";
        $prompt .= "5. Maintain the same level of formality as the source\n";
        $prompt .= "6. Keep proper nouns, brand names, and technical terms unchanged unless culturally inappropriate\n";
        $prompt .= "7. Ensure natural flow and readability in the target language\n";
        
        return $prompt;
    }
    
    /**
     * Build user prompt
     */
    protected function buildUserPrompt(string $text, string $sourceName, string $targetName, ?string $context): string
    {
        $prompt = "Translate the following text from {$sourceName} to {$targetName}";
        
        if ($context) {
            $prompt .= " with this context in mind:\n\n**Context:** {$context}\n";
        } else {
            $prompt .= ":\n";
        }
        
        $prompt .= "\n**Text to translate:**\n{$text}\n";
        $prompt .= "\n**Translation:**";
        
        return $prompt;
    }
    
    /**
     * Calculate quality score based on various metrics
     */
    protected function calculateQuality(string $original, string $translated, string $planType): float
    {
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
        if ($lengthRatio < 0.3 || $lengthRatio > 3.5) {
            $score -= 15;
        }
        
        // Bonus for premium plans (better models = higher baseline quality)
        if ($planType === 'professional') {
            $score = min(100, $score + 2);
        } elseif ($planType === 'enterprise') {
            $score = min(100, $score + 5);
        }
        
        return max(0, min(100, $score));
    }
}
