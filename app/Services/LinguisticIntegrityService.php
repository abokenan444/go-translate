<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Linguistic Integrity Service
 * 
 * Layer 1 of 5: Ensures linguistic accuracy and consistency in translations
 * Validates grammar, syntax, terminology consistency, and linguistic quality
 */
class LinguisticIntegrityService
{
    /**
     * Analyze linguistic integrity of translation
     *
     * @param string $sourceText
     * @param string $targetText
     * @param string $sourceLang
     * @param string $targetLang
     * @return array
     */
    public function analyze(string $sourceText, string $targetText, string $sourceLang, string $targetLang): array
    {
        $issues = [];
        $score = 100;

        // 1. Length consistency check
        $lengthIssue = $this->checkLengthConsistency($sourceText, $targetText);
        if ($lengthIssue) {
            $issues[] = $lengthIssue;
            $score -= 5;
        }

        // 2. Terminology consistency
        $termIssues = $this->checkTerminologyConsistency($sourceText, $targetText, $sourceLang, $targetLang);
        if (!empty($termIssues)) {
            $issues = array_merge($issues, $termIssues);
            $score -= count($termIssues) * 3;
        }

        // 3. Number and date format consistency
        $formatIssues = $this->checkFormatConsistency($sourceText, $targetText);
        if (!empty($formatIssues)) {
            $issues = array_merge($issues, $formatIssues);
            $score -= count($formatIssues) * 2;
        }

        // 4. Punctuation and formatting
        $punctuationIssues = $this->checkPunctuation($targetText, $targetLang);
        if (!empty($punctuationIssues)) {
            $issues = array_merge($issues, $punctuationIssues);
            $score -= count($punctuationIssues) * 1;
        }

        // 5. Special characters and encoding
        $encodingIssues = $this->checkEncoding($targetText, $targetLang);
        if (!empty($encodingIssues)) {
            $issues = array_merge($issues, $encodingIssues);
            $score -= count($encodingIssues) * 2;
        }

        return [
            'layer' => 'linguistic_integrity',
            'score' => max(0, $score),
            'issues' => $issues,
            'passed' => $score >= 70,
            'analysis' => [
                'source_length' => mb_strlen($sourceText),
                'target_length' => mb_strlen($targetText),
                'length_ratio' => $this->calculateLengthRatio($sourceText, $targetText),
                'terminology_matches' => $this->countTerminologyMatches($sourceText, $targetText),
            ]
        ];
    }

    /**
     * Check length consistency between source and target
     *
     * @param string $sourceText
     * @param string $targetText
     * @return array|null
     */
    protected function checkLengthConsistency(string $sourceText, string $targetText): ?array
    {
        $ratio = $this->calculateLengthRatio($sourceText, $targetText);

        // Acceptable range: 0.5 to 2.0 (target can be 50% to 200% of source)
        if ($ratio < 0.3 || $ratio > 3.0) {
            return [
                'type' => 'length_inconsistency',
                'severity' => 'medium',
                'message' => "Translation length ratio ({$ratio}) is outside acceptable range (0.3-3.0)",
                'recommendation' => 'Review translation completeness and accuracy'
            ];
        }

        return null;
    }

    /**
     * Calculate length ratio between source and target
     *
     * @param string $sourceText
     * @param string $targetText
     * @return float
     */
    protected function calculateLengthRatio(string $sourceText, string $targetText): float
    {
        $sourceLength = mb_strlen($sourceText);
        if ($sourceLength === 0) {
            return 0;
        }

        return round(mb_strlen($targetText) / $sourceLength, 2);
    }

    /**
     * Check terminology consistency
     *
     * @param string $sourceText
     * @param string $targetText
     * @param string $sourceLang
     * @param string $targetLang
     * @return array
     */
    protected function checkTerminologyConsistency(string $sourceText, string $targetText, string $sourceLang, string $targetLang): array
    {
        $issues = [];

        // Common terminology that should be consistent
        $commonTerms = $this->getCommonTerminology($sourceLang);

        foreach ($commonTerms as $term => $expectedTranslations) {
            if (stripos($sourceText, $term) !== false) {
                $found = false;
                foreach ($expectedTranslations as $translation) {
                    if (stripos($targetText, $translation) !== false) {
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $issues[] = [
                        'type' => 'terminology_inconsistency',
                        'severity' => 'low',
                        'message' => "Term '{$term}' may not be consistently translated",
                        'recommendation' => 'Verify terminology translation'
                    ];
                }
            }
        }

        return $issues;
    }

    /**
     * Get common terminology for language
     *
     * @param string $lang
     * @return array
     */
    protected function getCommonTerminology(string $lang): array
    {
        // This would be expanded with a comprehensive terminology database
        $terminology = [
            'en' => [
                'company' => ['شركة', 'مؤسسة'],
                'contract' => ['عقد', 'اتفاقية'],
                'certificate' => ['شهادة'],
                'translation' => ['ترجمة'],
            ],
            'ar' => [
                'شركة' => ['company', 'corporation'],
                'عقد' => ['contract', 'agreement'],
                'شهادة' => ['certificate'],
                'ترجمة' => ['translation'],
            ]
        ];

        return $terminology[$lang] ?? [];
    }

    /**
     * Check format consistency (numbers, dates, etc.)
     *
     * @param string $sourceText
     * @param string $targetText
     * @return array
     */
    protected function checkFormatConsistency(string $sourceText, string $targetText): array
    {
        $issues = [];

        // Extract numbers from both texts
        preg_match_all('/\d+(?:[.,]\d+)*/', $sourceText, $sourceNumbers);
        preg_match_all('/\d+(?:[.,]\d+)*/', $targetText, $targetNumbers);

        $sourceCount = count($sourceNumbers[0]);
        $targetCount = count($targetNumbers[0]);

        if ($sourceCount !== $targetCount) {
            $issues[] = [
                'type' => 'number_count_mismatch',
                'severity' => 'high',
                'message' => "Number count mismatch: source has {$sourceCount}, target has {$targetCount}",
                'recommendation' => 'Verify all numbers are correctly translated'
            ];
        }

        return $issues;
    }

    /**
     * Check punctuation correctness
     *
     * @param string $text
     * @param string $lang
     * @return array
     */
    protected function checkPunctuation(string $text, string $lang): array
    {
        $issues = [];

        // Check for common punctuation errors
        if ($lang === 'ar') {
            // Arabic should use Arabic comma and question mark
            if (strpos($text, ',') !== false) {
                $issues[] = [
                    'type' => 'punctuation_error',
                    'severity' => 'low',
                    'message' => 'English comma (,) found in Arabic text, should use Arabic comma (،)',
                    'recommendation' => 'Replace with Arabic punctuation'
                ];
            }
        }

        // Check for unmatched brackets/parentheses
        $openBrackets = substr_count($text, '(') + substr_count($text, '[') + substr_count($text, '{');
        $closeBrackets = substr_count($text, ')') + substr_count($text, ']') + substr_count($text, '}');

        if ($openBrackets !== $closeBrackets) {
            $issues[] = [
                'type' => 'unmatched_brackets',
                'severity' => 'medium',
                'message' => 'Unmatched brackets or parentheses detected',
                'recommendation' => 'Ensure all brackets are properly closed'
            ];
        }

        return $issues;
    }

    /**
     * Check encoding and special characters
     *
     * @param string $text
     * @param string $lang
     * @return array
     */
    protected function checkEncoding(string $text, string $lang): array
    {
        $issues = [];

        // Check for encoding issues
        if (!mb_check_encoding($text, 'UTF-8')) {
            $issues[] = [
                'type' => 'encoding_error',
                'severity' => 'high',
                'message' => 'Text encoding is not valid UTF-8',
                'recommendation' => 'Convert text to UTF-8 encoding'
            ];
        }

        // Check for replacement characters (�)
        if (strpos($text, '�') !== false) {
            $issues[] = [
                'type' => 'replacement_character',
                'severity' => 'high',
                'message' => 'Replacement character (�) found, indicating encoding issues',
                'recommendation' => 'Fix character encoding problems'
            ];
        }

        return $issues;
    }

    /**
     * Count terminology matches
     *
     * @param string $sourceText
     * @param string $targetText
     * @return int
     */
    protected function countTerminologyMatches(string $sourceText, string $targetText): int
    {
        // This is a simplified implementation
        // In production, this would use a comprehensive terminology database
        return 0;
    }
}
