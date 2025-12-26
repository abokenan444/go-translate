<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Legal Phrase Detection Service
 * 
 * Layer 4 of 5: Detects and validates legal terminology and phrases
 * Critical for certified legal translations and government documents
 */
class LegalPhraseDetectionService
{
    /**
     * Analyze legal phrases and terminology
     *
     * @param string $sourceText
     * @param string $targetText
     * @param string $sourceLang
     * @param string $targetLang
     * @param string|null $documentType
     * @return array
     */
    public function analyze(string $sourceText, string $targetText, string $sourceLang, string $targetLang, ?string $documentType = null): array
    {
        $issues = [];
        $score = 100;

        // 1. Legal terminology accuracy
        $termIssues = $this->checkLegalTerminology($sourceText, $targetText, $sourceLang, $targetLang);
        if (!empty($termIssues)) {
            $issues = array_merge($issues, $termIssues);
            $score -= count($termIssues) * 10;
        }

        // 2. Legal phrase structure
        $structureIssues = $this->checkLegalPhraseStructure($targetText, $targetLang);
        if (!empty($structureIssues)) {
            $issues = array_merge($issues, $structureIssues);
            $score -= count($structureIssues) * 8;
        }

        // 3. Binding language detection
        $bindingIssues = $this->checkBindingLanguage($targetText, $targetLang);
        if (!empty($bindingIssues)) {
            $issues = array_merge($issues, $bindingIssues);
            $score -= count($bindingIssues) * 12;
        }

        // 4. Date and time format (legal standard)
        $dateIssues = $this->checkLegalDateFormat($targetText, $targetLang);
        if (!empty($dateIssues)) {
            $issues = array_merge($issues, $dateIssues);
            $score -= count($dateIssues) * 5;
        }

        // 5. Signature and authentication phrases
        $authIssues = $this->checkAuthenticationPhrases($targetText, $targetLang);
        if (!empty($authIssues)) {
            $issues = array_merge($issues, $authIssues);
            $score -= count($authIssues) * 7;
        }

        return [
            'layer' => 'legal_phrase_detection',
            'score' => max(0, $score),
            'issues' => $issues,
            'passed' => $score >= 80, // High threshold for legal content
            'is_legal_document' => $this->isLegalDocument($sourceText, $targetText),
            'analysis' => [
                'legal_terms_count' => $this->countLegalTerms($targetText, $targetLang),
                'binding_phrases_count' => $this->countBindingPhrases($targetText, $targetLang),
                'document_type' => $documentType ?? $this->detectDocumentType($sourceText),
                'requires_notarization' => $this->requiresNotarization($sourceText, $targetText),
            ]
        ];
    }

    /**
     * Check legal terminology accuracy
     *
     * @param string $sourceText
     * @param string $targetText
     * @param string $sourceLang
     * @param string $targetLang
     * @return array
     */
    protected function checkLegalTerminology(string $sourceText, string $targetText, string $sourceLang, string $targetLang): array
    {
        $issues = [];

        $legalTerms = $this->getLegalTerminology($sourceLang);

        foreach ($legalTerms as $sourceTerm => $info) {
            if (stripos($sourceText, $sourceTerm) !== false) {
                $found = false;
                foreach ($info['correct_translations'] as $correctTranslation) {
                    if (stripos($targetText, $correctTranslation) !== false) {
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $issues[] = [
                        'type' => 'legal_terminology_error',
                        'severity' => 'high',
                        'source_term' => $sourceTerm,
                        'message' => "Legal term '{$sourceTerm}' may not be correctly translated",
                        'recommendation' => "Use standard legal translation: " . implode(' or ', $info['correct_translations']),
                        'context' => $info['context']
                    ];
                }
            }
        }

        return $issues;
    }

    /**
     * Get legal terminology database
     *
     * @param string $lang
     * @return array
     */
    protected function getLegalTerminology(string $lang): array
    {
        $terminology = [
            'en' => [
                'hereby' => [
                    'correct_translations' => ['بموجب هذا', 'بموجبه'],
                    'context' => 'Legal binding phrase'
                ],
                'whereas' => [
                    'correct_translations' => ['حيث أن', 'بما أن'],
                    'context' => 'Legal preamble phrase'
                ],
                'pursuant to' => [
                    'correct_translations' => ['عملاً بـ', 'استناداً إلى', 'بموجب'],
                    'context' => 'Legal reference phrase'
                ],
                'notwithstanding' => [
                    'correct_translations' => ['على الرغم من', 'بصرف النظر عن'],
                    'context' => 'Legal exception clause'
                ],
                'force majeure' => [
                    'correct_translations' => ['القوة القاهرة', 'الظروف القاهرة'],
                    'context' => 'Legal exemption clause'
                ],
                'indemnify' => [
                    'correct_translations' => ['يعوض', 'يتعهد بتعويض'],
                    'context' => 'Legal liability term'
                ],
                'covenant' => [
                    'correct_translations' => ['تعهد', 'التزام'],
                    'context' => 'Legal agreement term'
                ],
                'jurisdiction' => [
                    'correct_translations' => ['الاختصاص القضائي', 'الولاية القضائية'],
                    'context' => 'Legal authority term'
                ],
            ],
            'ar' => [
                'بموجب هذا' => [
                    'correct_translations' => ['hereby'],
                    'context' => 'Legal binding phrase'
                ],
                'حيث أن' => [
                    'correct_translations' => ['whereas'],
                    'context' => 'Legal preamble'
                ],
                'القوة القاهرة' => [
                    'correct_translations' => ['force majeure'],
                    'context' => 'Legal exemption'
                ],
                'الاختصاص القضائي' => [
                    'correct_translations' => ['jurisdiction'],
                    'context' => 'Legal authority'
                ],
            ]
        ];

        return $terminology[$lang] ?? [];
    }

    /**
     * Check legal phrase structure
     *
     * @param string $text
     * @param string $lang
     * @return array
     */
    protected function checkLegalPhraseStructure(string $text, string $lang): array
    {
        $issues = [];

        // Check for proper legal structure markers
        $structureMarkers = $this->getLegalStructureMarkers($lang);

        // Detect if document has legal structure but missing key elements
        if ($this->hasLegalStructure($text)) {
            foreach ($structureMarkers as $marker => $info) {
                if ($info['required'] && stripos($text, $marker) === false) {
                    $issues[] = [
                        'type' => 'missing_legal_structure',
                        'severity' => 'medium',
                        'message' => "Legal document may be missing required element: {$marker}",
                        'recommendation' => $info['recommendation']
                    ];
                }
            }
        }

        return $issues;
    }

    /**
     * Get legal structure markers
     *
     * @param string $lang
     * @return array
     */
    protected function getLegalStructureMarkers(string $lang): array
    {
        $markers = [
            'ar' => [
                'الطرف الأول' => [
                    'required' => true,
                    'recommendation' => 'Legal contracts should identify parties'
                ],
                'الطرف الثاني' => [
                    'required' => true,
                    'recommendation' => 'Legal contracts should identify all parties'
                ],
            ],
            'en' => [
                'Party A' => [
                    'required' => false,
                    'recommendation' => 'Consider identifying parties clearly'
                ],
                'Party B' => [
                    'required' => false,
                    'recommendation' => 'Consider identifying parties clearly'
                ],
            ]
        ];

        return $markers[$lang] ?? [];
    }

    /**
     * Check if text has legal structure
     *
     * @param string $text
     * @return bool
     */
    protected function hasLegalStructure(string $text): bool
    {
        $legalIndicators = ['contract', 'agreement', 'عقد', 'اتفاقية', 'whereas', 'حيث أن'];

        foreach ($legalIndicators as $indicator) {
            if (stripos($text, $indicator) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check binding language
     *
     * @param string $text
     * @param string $lang
     * @return array
     */
    protected function checkBindingLanguage(string $text, string $lang): array
    {
        $issues = [];

        $bindingPhrases = $this->getBindingPhrases($lang);

        // Check if binding phrases are used correctly
        foreach ($bindingPhrases as $phrase => $info) {
            if (stripos($text, $phrase) !== false) {
                // Verify proper usage context
                if (!$this->isProperBindingContext($text, $phrase)) {
                    $issues[] = [
                        'type' => 'binding_language_misuse',
                        'severity' => 'high',
                        'phrase' => $phrase,
                        'message' => "Binding phrase '{$phrase}' may be used incorrectly",
                        'recommendation' => $info['proper_usage']
                    ];
                }
            }
        }

        return $issues;
    }

    /**
     * Get binding phrases
     *
     * @param string $lang
     * @return array
     */
    protected function getBindingPhrases(string $lang): array
    {
        $phrases = [
            'ar' => [
                'يلتزم' => [
                    'proper_usage' => 'Should be followed by party identification and specific obligation'
                ],
                'يتعهد' => [
                    'proper_usage' => 'Should specify clear commitment'
                ],
                'ملزم قانوناً' => [
                    'proper_usage' => 'Should be used in context of legal obligation'
                ],
            ],
            'en' => [
                'shall' => [
                    'proper_usage' => 'Indicates mandatory obligation in legal context'
                ],
                'must' => [
                    'proper_usage' => 'Indicates requirement'
                ],
                'legally binding' => [
                    'proper_usage' => 'Should be used to establish enforceability'
                ],
            ]
        ];

        return $phrases[$lang] ?? [];
    }

    /**
     * Check if binding phrase is in proper context
     *
     * @param string $text
     * @param string $phrase
     * @return bool
     */
    protected function isProperBindingContext(string $text, string $phrase): bool
    {
        // Simplified check - in production, use NLP analysis
        return true;
    }

    /**
     * Check legal date format
     *
     * @param string $text
     * @param string $lang
     * @return array
     */
    protected function checkLegalDateFormat(string $text, string $lang): array
    {
        $issues = [];

        // Legal documents should use full date format
        // e.g., "December 14, 2025" not "12/14/25"

        preg_match_all('/\d{1,2}\/\d{1,2}\/\d{2,4}/', $text, $shortDates);

        if (!empty($shortDates[0])) {
            $issues[] = [
                'type' => 'informal_date_format',
                'severity' => 'low',
                'message' => 'Short date format found in legal document',
                'recommendation' => 'Use full date format for legal documents (e.g., "14 December 2025")'
            ];
        }

        return $issues;
    }

    /**
     * Check authentication phrases
     *
     * @param string $text
     * @param string $lang
     * @return array
     */
    protected function checkAuthenticationPhrases(string $text, string $lang): array
    {
        $issues = [];

        $authPhrases = $this->getAuthenticationPhrases($lang);

        // Check for presence of authentication elements
        $hasAuth = false;
        foreach ($authPhrases as $phrase => $info) {
            if (stripos($text, $phrase) !== false) {
                $hasAuth = true;
                break;
            }
        }

        if ($this->isLegalDocument($text, $text) && !$hasAuth) {
            $issues[] = [
                'type' => 'missing_authentication',
                'severity' => 'medium',
                'message' => 'Legal document may be missing authentication phrases',
                'recommendation' => 'Add signature/witness/notary sections as appropriate'
            ];
        }

        return $issues;
    }

    /**
     * Get authentication phrases
     *
     * @param string $lang
     * @return array
     */
    protected function getAuthenticationPhrases(string $lang): array
    {
        $phrases = [
            'ar' => [
                'التوقيع' => ['context' => 'Signature'],
                'الشاهد' => ['context' => 'Witness'],
                'موثق' => ['context' => 'Notarized'],
                'مصدق' => ['context' => 'Certified'],
            ],
            'en' => [
                'signature' => ['context' => 'Signature'],
                'witness' => ['context' => 'Witness'],
                'notary' => ['context' => 'Notary'],
                'certified' => ['context' => 'Certified'],
            ]
        ];

        return $phrases[$lang] ?? [];
    }

    /**
     * Check if document is legal
     *
     * @param string $sourceText
     * @param string $targetText
     * @return bool
     */
    protected function isLegalDocument(string $sourceText, string $targetText): bool
    {
        $legalKeywords = [
            'contract', 'agreement', 'عقد', 'اتفاقية',
            'hereby', 'بموجب هذا',
            'whereas', 'حيث أن',
            'jurisdiction', 'الاختصاص القضائي'
        ];

        $count = 0;
        foreach ($legalKeywords as $keyword) {
            if (stripos($sourceText, $keyword) !== false || stripos($targetText, $keyword) !== false) {
                $count++;
            }
        }

        return $count >= 2;
    }

    /**
     * Count legal terms
     *
     * @param string $text
     * @param string $lang
     * @return int
     */
    protected function countLegalTerms(string $text, string $lang): int
    {
        $terms = $this->getLegalTerminology($lang);
        $count = 0;

        foreach ($terms as $term => $info) {
            if (stripos($text, $term) !== false) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Count binding phrases
     *
     * @param string $text
     * @param string $lang
     * @return int
     */
    protected function countBindingPhrases(string $text, string $lang): int
    {
        $phrases = $this->getBindingPhrases($lang);
        $count = 0;

        foreach ($phrases as $phrase => $info) {
            $count += substr_count(strtolower($text), strtolower($phrase));
        }

        return $count;
    }

    /**
     * Detect document type
     *
     * @param string $text
     * @return string
     */
    protected function detectDocumentType(string $text): string
    {
        $types = [
            'contract' => ['contract', 'عقد', 'agreement', 'اتفاقية'],
            'certificate' => ['certificate', 'شهادة'],
            'power_of_attorney' => ['power of attorney', 'توكيل'],
            'affidavit' => ['affidavit', 'إقرار'],
        ];

        foreach ($types as $type => $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($text, $keyword) !== false) {
                    return $type;
                }
            }
        }

        return 'general';
    }

    /**
     * Check if document requires notarization
     *
     * @param string $sourceText
     * @param string $targetText
     * @return bool
     */
    protected function requiresNotarization(string $sourceText, string $targetText): bool
    {
        $notarizationKeywords = [
            'power of attorney', 'توكيل',
            'affidavit', 'إقرار',
            'deed', 'صك',
            'will', 'وصية'
        ];

        foreach ($notarizationKeywords as $keyword) {
            if (stripos($sourceText, $keyword) !== false || stripos($targetText, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }
}
