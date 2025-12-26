<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AdvancedCulturalTranslationService
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
    protected $maxTokens = 4000;

    public function __construct()
    {
        $this->apiKey = config('services.openai.key');
        $this->model = env('OPENAI_MODEL', 'gpt-5.2');
    }

    /**
     * Translate text with advanced cultural adaptation and multi-layer correction
     */
    public function translateWithCulturalContext(
        string $text,
        string $sourceLang,
        string $targetLang,
        array $options = []
    ): array {
        $domain = $options['domain'] ?? 'general';
        $formality = $options['formality'] ?? 'formal';
        $context = $options['context'] ?? '';
        $preserveFormatting = $options['preserve_formatting'] ?? true;

        // Get language configuration
        $sourceLangConfig = config("languages.supported.{$sourceLang}");
        $targetLangConfig = config("languages.supported.{$targetLang}");

        // Build comprehensive prompt
        $systemPrompt = $this->buildCulturalSystemPrompt($sourceLangConfig, $targetLangConfig, $domain);
        $userPrompt = $this->buildUserPrompt($text, $formality, $context, $preserveFormatting);

        // First pass: Initial translation
        $initialTranslation = $this->callOpenAI($systemPrompt, $userPrompt);

        // Apply correction layers
        $correctedTranslation = $this->applyCorrectionLayers(
            $initialTranslation,
            $targetLang,
            $targetLangConfig,
            $domain,
            $formality
        );

        // Cultural adaptation layer
        $culturallyAdapted = $this->applyCulturalAdaptation(
            $correctedTranslation,
            $sourceLang,
            $targetLang,
            $domain
        );

        return [
            'translated_text' => $culturallyAdapted,
            'source_language' => $sourceLang,
            'target_language' => $targetLang,
            'domain' => $domain,
            'formality' => $formality,
            'status' => 'draft', // Layer 2: EXPLICIT DRAFT STATUS - AI NEVER CERTIFIES
            'quality_score' => $this->calculateQualityScore($culturallyAdapted, $text),
            'metadata' => [
                'rtl' => $targetLangConfig['rtl'] ?? false,
                'formality_level' => $formality,
                'cultural_adaptation_applied' => true,
                'correction_layers_applied' => 5,
                'ai_generated' => true,
                'requires_human_certification' => true,
                'certification_status' => 'pending_partner_review',
            ]
        ];
    }

    /**
     * Build comprehensive system prompt with cultural context
     */
    protected function buildCulturalSystemPrompt($sourceLangConfig, $targetLangConfig, $domain): string
    {
        $domainPrompts = $this->getDomainSpecificPrompts($domain);
        
        return <<<PROMPT
You are an expert cultural translator specializing in {$sourceLangConfig['name']} to {$targetLangConfig['native']} translation.

CORE PRINCIPLES:
1. **Cultural Sensitivity**: Adapt idioms, metaphors, and cultural references appropriately
2. **Context Preservation**: Maintain the original meaning and intent
3. **Natural Flow**: Ensure the translation sounds native, not mechanical
4. **Formality Matching**: Match the formality level of the source text
5. **Technical Accuracy**: Preserve technical terms and specialized vocabulary

DOMAIN EXPERTISE: {$domainPrompts['expertise']}

CULTURAL CONSIDERATIONS:
- Source Culture ({$sourceLangConfig['name']}): {$this->getCulturalNotes($sourceLangConfig)}
- Target Culture ({$targetLangConfig['native']}): {$this->getCulturalNotes($targetLangConfig)}

RTL HANDLING: {$this->getRTLInstructions($targetLangConfig)}

QUALITY STANDARDS:
✓ Grammar accuracy: 100%
✓ Spelling accuracy: 100%
✓ Context preservation: High priority
✓ Cultural appropriateness: Essential
✓ Natural readability: Native-level
✓ Technical term accuracy: Verified

TRANSLATION APPROACH:
{$domainPrompts['approach']}

IMPORTANT: Do NOT translate:
- Brand names (unless culturally adapted versions exist)
- Product codes or SKUs
- URLs or email addresses
- Proper nouns (unless translation is conventional)
- Technical specifications numbers

Return ONLY the translated text without explanations or notes.
PROMPT;
    }

    /**
     * Build user prompt with context
     */
    protected function buildUserPrompt(string $text, string $formality, string $context, bool $preserveFormatting): string
    {
        $formalityInstruction = $this->getFormalityInstruction($formality);
        $formattingInstruction = $preserveFormatting ? 
            "Preserve all formatting including line breaks, bullet points, and structure." : 
            "Focus on content, formatting can be adapted.";

        $prompt = "FORMALITY LEVEL: {$formalityInstruction}\n\n";
        
        if (!empty($context)) {
            $prompt .= "CONTEXT: {$context}\n\n";
        }
        
        $prompt .= "FORMATTING: {$formattingInstruction}\n\n";
        $prompt .= "TEXT TO TRANSLATE:\n{$text}";

        return $prompt;
    }

    /**
     * Apply multi-layer correction system
     */
    protected function applyCorrectionLayers(
        string $translation,
        string $targetLang,
        array $langConfig,
        string $domain,
        string $formality
    ): string {
        // Layer 1: Grammar and spelling check
        $translation = $this->grammarCheck($translation, $targetLang);

        // Layer 2: Formality consistency check
        $translation = $this->formalityCheck($translation, $targetLang, $formality);

        // Layer 3: Technical term verification
        $translation = $this->technicalTermCheck($translation, $domain, $targetLang);

        // Layer 4: Idiom localization
        $translation = $this->idiomLocalization($translation, $targetLang);

        // Layer 5: Flow and readability enhancement
        $translation = $this->readabilityEnhancement($translation, $targetLang);

        return $translation;
    }

    /**
     * Apply cultural adaptation
     */
    protected function applyCulturalAdaptation(
        string $translation,
        string $sourceLang,
        string $targetLang,
        string $domain
    ): string {
        $cacheKey = "cultural_adaptation_{$sourceLang}_{$targetLang}_{$domain}_" . md5($translation);
        
        return Cache::remember($cacheKey, 3600, function () use ($translation, $sourceLang, $targetLang, $domain) {
            $prompt = <<<PROMPT
Review this translation for cultural appropriateness and make necessary adaptations:

Source Language: {$sourceLang}
Target Language: {$targetLang}
Domain: {$domain}

Translation: {$translation}

Apply cultural adaptations for:
1. Local customs and traditions
2. Religious sensitivities
3. Social norms and taboos
4. Business etiquette
5. Color symbolism
6. Number preferences/superstitions
7. Time and date formats
8. Currency and measurement units

Return ONLY the culturally adapted translation.
PROMPT;

            try {
                return $this->callOpenAI(
                    "You are a cultural adaptation specialist.",
                    $prompt
                );
            } catch (\Exception $e) {
                Log::warning('Cultural adaptation failed', ['error' => $e->getMessage()]);
                return $translation;
            }
        });
    }

    /**
     * Grammar and spelling check - LAYER 1
     */
    protected function grammarCheck(string $text, string $lang): string
    {
        $cacheKey = "grammar_check_{$lang}_" . md5($text);
        
        return Cache::remember($cacheKey, 1800, function () use ($text, $lang) {
            $prompt = <<<PROMPT
Review the following text in {$lang} for grammar and spelling errors.
Correct any mistakes while preserving the original meaning and style.

IMPORTANT:
- Fix grammar errors
- Fix spelling mistakes
- Preserve original meaning
- Maintain the same tone and style
- Return ONLY the corrected text without explanations

Text:
{$text}
PROMPT;

            try {
                return $this->callOpenAI(
                    "You are an expert grammar and spelling checker with native-level proficiency.",
                    $prompt
                );
            } catch (\Exception $e) {
                Log::warning('Grammar check failed', ['error' => $e->getMessage()]);
                return $text;
            }
        });
    }

    /**
     * Formality consistency check - LAYER 2
     */
    protected function formalityCheck(string $text, string $lang, string $formality): string
    {
        $cacheKey = "formality_check_{$lang}_{$formality}_" . md5($text);
        
        return Cache::remember($cacheKey, 1800, function () use ($text, $lang, $formality) {
            $formalityInstruction = $this->getFormalityInstruction($formality);
            
            $prompt = <<<PROMPT
Review the following text in {$lang} for formality consistency.
Ensure the text matches the required formality level: {$formality}

FORMALITY REQUIREMENT: {$formalityInstruction}

IMPORTANT:
- Adjust pronouns to match formality level
- Adjust vocabulary to match formality level
- Preserve original meaning
- Return ONLY the adjusted text without explanations

Text:
{$text}
PROMPT;

            try {
                return $this->callOpenAI(
                    "You are an expert in linguistic formality and register.",
                    $prompt
                );
            } catch (\Exception $e) {
                Log::warning('Formality check failed', ['error' => $e->getMessage()]);
                return $text;
            }
        });
    }

    /**
     * Technical term verification - LAYER 3
     */
    protected function technicalTermCheck(string $text, string $domain, string $lang): string
    {
        $cacheKey = "technical_check_{$domain}_{$lang}_" . md5($text);
        
        return Cache::remember($cacheKey, 1800, function () use ($text, $domain, $lang) {
            $domainPrompts = $this->getDomainSpecificPrompts($domain);
            
            $prompt = <<<PROMPT
Review the following text in {$lang} for technical term accuracy in the {$domain} domain.

DOMAIN EXPERTISE: {$domainPrompts['expertise']}
DOMAIN APPROACH: {$domainPrompts['approach']}

IMPORTANT:
- Verify technical terminology is correct and standard
- Ensure domain-specific terms are accurate
- Preserve technical precision
- Return ONLY the verified text without explanations

Text:
{$text}
PROMPT;

            try {
                return $this->callOpenAI(
                    "You are an expert in {$domain} terminology and technical translation.",
                    $prompt
                );
            } catch (\Exception $e) {
                Log::warning('Technical term check failed', ['error' => $e->getMessage()]);
                return $text;
            }
        });
    }

    /**
     * Idiom localization - LAYER 4
     */
    protected function idiomLocalization(string $text, string $lang): string
    {
        $cacheKey = "idiom_localization_{$lang}_" . md5($text);
        
        return Cache::remember($cacheKey, 1800, function () use ($text, $lang) {
            $prompt = <<<PROMPT
Review the following text in {$lang} for idioms and expressions.
Ensure all idioms are natural and culturally appropriate for native speakers.

IMPORTANT:
- Replace foreign idioms with natural local equivalents
- Ensure expressions sound native, not translated
- Preserve the intended meaning and impact
- Return ONLY the localized text without explanations

Text:
{$text}
PROMPT;

            try {
                return $this->callOpenAI(
                    "You are an expert in idiomatic expressions and cultural localization.",
                    $prompt
                );
            } catch (\Exception $e) {
                Log::warning('Idiom localization failed', ['error' => $e->getMessage()]);
                return $text;
            }
        });
    }

    /**
     * Readability enhancement - LAYER 5
     */
    protected function readabilityEnhancement(string $text, string $lang): string
    {
        $cacheKey = "readability_enhancement_{$lang}_" . md5($text);
        
        return Cache::remember($cacheKey, 1800, function () use ($text, $lang) {
            $prompt = <<<PROMPT
Review the following text in {$lang} for flow and readability.
Enhance the text to sound natural and fluent for native speakers.

IMPORTANT:
- Improve sentence flow and transitions
- Ensure natural word order
- Enhance readability without changing meaning
- Make it sound like it was originally written in {$lang}
- Return ONLY the enhanced text without explanations

Text:
{$text}
PROMPT;

            try {
                return $this->callOpenAI(
                    "You are an expert in linguistic flow and native-level writing.",
                    $prompt
                );
            } catch (\Exception $e) {
                Log::warning('Readability enhancement failed', ['error' => $e->getMessage()]);
                return $text;
            }
        });
    }

    /**
     * Get domain-specific prompts
     */
    protected function getDomainSpecificPrompts(string $domain): array
    {
        $prompts = [
            'legal' => [
                'expertise' => 'Legal documents, contracts, terms of service, privacy policies',
                'approach' => 'Maintain precise legal terminology. Ensure exact meaning preservation. Use standard legal phrases in target language. Be extremely careful with obligations, rights, and conditions.',
            ],
            'medical' => [
                'expertise' => 'Medical records, prescriptions, health information, clinical notes',
                'approach' => 'Use standard medical terminology. Preserve all diagnoses and medication names. Be precise with dosages and instructions. Maintain patient safety through accurate translation.',
            ],
            'technical' => [
                'expertise' => 'Technical manuals, specifications, engineering documents, IT documentation',
                'approach' => 'Preserve technical terms. Maintain specifications accuracy. Use industry-standard terminology. Keep technical precision while ensuring clarity.',
            ],
            'business' => [
                'expertise' => 'Business correspondence, reports, presentations, marketing materials',
                'approach' => 'Maintain professional tone. Adapt business etiquette to target culture. Preserve brand voice. Balance formality with accessibility.',
            ],
            'academic' => [
                'expertise' => 'Research papers, academic articles, educational content, theses',
                'approach' => 'Maintain academic rigor. Preserve citations and references. Use scholarly terminology. Ensure intellectual precision.',
            ],
            'marketing' => [
                'expertise' => 'Advertisements, product descriptions, promotional content, social media',
                'approach' => 'Adapt slogans and catchphrases culturally. Maintain persuasive tone. Localize humor and wordplay. Preserve brand personality.',
            ],
            'literary' => [
                'expertise' => 'Literature, creative writing, poetry, narratives',
                'approach' => 'Preserve author\'s voice and style. Adapt cultural references. Maintain emotional impact. Balance literal and literary translation.',
            ],
            'government' => [
                'expertise' => 'Official documents, certificates, government forms, regulations',
                'approach' => 'Use official terminology. Maintain bureaucratic tone. Ensure legal validity. Follow standard government language conventions.',
            ],
            'financial' => [
                'expertise' => 'Financial reports, banking documents, investment materials, accounting',
                'approach' => 'Preserve financial terminology. Maintain numerical accuracy. Use standard financial language. Ensure regulatory compliance.',
            ],
            'general' => [
                'expertise' => 'General content, everyday communication, various topics',
                'approach' => 'Maintain natural flow. Adapt idioms appropriately. Balance accuracy with readability. Ensure clear communication.',
            ],
        ];

        return $prompts[$domain] ?? $prompts['general'];
    }

    /**
     * Get cultural notes for language
     */
    protected function getCulturalNotes(array $langConfig): string
    {
        $notes = "Language: {$langConfig['native']}.";
        
        if (isset($langConfig['formality'])) {
            $notes .= " Formality levels: " . implode(', ', $langConfig['formality']) . ".";
        }
        
        if (isset($langConfig['rtl']) && $langConfig['rtl']) {
            $notes .= " Right-to-left script.";
        }
        
        if (isset($langConfig['regions'])) {
            $notes .= " Regional variants: " . implode(', ', $langConfig['regions']) . ".";
        }
        
        return $notes;
    }

    /**
     * Get RTL instructions
     */
    protected function getRTLInstructions(array $langConfig): string
    {
        if ($langConfig['rtl'] ?? false) {
            return "This is a RIGHT-TO-LEFT language. Ensure proper RTL text flow and punctuation handling.";
        }
        
        return "This is a LEFT-TO-RIGHT language. Use standard LTR punctuation and formatting.";
    }

    /**
     * Get formality instruction
     */
    protected function getFormalityInstruction(string $formality): string
    {
        $instructions = [
            'formal' => 'Very formal, professional, respectful tone. Use formal pronouns and expressions.',
            'informal' => 'Casual, friendly, conversational tone. Use informal pronouns and colloquial expressions.',
            'neutral' => 'Standard, balanced tone. Neither too formal nor too casual.',
            'polite' => 'Polite and courteous tone. Show respect while maintaining accessibility.',
            'honorific' => 'Highly respectful tone with honorific forms. Maximum respect and deference.',
        ];

        return $instructions[$formality] ?? $instructions['formal'];
    }

    /**
     * Calculate quality score
     */
    protected function calculateQualityScore(string $translation, string $original): float
    {
        // Basic quality metrics
        $lengthRatio = strlen($translation) / strlen($original);
        $score = 0.8; // Base score

        // Adjust based on length ratio (expect similar lengths for good translations)
        if ($lengthRatio >= 0.7 && $lengthRatio <= 1.5) {
            $score += 0.1;
        }

        // Check for completeness (no placeholders or errors)
        if (!str_contains($translation, '[') && !str_contains($translation, 'ERROR')) {
            $score += 0.1;
        }

        return min($score, 1.0);
    }

    /**
     * Call OpenAI API
     */
    protected function callOpenAI(string $systemPrompt, string $userPrompt): string
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post($this->endpoint, [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'max_tokens' => $this->maxTokens,
                'top_p' => 0.9,
            ]);

            if ($response->successful()) {
                $payload = $response->json() ?? [];
                // Standard Chat Completions API response format
                if (!empty($payload['choices'][0]['message']['content'])) {
                    return trim($payload['choices'][0]['message']['content']);
                }
                return '';
            }

            Log::error('OpenAI API Error', ['status' => $response->status(), 'response' => $response->body()]);
            throw new \Exception('Translation API error');

        } catch (\Exception $e) {
            Log::error('Translation Service Error', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Batch translate multiple texts
     */
    public function batchTranslate(array $texts, string $sourceLang, string $targetLang, array $options = []): array
    {
        $results = [];
        
        foreach ($texts as $key => $text) {
            try {
                $results[$key] = $this->translateWithCulturalContext($text, $sourceLang, $targetLang, $options);
                // Layer 2: Ensure batch results also have DRAFT status
                $results[$key]['status'] = 'draft';
                $results[$key]['batch_item'] = true;
            } catch (\Exception $e) {
                $results[$key] = [
                    'error' => $e->getMessage(),
                    'original_text' => $text,
                    'status' => 'error',
                    'batch_item' => true,
                ];
            }
        }
        
        return [
            'status' => 'draft', // Overall batch status is also DRAFT
            'total_items' => count($texts),
            'results' => $results,
        ];
    }
}
