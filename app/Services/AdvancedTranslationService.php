<?php

namespace App\Services;

use OpenAI\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client as GuzzleClient;

class AdvancedTranslationService
{
    protected $client;
    protected $culturalEngine;
    protected $cacheManager;
    
    public function __construct()
    {
        // Prefer config-based access (works reliably when config is cached), then fall back to env
        $apiKey = config('openai.api_key') ?: (config('services.openai.key') ?: env('OPENAI_API_KEY'));
        if (is_string($apiKey)) {
            $apiKey = trim($apiKey);
        }

        if (empty($apiKey)) {
            // Don't attempt to construct the OpenAI client when no API key is configured.
            // This prevents a TypeError during container resolution and lets callers
            // receive a controlled error response.
            $this->client = null;
        } else {
            try {
                $this->client = \OpenAI::client($apiKey);
            } catch (\Throwable $e) {
                // If default client construction fails (often due to SSL certs on Windows),
                // attempt to build a client with a custom CA bundle when provided via env.
                $this->client = $this->makeClientWithCustomCA($apiKey);
            }
        }

        $this->culturalEngine = new CulturalAdaptationEngine();
        $this->cacheManager = new TranslationCacheManager();
    }

    /**
     * Attempt to build an OpenAI client using a custom CA bundle path.
     * Honors env variables commonly used for CA bundles: SSL_CERT_FILE, CURL_CA_BUNDLE, CACERT_PATH.
     */
    protected function makeClientWithCustomCA(string $apiKey): ?Client
    {
        try {
            $caPath = env('SSL_CERT_FILE') ?? env('CURL_CA_BUNDLE') ?? env('CACERT_PATH');
            if (!$caPath || !is_string($caPath) || !file_exists($caPath)) {
                return null;
            }

            $timeout = (int) (config('openai.request_timeout', 30) ?? 30);

            $factory = \OpenAI::factory()->withApiKey($apiKey);

            // Optionally apply base URI / org / project if configured
            $baseUri = config('openai.base_uri');
            if ($baseUri) {
                $factory = $factory->withBaseUri($baseUri);
            }

            $organization = config('openai.organization');
            if ($organization) {
                $factory = $factory->withOrganization($organization);
            }

            $project = config('openai.project');
            if ($project) {
                $factory = $factory->withProject($project);
            }

            $httpClient = new GuzzleClient([
                'verify' => $caPath,
                'timeout' => $timeout,
            ]);

            return $factory->withHttpClient($httpClient)->make();
        } catch (\Throwable $e) {
            return null;
        }
    }
    
    /**
     * Advanced translation with cultural adaptation
     */
    public function translate(array $params): array
    {
        $startTime = microtime(true);
        // If the translation provider isn't configured, return a JSON-friendly error.
        if (is_null($this->client)) {
            return [
                'success' => false,
                'error' => 'Translation provider not configured. Set OPENAI_API_KEY in your .env or configure services.openai.key.',
                'response_time_ms' => round((microtime(true) - $startTime) * 1000, 2),
            ];
        }
        
        // Extract parameters
        $text = $params['text'];
        $sourceLang = $params['source_language'];
        $targetLang = $params['target_language'];
        $tone = $params['tone'] ?? 'professional';
        $industry = $params['industry'] ?? null;
        $taskType = $params['task_type'] ?? null;
        $context = $params['context'] ?? null;
        $targetCulture = $params['target_culture'] ?? null;
        $smartCorrect = (bool)($params['smart_correct'] ?? false);
        $companyId = $params['company_id'] ?? null;

        // Company feature flags & allowed models
        $allowedModels = null;
        $enabledFeatures = null;
        if ($companyId) {
            try {
                $settings = \App\Models\CompanySetting::where('company_id', $companyId)->first();
                if ($settings) {
                    $allowedModels = $settings->allowed_models ?? null;
                    $enabledFeatures = $settings->enabled_features ?? null;
                }
            } catch (\Throwable $e) {
                // ignore
            }
        }
        
        // Check cache first
        $cacheKey = $this->cacheManager->generateKey($text, $sourceLang, $targetLang, $tone, $industry);
        $cached = $this->cacheManager->get($cacheKey);
        
        if ($cached) {
            return [
                'success' => true,
                'translated_text' => $cached['translated_text'],
                'source_language' => $sourceLang,
                'target_language' => $targetLang,
                'tone' => $tone,
                'word_count' => str_word_count($text),
                'tokens_used' => 0, // Cached, no tokens used
                'cached' => true,
                'response_time_ms' => round((microtime(true) - $startTime) * 1000, 2),
            ];
        }
        
        try {
            // Optional smart correction step (spelling/grammar) on source text
            $correctedText = null;
            if ($smartCorrect) {
                try {
                    $correction = $this->client->chat()->create([
                        'model' => 'gpt-4o-mini',
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => 'You correct spelling and grammar for the given language while preserving meaning. Respond ONLY with the corrected text.'
                            ],
                            [
                                'role' => 'user',
                                'content' => ($sourceLang === 'auto' ? '' : "Language: $sourceLang\n") . "Text:\n" . $text
                            ]
                        ],
                        'temperature' => 0.1,
                        'max_completion_tokens' => 1200,
                    ]);
                    if (is_array($correction)) {
                        $correctedText = trim($correction['choices'][0]['message']['content'] ?? '') ?: null;
                    } else {
                        $correctedText = trim($correction->choices[0]->message->content ?? '') ?: null;
                    }
                    if (!empty($correctedText)) {
                        $text = $correctedText;
                    }
                } catch (\Throwable $e) {
                    // Ignore correction failures and proceed with original text
                }
            }
            // Load cultural profiles
            $sourceProfile = $this->culturalEngine->getProfile($sourceLang);
            $targetProfile = $targetCulture
                ? ($this->culturalEngine->getProfile($targetCulture) ?? $this->culturalEngine->getProfile($targetLang))
                : $this->culturalEngine->getProfile($targetLang);
            
            // Load emotional tone
            $toneProfile = $this->culturalEngine->getTone($tone);
            
            // Load industry template if provided
            $industryTemplate = $industry ? $this->culturalEngine->getIndustry($industry) : null;
            
            // Load task template if provided
            $taskTemplate = $taskType ? $this->culturalEngine->getTaskTemplate($taskType) : null;
            
            // Build advanced prompt
            $prompt = $this->buildAdvancedPrompt([
                'text' => $text,
                'source_profile' => $sourceProfile,
                'target_profile' => $targetProfile,
                'tone_profile' => $toneProfile,
                'industry_template' => $industryTemplate,
                'task_template' => $taskTemplate,
                'context' => $context,
                'source_language' => $sourceLang,
                'target_language' => $targetLang,
            ]);
            
            // Call OpenAI API
            // Choose model respecting company allowed models if provided
            $model = 'gpt-4o-mini';
            if (is_array($allowedModels) && count($allowedModels) > 0) {
                // pick first allowed model for simplicity
                $model = $allowedModels[0];
            }

            $response = $this->client->chat()->create([
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $prompt['system_message']
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt['user_message']
                    ]
                ],
                'temperature' => 0.3,
                'max_completion_tokens' => 4000,
            ]);

            // Support both array and object response shapes from openai-php
            if (is_array($response)) {
                $translatedText = trim($response['choices'][0]['message']['content'] ?? '');
                $tokensUsed = $response['usage']['total_tokens'] ?? 0;
            } else {
                $translatedText = trim($response->choices[0]->message->content ?? '');
                $tokensUsed = $response->usage->totalTokens ?? 0;
            }
            
            // Post-processing
            $translatedText = $this->postProcess($translatedText, $targetProfile);

            // Apply company features (e.g., brand voice/glossary could be applied elsewhere)
            // Placeholder: future hooks can use $enabledFeatures array
            
            // Quality check
            $qualityScore = $this->calculateQuality($text, $translatedText, $sourceLang, $targetLang);
            
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Cache the result
            $this->cacheManager->store($cacheKey, [
                'source_text' => $text,
                'source_language' => $sourceLang,
                'target_language' => $targetLang,
                'tone' => $tone,
                'industry' => $industry,
                'translated_text' => $translatedText,
                'quality_score' => $qualityScore,
                'tokens_used' => $tokensUsed,
                'response_time_ms' => $responseTime,
            ]);
            
            return [
                'success' => true,
                'translated_text' => $translatedText,
                'corrected_text' => $correctedText,
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
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'response_time_ms' => round((microtime(true) - $startTime) * 1000, 2),
            ];
        }
    }
    
    /**
     * Build advanced culturally-aware prompt
     */
    protected function buildAdvancedPrompt(array $data): array
    {
        $sourceProfile = $data['source_profile'];
        $targetProfile = $data['target_profile'];
        $toneProfile = $data['tone_profile'];
        $industryTemplate = $data['industry_template'];
        $taskTemplate = $data['task_template'];
        $context = $data['context'];
        $text = $data['text'];
        $sourceLangCode = $data['source_language'] ?? null;
        $targetLangCode = $data['target_language'] ?? null;
        
        // Build system message
        $systemParts = [];
        
        // Base instruction
        $systemParts[] = "You are CulturalTranslate, an advanced AI translation engine specialized in cultural adaptation.";
        
        // Target culture instructions
        if ($targetProfile) {
            $systemParts[] = "\n**Target Culture:** " . $targetProfile['culture_name'];
            $systemParts[] = $targetProfile['system_prompt'];
            $systemParts[] = "\n**Translation Guidelines:** " . $targetProfile['translation_guidelines'];
        } else if ($targetLangCode) {
            // Fallback when no specific culture profile is available
            $systemParts[] = "\n**Target Language:** " . $targetLangCode;
            $systemParts[] = "If no specific culture is provided, translate for a general " . $targetLangCode . " audience.";
            $systemParts[] = "Do not ask for additional details. Choose reasonable defaults and proceed with the translation.";
        }
        
        // Tone instructions
        if ($toneProfile) {
            $systemParts[] = "\n**Tone:** " . $toneProfile['tone_name_en'];
            $systemParts[] = $toneProfile['system_instructions'];
        }
        
        // Industry-specific instructions
        if ($industryTemplate) {
            $systemParts[] = "\n**Industry:** " . $industryTemplate['industry_name_en'];
            $systemParts[] = $industryTemplate['system_prompt'];
        }
        
        // Task-specific instructions
        if ($taskTemplate) {
            $systemParts[] = "\n**Task Type:** " . $taskTemplate['task_name_en'];
            $systemParts[] = $taskTemplate['system_prompt'];
        }
        
        $systemMessage = implode("\n", $systemParts);
        
        // Build user message
        $userMessage = "Translate the following text with full cultural adaptation:\n\n";
        
        if ($context) {
            $userMessage .= "**Context:** $context\n\n";
        }
        
        $userMessage .= "**Text to translate:**\n$text\n\n";
        $userMessage .= "Provide ONLY the translated text without any explanations or notes. Do not request additional information.";
        
        return [
            'system_message' => $systemMessage,
            'user_message' => $userMessage,
        ];
    }
    
    /**
     * Post-process translated text
     */
    protected function postProcess(string $text, ?array $targetProfile): string
    {
        // Remove common AI artifacts
        $text = preg_replace('/^(Here is the translation:|Translation:|Translated text:)/i', '', $text);
        $text = preg_replace('/\n\n+/', "\n\n", $text);
        $text = trim($text);
        
        // Apply text direction if needed
        if ($targetProfile && $targetProfile['text_direction'] === 'rtl') {
            // Add RTL markers if needed
            $text = "\u{202B}" . $text . "\u{202C}";
        }
        
        return $text;
    }
    
    /**
     * Calculate translation quality score
     */
    protected function calculateQuality(string $source, string $translation, string $sourceLang, string $targetLang): float
    {
        $score = 100;
        
        // Length ratio check (translation shouldn't be too different in length)
        $sourceLen = mb_strlen($source);
        $transLen = mb_strlen($translation);
        $ratio = $transLen / max($sourceLen, 1);
        
        if ($ratio < 0.5 || $ratio > 2.0) {
            $score -= 20;
        }
        
        // Check for untranslated text (same as source)
        if ($source === $translation) {
            $score -= 50;
        }
        
        // Check for empty translation
        if (empty(trim($translation))) {
            $score = 0;
        }
        
        return max(0, min(100, $score));
    }
    
    /**
     * Detect language of text
     */
    public function detectLanguage(string $text): array
    {
        if (is_null($this->client)) {
            return [
                'success' => false,
                'error' => 'Language detection provider not configured. Set OPENAI_API_KEY.',
                'language' => 'en',
            ];
        }
        try {
            $response = $this->client->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a language detection expert. Respond with ONLY the ISO 639-1 language code (e.g., en, ar, fr).'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Detect the language of this text:\n\n$text"
                    ]
                ],
                'temperature' => 0.1,
                'max_completion_tokens' => 10,
            ]);

            if (is_array($response)) {
                $detectedLang = trim($response['choices'][0]['message']['content'] ?? 'en');
            } else {
                $detectedLang = trim($response->choices[0]->message->content ?? 'en');
            }
            
            return [
                'success' => true,
                'language' => $detectedLang,
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'language' => 'en',
            ];
        }
    }
}
