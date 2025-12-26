<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * Brand Voice Application Engine
 * Automatically applies brand voice and glossary to translations
 */
class BrandVoiceEngine
{
    protected ContextAnalysisEngine $contextEngine;
    
    public function __construct(ContextAnalysisEngine $contextEngine)
    {
        $this->contextEngine = $contextEngine;
    }
    
    /**
     * Apply brand voice to translation
     *
     * @param string $translation
     * @param int $brandVoiceId
     * @param string $targetLanguage
     * @return array
     */
    public function applyBrandVoice(string $translation, int $brandVoiceId, string $targetLanguage): array
    {
        try {
            // Load brand voice
            $brandVoice = $this->loadBrandVoice($brandVoiceId);
            
            if (!$brandVoice) {
                return [
                    'success' => false,
                    'error' => 'Brand voice not found',
                    'original_translation' => $translation
                ];
            }
            
            // Load glossary terms
            $glossaryTerms = $this->loadGlossaryTerms($brandVoiceId, $targetLanguage);
            
            // Step 1: Apply glossary terms
            $step1 = $this->applyGlossaryTerms($translation, $glossaryTerms);
            
            // Step 2: Enforce vocabulary rules
            $step2 = $this->enforceVocabularyRules($step1['text'], $brandVoice);
            
            // Step 3: Apply tone and style
            $step3 = $this->applyToneAndStyle($step2['text'], $brandVoice, $targetLanguage);
            
            // Step 4: Consistency check
            $step4 = $this->checkConsistency($step3['text'], $brandVoice);
            
            return [
                'success' => true,
                'original_translation' => $translation,
                'brand_voice_applied' => $step4['text'],
                'changes_made' => array_merge(
                    $step1['changes'],
                    $step2['changes'],
                    $step3['changes'],
                    $step4['changes']
                ),
                'glossary_terms_applied' => count($step1['changes']),
                'vocabulary_corrections' => count($step2['changes']),
                'style_adjustments' => count($step3['changes']),
                'consistency_fixes' => count($step4['changes']),
                'brand_voice_info' => [
                    'name' => $brandVoice->name,
                    'tone' => $brandVoice->tone,
                    'formality' => $brandVoice->formality
                ]
            ];
            
        } catch (\Exception $e) {
            Log::error('Brand voice application failed', [
                'brand_voice_id' => $brandVoiceId,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'original_translation' => $translation
            ];
        }
    }
    
    /**
     * Load brand voice
     *
     * @param int $brandVoiceId
     * @return object|null
     */
    private function loadBrandVoice(int $brandVoiceId): ?object
    {
        return Cache::remember("brand_voice_{$brandVoiceId}", 3600, function () use ($brandVoiceId) {
            return DB::table('brand_voices')->where('id', $brandVoiceId)->first();
        });
    }
    
    /**
     * Load glossary terms
     *
     * @param int $brandVoiceId
     * @param string $targetLanguage
     * @return array
     */
    private function loadGlossaryTerms(int $brandVoiceId, string $targetLanguage): array
    {
        $cacheKey = "glossary_terms_{$brandVoiceId}_{$targetLanguage}";
        
        return Cache::remember($cacheKey, 3600, function () use ($brandVoiceId, $targetLanguage) {
            $brandVoice = DB::table('brand_voices')->where('id', $brandVoiceId)->first();
            
            if (!$brandVoice || !$brandVoice->user_id) {
                return [];
            }
            
            return DB::table('glossary_terms')
                ->where('user_id', $brandVoice->user_id)
                ->where('language', $targetLanguage)
                ->get()
                ->map(function ($term) {
                    return [
                        'term' => $term->term,
                        'preferred' => $term->preferred,
                        'context' => $term->context ?? null,
                        'case_sensitive' => $term->case_sensitive ?? false
                    ];
                })
                ->toArray();
        });
    }
    
    /**
     * Apply glossary terms to translation
     *
     * @param string $text
     * @param array $glossaryTerms
     * @return array
     */
    private function applyGlossaryTerms(string $text, array $glossaryTerms): array
    {
        $changes = [];
        $modifiedText = $text;
        
        foreach ($glossaryTerms as $glossaryTerm) {
            $term = $glossaryTerm['term'];
            $preferred = $glossaryTerm['preferred'];
            $caseSensitive = $glossaryTerm['case_sensitive'];
            
            // Build pattern
            $pattern = $caseSensitive 
                ? '/\b' . preg_quote($term, '/') . '\b/'
                : '/\b' . preg_quote($term, '/') . '\b/i';
            
            // Count occurrences
            $count = preg_match_all($pattern, $modifiedText);
            
            if ($count > 0) {
                // Replace term
                $modifiedText = preg_replace($pattern, $preferred, $modifiedText);
                
                $changes[] = [
                    'type' => 'glossary',
                    'from' => $term,
                    'to' => $preferred,
                    'occurrences' => $count,
                    'context' => $glossaryTerm['context']
                ];
            }
        }
        
        return [
            'text' => $modifiedText,
            'changes' => $changes
        ];
    }
    
    /**
     * Enforce vocabulary rules (use/avoid)
     *
     * @param string $text
     * @param object $brandVoice
     * @return array
     */
    private function enforceVocabularyRules(string $text, object $brandVoice): array
    {
        $changes = [];
        $modifiedText = $text;
        
        // Get vocabulary rules
        $vocabularyAvoid = json_decode($brandVoice->vocabulary_avoid ?? '[]', true);
        $vocabularyUse = json_decode($brandVoice->vocabulary_use ?? '[]', true);
        
        // Check for forbidden words
        foreach ($vocabularyAvoid as $avoidWord) {
            if (is_array($avoidWord)) {
                $forbidden = $avoidWord['word'] ?? $avoidWord;
                $replacement = $avoidWord['replacement'] ?? null;
            } else {
                $forbidden = $avoidWord;
                $replacement = null;
            }
            
            $pattern = '/\b' . preg_quote($forbidden, '/') . '\b/i';
            
            if (preg_match($pattern, $modifiedText)) {
                if ($replacement) {
                    $modifiedText = preg_replace($pattern, $replacement, $modifiedText);
                    
                    $changes[] = [
                        'type' => 'vocabulary_avoid',
                        'from' => $forbidden,
                        'to' => $replacement,
                        'reason' => 'Brand guideline: avoid this term'
                    ];
                } else {
                    $changes[] = [
                        'type' => 'vocabulary_warning',
                        'word' => $forbidden,
                        'reason' => 'This word should be avoided per brand guidelines'
                    ];
                }
            }
        }
        
        return [
            'text' => $modifiedText,
            'changes' => $changes
        ];
    }
    
    /**
     * Apply tone and style adjustments
     *
     * @param string $text
     * @param object $brandVoice
     * @param string $targetLanguage
     * @return array
     */
    private function applyToneAndStyle(string $text, object $brandVoice, string $targetLanguage): array
    {
        // This would use AI to adjust tone
        // For now, returning basic structure
        
        $changes = [];
        $modifiedText = $text;
        
        $tone = $brandVoice->tone ?? 'professional';
        $formality = $brandVoice->formality ?? 'medium';
        
        // Check if current text matches desired tone
        // This is simplified - would use AI in production
        $toneKeywords = $this->getToneKeywords($tone);
        
        foreach ($toneKeywords['should_contain'] as $keyword) {
            if (stripos($modifiedText, $keyword) === false) {
                $changes[] = [
                    'type' => 'tone_suggestion',
                    'suggestion' => "Consider adding '{$keyword}' to match {$tone} tone"
                ];
            }
        }
        
        return [
            'text' => $modifiedText,
            'changes' => $changes
        ];
    }
    
    /**
     * Check consistency with brand voice
     *
     * @param string $text
     * @param object $brandVoice
     * @return array
     */
    private function checkConsistency(string $text, object $brandVoice): array
    {
        $changes = [];
        
        // Check formality consistency
        $formality = $brandVoice->formality ?? 'medium';
        
        // Check for contractions in formal voice
        if ($formality === 'high' || $formality === 'very_formal') {
            $contractions = ["don't", "won't", "can't", "shouldn't", "wouldn't", "isn't", "aren't"];
            
            foreach ($contractions as $contraction) {
                if (stripos($text, $contraction) !== false) {
                    $changes[] = [
                        'type' => 'consistency_warning',
                        'issue' => "Contraction '{$contraction}' found",
                        'recommendation' => "Avoid contractions in formal brand voice"
                    ];
                }
            }
        }
        
        // Check tone consistency
        $tone = $brandVoice->tone ?? 'professional';
        
        if ($tone === 'friendly' || $tone === 'casual') {
            // Check for overly formal language
            $formalWords = ['utilize', 'commence', 'terminate', 'endeavor'];
            
            foreach ($formalWords as $formalWord) {
                if (stripos($text, $formalWord) !== false) {
                    $changes[] = [
                        'type' => 'consistency_suggestion',
                        'issue' => "Overly formal word '{$formalWord}' found",
                        'recommendation' => "Use simpler alternatives for {$tone} tone"
                    ];
                }
            }
        }
        
        return [
            'text' => $text,
            'changes' => $changes
        ];
    }
    
    /**
     * Get tone keywords
     *
     * @param string $tone
     * @return array
     */
    private function getToneKeywords(string $tone): array
    {
        $keywords = [
            'professional' => [
                'should_contain' => [],
                'should_avoid' => ['awesome', 'cool', 'dude']
            ],
            'friendly' => [
                'should_contain' => [],
                'should_avoid' => ['utilize', 'commence']
            ],
            'formal' => [
                'should_contain' => [],
                'should_avoid' => ['yeah', 'gonna', 'wanna']
            ],
            'casual' => [
                'should_contain' => [],
                'should_avoid' => ['pursuant', 'herewith']
            ]
        ];
        
        return $keywords[$tone] ?? $keywords['professional'];
    }
    
    /**
     * Get brand voice recommendations for text
     *
     * @param string $text
     * @param int $brandVoiceId
     * @return array
     */
    public function getBrandVoiceRecommendations(string $text, int $brandVoiceId): array
    {
        $brandVoice = $this->loadBrandVoice($brandVoiceId);
        
        if (!$brandVoice) {
            return [];
        }
        
        $recommendations = [];
        
        // Analyze current text
        $tone = $brandVoice->tone ?? 'professional';
        $formality = $brandVoice->formality ?? 'medium';
        
        $recommendations[] = [
            'category' => 'tone',
            'current' => 'detected from text',
            'target' => $tone,
            'suggestion' => "Ensure text maintains {$tone} tone throughout"
        ];
        
        $recommendations[] = [
            'category' => 'formality',
            'current' => 'detected from text',
            'target' => $formality,
            'suggestion' => "Adjust formality level to {$formality}"
        ];
        
        return $recommendations;
    }
}
