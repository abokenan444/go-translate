<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Cultural Norms Service
 * 
 * Layer 2 of 5: Analyzes cultural appropriateness and norms compliance
 * Detects cultural sensitivities, idioms, metaphors, and context-specific meanings
 */
class CulturalNormsService
{
    /**
     * Analyze cultural norms compliance
     *
     * @param string $sourceText
     * @param string $targetText
     * @param string $sourceLang
     * @param string $targetLang
     * @param string $targetCountry
     * @return array
     */
    public function analyze(string $sourceText, string $targetText, string $sourceLang, string $targetLang, string $targetCountry = null): array
    {
        $issues = [];
        $score = 100;

        // 1. Idioms and expressions
        $idiomIssues = $this->checkIdioms($sourceText, $targetText, $sourceLang, $targetLang);
        if (!empty($idiomIssues)) {
            $issues = array_merge($issues, $idiomIssues);
            $score -= count($idiomIssues) * 5;
        }

        // 2. Cultural references
        $culturalIssues = $this->checkCulturalReferences($targetText, $targetLang, $targetCountry);
        if (!empty($culturalIssues)) {
            $issues = array_merge($issues, $culturalIssues);
            $score -= count($culturalIssues) * 8;
        }

        // 3. Honorifics and formality
        $formalityIssues = $this->checkFormality($targetText, $targetLang, $targetCountry);
        if (!empty($formalityIssues)) {
            $issues = array_merge($issues, $formalityIssues);
            $score -= count($formalityIssues) * 4;
        }

        // 4. Gender and social norms
        $genderIssues = $this->checkGenderNorms($targetText, $targetLang, $targetCountry);
        if (!empty($genderIssues)) {
            $issues = array_merge($issues, $genderIssues);
            $score -= count($genderIssues) * 6;
        }

        // 5. Taboo words and offensive content
        $tabooIssues = $this->checkTaboos($targetText, $targetLang, $targetCountry);
        if (!empty($tabooIssues)) {
            $issues = array_merge($issues, $tabooIssues);
            $score -= count($tabooIssues) * 15; // High penalty
        }

        return [
            'layer' => 'cultural_norms',
            'score' => max(0, $score),
            'issues' => $issues,
            'passed' => $score >= 70,
            'analysis' => [
                'formality_level' => $this->detectFormalityLevel($targetText, $targetLang),
                'cultural_adaptation' => $this->assessCulturalAdaptation($sourceText, $targetText),
                'target_country' => $targetCountry ?? 'unknown',
            ]
        ];
    }

    /**
     * Check idioms and expressions translation
     *
     * @param string $sourceText
     * @param string $targetText
     * @param string $sourceLang
     * @param string $targetLang
     * @return array
     */
    protected function checkIdioms(string $sourceText, string $targetText, string $sourceLang, string $targetLang): array
    {
        $issues = [];

        $idioms = $this->getCommonIdioms($sourceLang);

        foreach ($idioms as $idiom => $info) {
            if (stripos($sourceText, $idiom) !== false) {
                // Check if literally translated (which is usually wrong)
                if ($this->isLiteralTranslation($idiom, $targetText, $targetLang)) {
                    $issues[] = [
                        'type' => 'literal_idiom_translation',
                        'severity' => 'medium',
                        'message' => "Idiom '{$idiom}' appears to be literally translated",
                        'recommendation' => "Use cultural equivalent: {$info['suggestion']}",
                        'context' => $info['meaning']
                    ];
                }
            }
        }

        return $issues;
    }

    /**
     * Get common idioms for language
     *
     * @param string $lang
     * @return array
     */
    protected function getCommonIdioms(string $lang): array
    {
        $idioms = [
            'en' => [
                'break the ice' => [
                    'meaning' => 'Start a conversation in a social setting',
                    'suggestion' => 'كسر الحاجز / بدء الحديث'
                ],
                'piece of cake' => [
                    'meaning' => 'Very easy',
                    'suggestion' => 'سهل جداً / أمر بسيط'
                ],
                'cost an arm and a leg' => [
                    'meaning' => 'Very expensive',
                    'suggestion' => 'غالي جداً / باهظ الثمن'
                ],
            ],
            'ar' => [
                'على قدم وساق' => [
                    'meaning' => 'In full swing',
                    'suggestion' => 'in full swing / at full speed'
                ],
                'رمى الكرة في ملعب' => [
                    'meaning' => 'Pass responsibility to someone',
                    'suggestion' => 'pass the buck / shift responsibility'
                ],
            ]
        ];

        return $idioms[$lang] ?? [];
    }

    /**
     * Check if idiom is literally translated
     *
     * @param string $idiom
     * @param string $targetText
     * @param string $targetLang
     * @return bool
     */
    protected function isLiteralTranslation(string $idiom, string $targetText, string $targetLang): bool
    {
        // Simplified check - in production, use NLP analysis
        return false;
    }

    /**
     * Check cultural references appropriateness
     *
     * @param string $text
     * @param string $lang
     * @param string|null $country
     * @return array
     */
    protected function checkCulturalReferences(string $text, string $lang, ?string $country): array
    {
        $issues = [];

        $sensitiveConcepts = $this->getSensitiveConcepts($lang, $country);

        foreach ($sensitiveConcepts as $concept => $info) {
            if (stripos($text, $concept) !== false) {
                $issues[] = [
                    'type' => 'cultural_sensitivity',
                    'severity' => $info['severity'],
                    'message' => "Cultural reference '{$concept}' detected",
                    'recommendation' => $info['recommendation'],
                    'context' => $info['context']
                ];
            }
        }

        return $issues;
    }

    /**
     * Get sensitive cultural concepts
     *
     * @param string $lang
     * @param string|null $country
     * @return array
     */
    protected function getSensitiveConcepts(string $lang, ?string $country): array
    {
        // This would be expanded with comprehensive cultural database
        $concepts = [
            'ar' => [
                'خمر' => [
                    'severity' => 'high',
                    'recommendation' => 'Consider context and target audience',
                    'context' => 'Alcohol references may be sensitive in some Arabic-speaking countries'
                ],
                'لحم الخنزير' => [
                    'severity' => 'high',
                    'recommendation' => 'Handle with cultural sensitivity',
                    'context' => 'Pork references require careful handling in Islamic contexts'
                ],
            ]
        ];

        return $concepts[$lang] ?? [];
    }

    /**
     * Check formality and honorifics
     *
     * @param string $text
     * @param string $lang
     * @param string|null $country
     * @return array
     */
    protected function checkFormality(string $text, string $lang, ?string $country): array
    {
        $issues = [];

        if ($lang === 'ar') {
            // Check for appropriate use of formal Arabic
            $informalMarkers = ['انت', 'انتي', 'انتو']; // Informal "you"
            $formalMarkers = ['حضرتك', 'سيادتك', 'معالي'];

            $hasInformal = false;
            foreach ($informalMarkers as $marker) {
                if (stripos($text, $marker) !== false) {
                    $hasInformal = true;
                    break;
                }
            }

            // In formal/legal documents, informal language is inappropriate
            if ($hasInformal && $this->isLegalContext($text)) {
                $issues[] = [
                    'type' => 'formality_mismatch',
                    'severity' => 'medium',
                    'message' => 'Informal language detected in formal/legal context',
                    'recommendation' => 'Use formal Arabic pronouns and expressions'
                ];
            }
        }

        return $issues;
    }

    /**
     * Check gender and social norms
     *
     * @param string $text
     * @param string $lang
     * @param string|null $country
     * @return array
     */
    protected function checkGenderNorms(string $text, string $lang, ?string $country): array
    {
        $issues = [];

        // Check for gender-neutral language where appropriate
        // This is context-dependent and would require more sophisticated analysis

        return $issues;
    }

    /**
     * Check for taboo words and offensive content
     *
     * @param string $text
     * @param string $lang
     * @param string|null $country
     * @return array
     */
    protected function checkTaboos(string $text, string $lang, ?string $country): array
    {
        $issues = [];

        $tabooWords = $this->getTabooWords($lang, $country);

        foreach ($tabooWords as $word => $severity) {
            if (stripos($text, $word) !== false) {
                $issues[] = [
                    'type' => 'taboo_content',
                    'severity' => $severity,
                    'message' => 'Potentially offensive or taboo content detected',
                    'recommendation' => 'Review and replace with culturally appropriate alternative'
                ];
            }
        }

        return $issues;
    }

    /**
     * Get taboo words for language/country
     *
     * @param string $lang
     * @param string|null $country
     * @return array
     */
    protected function getTabooWords(string $lang, ?string $country): array
    {
        // This would be a comprehensive database in production
        // Returning empty array for now to avoid storing offensive content
        return [];
    }

    /**
     * Detect formality level
     *
     * @param string $text
     * @param string $lang
     * @return string
     */
    protected function detectFormalityLevel(string $text, string $lang): string
    {
        // Simplified detection - would use NLP in production
        return 'formal';
    }

    /**
     * Assess cultural adaptation quality
     *
     * @param string $sourceText
     * @param string $targetText
     * @return string
     */
    protected function assessCulturalAdaptation(string $sourceText, string $targetText): string
    {
        // Simplified assessment
        return 'adequate';
    }

    /**
     * Check if text is in legal context
     *
     * @param string $text
     * @return bool
     */
    protected function isLegalContext(string $text): bool
    {
        $legalKeywords = ['عقد', 'اتفاقية', 'شهادة', 'قانون', 'محكمة', 'contract', 'agreement', 'legal', 'court'];

        foreach ($legalKeywords as $keyword) {
            if (stripos($text, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }
}
