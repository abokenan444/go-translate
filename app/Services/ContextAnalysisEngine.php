<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * Advanced Context Analysis Engine
 * Analyzes text in 7 layers: Domain, Formality, Audience, Cultural Sensitivity, Idioms, References, Intent
 */
class ContextAnalysisEngine
{
    protected string $apiKey;
    protected string $model;
    
    public function __construct()
    {
        $this->apiKey = config('services.openai.key');
        $this->model = config('services.openai.model', 'gpt-5');
    }
    
    /**
     * Analyze text context in 7 layers
     *
     * @param string $text
     * @param string $sourceLanguage
     * @param string $targetLanguage
     * @return array
     */
    public function analyzeContext(string $text, string $sourceLanguage, string $targetLanguage): array
    {
        // Check cache first
        $cacheKey = $this->getCacheKey($text, $sourceLanguage, $targetLanguage);
        
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        try {
            $analysis = [
                'layer_1_domain' => $this->analyzeDomain($text),
                'layer_2_formality' => $this->analyzeFormalityLevel($text),
                'layer_3_audience' => $this->analyzeTargetAudience($text),
                'layer_4_cultural_sensitivity' => $this->analyzeCulturalSensitivity($text, $sourceLanguage, $targetLanguage),
                'layer_5_idioms' => $this->detectIdioms($text, $sourceLanguage),
                'layer_6_cultural_references' => $this->detectCulturalReferences($text, $sourceLanguage),
                'layer_7_intent' => $this->analyzeIntent($text)
            ];
            
            // Add comprehensive summary
            $analysis['summary'] = $this->generateContextSummary($analysis);
            $analysis['recommendations'] = $this->generateTranslationRecommendations($analysis, $targetLanguage);
            
            // Cache for 1 hour
            Cache::put($cacheKey, $analysis, 3600);
            
            return $analysis;
            
        } catch (\Exception $e) {
            Log::error('Context analysis failed', [
                'text' => substr($text, 0, 100),
                'error' => $e->getMessage()
            ]);
            
            return $this->getDefaultAnalysis();
        }
    }
    
    /**
     * Layer 1: Analyze domain/field
     *
     * @param string $text
     * @return array
     */
    private function analyzeDomain(string $text): array
    {
        $prompt = <<<PROMPT
Analyze the following text and identify its domain/field. Choose from:
- legal (contracts, terms, legal documents)
- medical (healthcare, clinical, pharmaceutical)
- technical (IT, engineering, specifications)
- marketing (advertising, promotional, sales)
- academic (research, educational, scholarly)
- business (corporate, finance, management)
- government (official, administrative, policy)
- literary (creative, narrative, artistic)
- general (everyday, conversational)

Text: "{$text}"

Respond with JSON format:
{
    "primary_domain": "domain_name",
    "confidence": 0.95,
    "secondary_domains": ["domain2", "domain3"],
    "domain_indicators": ["keyword1", "keyword2"],
    "specialized_terminology": ["term1", "term2"]
}
PROMPT;
        
        $response = $this->callAI($prompt);
        return $this->parseJsonResponse($response, [
            'primary_domain' => 'general',
            'confidence' => 0.5,
            'secondary_domains' => [],
            'domain_indicators' => [],
            'specialized_terminology' => []
        ]);
    }
    
    /**
     * Layer 2: Analyze formality level
     *
     * @param string $text
     * @return array
     */
    private function analyzeFormalityLevel(string $text): array
    {
        $prompt = <<<PROMPT
Analyze the formality level of this text. Rate on scale:
- very_formal (official documents, legal)
- formal (business, professional)
- semi_formal (educated conversation)
- informal (casual, friendly)
- very_informal (slang, colloquial)

Text: "{$text}"

Respond with JSON:
{
    "formality_level": "formal",
    "score": 0.85,
    "indicators": ["uses professional vocabulary", "no contractions"],
    "tone": "respectful",
    "register": "professional"
}
PROMPT;
        
        $response = $this->callAI($prompt);
        return $this->parseJsonResponse($response, [
            'formality_level' => 'semi_formal',
            'score' => 0.5,
            'indicators' => [],
            'tone' => 'neutral',
            'register' => 'standard'
        ]);
    }
    
    /**
     * Layer 3: Analyze target audience
     *
     * @param string $text
     * @return array
     */
    private function analyzeTargetAudience(string $text): array
    {
        $prompt = <<<PROMPT
Identify the target audience for this text:

Categories:
- experts (professionals, specialists)
- educated_general (university-level understanding)
- general_public (average adult)
- youth (teenagers, young adults)
- children (under 12)

Text: "{$text}"

Respond with JSON:
{
    "primary_audience": "general_public",
    "age_range": "18-65",
    "education_level": "high_school_plus",
    "expertise_required": "none",
    "cultural_background": "any",
    "audience_indicators": ["simple language", "explanatory tone"]
}
PROMPT;
        
        $response = $this->callAI($prompt);
        return $this->parseJsonResponse($response, [
            'primary_audience' => 'general_public',
            'age_range' => 'any',
            'education_level' => 'general',
            'expertise_required' => 'none',
            'cultural_background' => 'any',
            'audience_indicators' => []
        ]);
    }
    
    /**
     * Layer 4: Analyze cultural sensitivity
     *
     * @param string $text
     * @param string $sourceLanguage
     * @param string $targetLanguage
     * @return array
     */
    private function analyzeCulturalSensitivity(string $text, string $sourceLanguage, string $targetLanguage): array
    {
        $prompt = <<<PROMPT
Analyze cultural sensitivity considerations when translating from {$sourceLanguage} to {$targetLanguage}:

Text: "{$text}"

Identify:
- Religious references or sensitivities
- Political sensitivities
- Gender/social sensitivities
- Taboo topics
- Cultural values conflicts
- Offensive content risk

Respond with JSON:
{
    "sensitivity_level": "high|medium|low",
    "risk_areas": ["religion", "politics"],
    "sensitive_phrases": [{"phrase": "...", "reason": "...", "severity": "high"}],
    "cultural_conflicts": ["individualism vs collectivism"],
    "adaptation_required": true,
    "recommendations": ["avoid literal translation", "use cultural equivalent"]
}
PROMPT;
        
        $response = $this->callAI($prompt);
        return $this->parseJsonResponse($response, [
            'sensitivity_level' => 'low',
            'risk_areas' => [],
            'sensitive_phrases' => [],
            'cultural_conflicts' => [],
            'adaptation_required' => false,
            'recommendations' => []
        ]);
    }
    
    /**
     * Layer 5: Detect idioms and expressions
     *
     * @param string $text
     * @param string $language
     * @return array
     */
    private function detectIdioms(string $text, string $language): array
    {
        $prompt = <<<PROMPT
Identify all idioms, expressions, and figurative language in this {$language} text:

Text: "{$text}"

For each idiom found, provide:
- The idiom
- Literal meaning
- Figurative meaning
- Cultural origin
- Suggested translation approach

Respond with JSON:
{
    "idioms_found": [
        {
            "expression": "break the ice",
            "literal_meaning": "physically breaking ice",
            "figurative_meaning": "initiate conversation in awkward situation",
            "cultural_origin": "social gathering customs",
            "translation_approach": "find cultural equivalent or explain concept"
        }
    ],
    "total_count": 1,
    "complexity": "medium"
}
PROMPT;
        
        $response = $this->callAI($prompt);
        return $this->parseJsonResponse($response, [
            'idioms_found' => [],
            'total_count' => 0,
            'complexity' => 'low'
        ]);
    }
    
    /**
     * Layer 6: Detect cultural references
     *
     * @param string $text
     * @param string $language
     * @return array
     */
    private function detectCulturalReferences(string $text, string $language): array
    {
        $prompt = <<<PROMPT
Identify cultural references in this {$language} text:

Types to look for:
- Historical events
- Pop culture (movies, music, celebrities)
- Local customs/traditions
- Sports references
- Food/cuisine references
- Geographic/location-specific
- Religious/mythological
- Literary references

Text: "{$text}"

Respond with JSON:
{
    "cultural_references": [
        {
            "reference": "winning the lottery",
            "type": "metaphor",
            "cultural_context": "Western concept of sudden wealth",
            "universality": "medium",
            "translation_challenge": "may not resonate in cultures without lotteries",
            "suggested_approach": "adapt to local equivalent or explain"
        }
    ],
    "total_count": 1,
    "adaptation_priority": "high"
}
PROMPT;
        
        $response = $this->callAI($prompt);
        return $this->parseJsonResponse($response, [
            'cultural_references' => [],
            'total_count' => 0,
            'adaptation_priority' => 'low'
        ]);
    }
    
    /**
     * Layer 7: Analyze intent and purpose
     *
     * @param string $text
     * @return array
     */
    private function analyzeIntent(string $text): array
    {
        $prompt = <<<PROMPT
Analyze the intent and purpose of this text:

Categories:
- inform (educate, explain)
- persuade (convince, sell)
- entertain (amuse, engage)
- instruct (teach, guide)
- document (record, report)
- request (ask, demand)
- express (emotion, opinion)

Text: "{$text}"

Respond with JSON:
{
    "primary_intent": "inform",
    "secondary_intents": ["persuade"],
    "tone_goals": ["professional", "trustworthy"],
    "desired_action": "reader should understand the concept",
    "emotional_target": "neutral to positive",
    "persuasion_techniques": ["credibility", "statistics"]
}
PROMPT;
        
        $response = $this->callAI($prompt);
        return $this->parseJsonResponse($response, [
            'primary_intent' => 'inform',
            'secondary_intents' => [],
            'tone_goals' => ['neutral'],
            'desired_action' => 'none',
            'emotional_target' => 'neutral',
            'persuasion_techniques' => []
        ]);
    }
    
    /**
     * Generate context summary
     *
     * @param array $analysis
     * @return string
     */
    private function generateContextSummary(array $analysis): string
    {
        $domain = $analysis['layer_1_domain']['primary_domain'] ?? 'general';
        $formality = $analysis['layer_2_formality']['formality_level'] ?? 'neutral';
        $audience = $analysis['layer_3_audience']['primary_audience'] ?? 'general';
        $sensitivity = $analysis['layer_4_cultural_sensitivity']['sensitivity_level'] ?? 'low';
        $intent = $analysis['layer_7_intent']['primary_intent'] ?? 'inform';
        
        return "This is a {$formality} {$domain} text intended to {$intent}, targeting {$audience}. Cultural sensitivity: {$sensitivity}.";
    }
    
    /**
     * Generate translation recommendations
     *
     * @param array $analysis
     * @param string $targetLanguage
     * @return array
     */
    private function generateTranslationRecommendations(array $analysis, string $targetLanguage): array
    {
        $recommendations = [];
        
        // Domain-based recommendations
        $domain = $analysis['layer_1_domain']['primary_domain'] ?? 'general';
        if (in_array($domain, ['legal', 'medical', 'technical'])) {
            $recommendations[] = "Use specialized {$domain} terminology glossary";
            $recommendations[] = "Maintain precision - avoid interpretation";
        }
        
        // Formality recommendations
        $formality = $analysis['layer_2_formality']['formality_level'] ?? 'neutral';
        if ($formality === 'very_formal') {
            $recommendations[] = "Maintain formal register in {$targetLanguage}";
            $recommendations[] = "Use honorifics where culturally appropriate";
        }
        
        // Cultural sensitivity recommendations
        if ($analysis['layer_4_cultural_sensitivity']['adaptation_required'] ?? false) {
            $recommendations[] = "Adapt culturally sensitive content";
            $recommendations[] = "Review with native cultural expert";
        }
        
        // Idiom recommendations
        $idiomsCount = $analysis['layer_5_idioms']['total_count'] ?? 0;
        if ($idiomsCount > 0) {
            $recommendations[] = "Find cultural equivalents for idioms";
            $recommendations[] = "Avoid literal translation of expressions";
        }
        
        return array_unique($recommendations);
    }
    
    /**
     * Call AI for analysis
     *
     * @param string $prompt
     * @return string
     */
    private function callAI(string $prompt): string
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a cultural and linguistic analysis expert. Always respond with valid JSON.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 1000,
                'temperature' => 0.3
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? '';
            }
            
            return '';
            
        } catch (\Exception $e) {
            Log::error('AI call failed in context analysis', ['error' => $e->getMessage()]);
            return '';
        }
    }
    
    /**
     * Parse JSON response from AI
     *
     * @param string $response
     * @param array $default
     * @return array
     */
    private function parseJsonResponse(string $response, array $default): array
    {
        try {
            // Extract JSON from response (might be wrapped in markdown)
            if (preg_match('/```json\s*(.*?)\s*```/s', $response, $matches)) {
                $response = $matches[1];
            }
            
            $decoded = json_decode(trim($response), true);
            return is_array($decoded) ? $decoded : $default;
            
        } catch (\Exception $e) {
            return $default;
        }
    }
    
    /**
     * Get cache key for analysis
     *
     * @param string $text
     * @param string $sourceLanguage
     * @param string $targetLanguage
     * @return string
     */
    private function getCacheKey(string $text, string $sourceLanguage, string $targetLanguage): string
    {
        return 'context_analysis_' . md5($text . $sourceLanguage . $targetLanguage);
    }
    
    /**
     * Get default analysis when AI fails
     *
     * @return array
     */
    private function getDefaultAnalysis(): array
    {
        return [
            'layer_1_domain' => ['primary_domain' => 'general', 'confidence' => 0.5],
            'layer_2_formality' => ['formality_level' => 'semi_formal', 'score' => 0.5],
            'layer_3_audience' => ['primary_audience' => 'general_public'],
            'layer_4_cultural_sensitivity' => ['sensitivity_level' => 'low', 'adaptation_required' => false],
            'layer_5_idioms' => ['idioms_found' => [], 'total_count' => 0],
            'layer_6_cultural_references' => ['cultural_references' => [], 'total_count' => 0],
            'layer_7_intent' => ['primary_intent' => 'inform'],
            'summary' => 'General text with standard formality for general audience.',
            'recommendations' => ['Use standard translation approach']
        ];
    }
}
