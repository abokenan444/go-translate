<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Religious & Political Sensitivity Service
 * 
 * Layer 3 of 5: Detects and analyzes religious and political sensitivities
 * Critical for cross-border communication and government certifications
 */
class ReligiousPoliticalSensitivityService
{
    /**
     * Analyze religious and political sensitivities
     *
     * @param string $text
     * @param string $lang
     * @param string|null $targetCountry
     * @param string|null $sourceCountry
     * @return array
     */
    public function analyze(string $text, string $lang, ?string $targetCountry = null, ?string $sourceCountry = null): array
    {
        $issues = [];
        $score = 100;

        // 1. Religious content analysis
        $religiousIssues = $this->analyzeReligiousContent($text, $lang, $targetCountry);
        if (!empty($religiousIssues)) {
            $issues = array_merge($issues, $religiousIssues);
            $score -= $this->calculateReligiousPenalty($religiousIssues);
        }

        // 2. Political content analysis
        $politicalIssues = $this->analyzePoliticalContent($text, $lang, $targetCountry, $sourceCountry);
        if (!empty($politicalIssues)) {
            $issues = array_merge($issues, $politicalIssues);
            $score -= $this->calculatePoliticalPenalty($politicalIssues);
        }

        // 3. Territorial disputes
        $territorialIssues = $this->analyzeTerritorialReferences($text, $targetCountry);
        if (!empty($territorialIssues)) {
            $issues = array_merge($issues, $territorialIssues);
            $score -= count($territorialIssues) * 20; // High penalty
        }

        // 4. Historical sensitivities
        $historicalIssues = $this->analyzeHistoricalReferences($text, $lang, $targetCountry);
        if (!empty($historicalIssues)) {
            $issues = array_merge($issues, $historicalIssues);
            $score -= count($historicalIssues) * 10;
        }

        return [
            'layer' => 'religious_political_sensitivity',
            'score' => max(0, $score),
            'issues' => $issues,
            'passed' => $score >= 60, // Lower threshold due to sensitivity
            'risk_level' => $this->calculateRiskLevel($score, $issues),
            'analysis' => [
                'religious_references' => $this->countReligiousReferences($text, $lang),
                'political_references' => $this->countPoliticalReferences($text, $lang),
                'target_country' => $targetCountry ?? 'unknown',
                'requires_review' => $score < 80,
            ]
        ];
    }

    /**
     * Analyze religious content
     *
     * @param string $text
     * @param string $lang
     * @param string|null $targetCountry
     * @return array
     */
    protected function analyzeReligiousContent(string $text, string $lang, ?string $targetCountry): array
    {
        $issues = [];

        $religiousTerms = $this->getReligiousTerms($lang);

        foreach ($religiousTerms as $term => $info) {
            if (stripos($text, $term) !== false) {
                // Check if term is appropriate for target country
                if ($this->isReligiouslySensitive($term, $targetCountry)) {
                    $issues[] = [
                        'type' => 'religious_sensitivity',
                        'severity' => $info['severity'],
                        'term' => $term,
                        'message' => "Religious term '{$term}' may be sensitive in {$targetCountry}",
                        'recommendation' => $info['recommendation'],
                        'context' => $info['context']
                    ];
                }
            }
        }

        return $issues;
    }

    /**
     * Get religious terms database
     *
     * @param string $lang
     * @return array
     */
    protected function getReligiousTerms(string $lang): array
    {
        $terms = [
            'ar' => [
                'الله' => [
                    'severity' => 'critical',
                    'recommendation' => 'Ensure respectful usage and proper context',
                    'context' => 'Divine name requiring highest respect'
                ],
                'النبي' => [
                    'severity' => 'critical',
                    'recommendation' => 'Use with appropriate honorifics',
                    'context' => 'Prophet reference requiring respect'
                ],
                'القرآن' => [
                    'severity' => 'high',
                    'recommendation' => 'Ensure accurate and respectful reference',
                    'context' => 'Holy book reference'
                ],
                'الإنجيل' => [
                    'severity' => 'medium',
                    'recommendation' => 'Use respectfully',
                    'context' => 'Bible reference'
                ],
                'التوراة' => [
                    'severity' => 'medium',
                    'recommendation' => 'Use respectfully',
                    'context' => 'Torah reference'
                ],
            ],
            'en' => [
                'God' => [
                    'severity' => 'high',
                    'recommendation' => 'Ensure respectful usage',
                    'context' => 'Divine reference'
                ],
                'Prophet' => [
                    'severity' => 'high',
                    'recommendation' => 'Use with respect',
                    'context' => 'Religious figure reference'
                ],
                'Quran' => [
                    'severity' => 'high',
                    'recommendation' => 'Ensure accurate reference',
                    'context' => 'Holy book'
                ],
            ]
        ];

        return $terms[$lang] ?? [];
    }

    /**
     * Check if term is religiously sensitive for target country
     *
     * @param string $term
     * @param string|null $country
     * @return bool
     */
    protected function isReligiouslySensitive(string $term, ?string $country): bool
    {
        // All religious terms are sensitive, but some countries have stricter requirements
        $strictCountries = ['SA', 'IR', 'AE', 'QA', 'KW', 'BH', 'OM'];

        return in_array($country, $strictCountries);
    }

    /**
     * Analyze political content
     *
     * @param string $text
     * @param string $lang
     * @param string|null $targetCountry
     * @param string|null $sourceCountry
     * @return array
     */
    protected function analyzePoliticalContent(string $text, string $lang, ?string $targetCountry, ?string $sourceCountry): array
    {
        $issues = [];

        $politicalTerms = $this->getPoliticalTerms($lang);

        foreach ($politicalTerms as $term => $info) {
            if (stripos($text, $term) !== false) {
                // Check if politically sensitive for target country
                if ($this->isPoliticallySensitive($term, $targetCountry, $sourceCountry)) {
                    $issues[] = [
                        'type' => 'political_sensitivity',
                        'severity' => $info['severity'],
                        'term' => $term,
                        'message' => "Political term '{$term}' may be sensitive",
                        'recommendation' => $info['recommendation'],
                        'affected_countries' => $info['affected_countries'] ?? []
                    ];
                }
            }
        }

        return $issues;
    }

    /**
     * Get political terms database
     *
     * @param string $lang
     * @return array
     */
    protected function getPoliticalTerms(string $lang): array
    {
        $terms = [
            'ar' => [
                'إسرائيل' => [
                    'severity' => 'critical',
                    'recommendation' => 'Use neutral terminology or avoid if possible',
                    'affected_countries' => ['SA', 'IR', 'SY', 'LB']
                ],
                'فلسطين' => [
                    'severity' => 'high',
                    'recommendation' => 'Use carefully based on context',
                    'affected_countries' => ['IL', 'PS']
                ],
                'الثورة' => [
                    'severity' => 'medium',
                    'recommendation' => 'Context-dependent, review carefully',
                    'affected_countries' => []
                ],
            ],
            'en' => [
                'Israel' => [
                    'severity' => 'critical',
                    'recommendation' => 'Use neutral terminology',
                    'affected_countries' => ['SA', 'IR', 'SY', 'LB']
                ],
                'Palestine' => [
                    'severity' => 'high',
                    'recommendation' => 'Use carefully based on context',
                    'affected_countries' => ['IL', 'PS']
                ],
                'revolution' => [
                    'severity' => 'medium',
                    'recommendation' => 'Context-dependent',
                    'affected_countries' => []
                ],
            ]
        ];

        return $terms[$lang] ?? [];
    }

    /**
     * Check if term is politically sensitive
     *
     * @param string $term
     * @param string|null $targetCountry
     * @param string|null $sourceCountry
     * @return bool
     */
    protected function isPoliticallySensitive(string $term, ?string $targetCountry, ?string $sourceCountry): bool
    {
        // Simplified check - in production, use comprehensive political sensitivity database
        return true;
    }

    /**
     * Analyze territorial references
     *
     * @param string $text
     * @param string|null $targetCountry
     * @return array
     */
    protected function analyzeTerritorialReferences(string $text, ?string $targetCountry): array
    {
        $issues = [];

        $disputedTerritories = $this->getDisputedTerritories();

        foreach ($disputedTerritories as $territory => $info) {
            if (stripos($text, $territory) !== false) {
                $issues[] = [
                    'type' => 'territorial_dispute',
                    'severity' => 'critical',
                    'territory' => $territory,
                    'message' => "Reference to disputed territory '{$territory}'",
                    'recommendation' => 'Use neutral terminology or consult legal team',
                    'disputed_by' => $info['disputed_by']
                ];
            }
        }

        return $issues;
    }

    /**
     * Get disputed territories database
     *
     * @return array
     */
    protected function getDisputedTerritories(): array
    {
        return [
            'Kashmir' => [
                'disputed_by' => ['IN', 'PK', 'CN']
            ],
            'كشمير' => [
                'disputed_by' => ['IN', 'PK', 'CN']
            ],
            'Taiwan' => [
                'disputed_by' => ['CN', 'TW']
            ],
            'تايوان' => [
                'disputed_by' => ['CN', 'TW']
            ],
        ];
    }

    /**
     * Analyze historical references
     *
     * @param string $text
     * @param string $lang
     * @param string|null $targetCountry
     * @return array
     */
    protected function analyzeHistoricalReferences(string $text, string $lang, ?string $targetCountry): array
    {
        $issues = [];

        // Check for sensitive historical events
        $sensitiveEvents = $this->getSensitiveHistoricalEvents($lang);

        foreach ($sensitiveEvents as $event => $info) {
            if (stripos($text, $event) !== false) {
                $issues[] = [
                    'type' => 'historical_sensitivity',
                    'severity' => $info['severity'],
                    'event' => $event,
                    'message' => "Reference to sensitive historical event",
                    'recommendation' => $info['recommendation']
                ];
            }
        }

        return $issues;
    }

    /**
     * Get sensitive historical events
     *
     * @param string $lang
     * @return array
     */
    protected function getSensitiveHistoricalEvents(string $lang): array
    {
        // This would be a comprehensive database in production
        return [];
    }

    /**
     * Calculate religious penalty
     *
     * @param array $issues
     * @return int
     */
    protected function calculateReligiousPenalty(array $issues): int
    {
        $penalty = 0;

        foreach ($issues as $issue) {
            switch ($issue['severity']) {
                case 'critical':
                    $penalty += 25;
                    break;
                case 'high':
                    $penalty += 15;
                    break;
                case 'medium':
                    $penalty += 8;
                    break;
                default:
                    $penalty += 3;
            }
        }

        return $penalty;
    }

    /**
     * Calculate political penalty
     *
     * @param array $issues
     * @return int
     */
    protected function calculatePoliticalPenalty(array $issues): int
    {
        return $this->calculateReligiousPenalty($issues); // Same calculation
    }

    /**
     * Calculate overall risk level
     *
     * @param int $score
     * @param array $issues
     * @return string
     */
    protected function calculateRiskLevel(int $score, array $issues): string
    {
        $criticalCount = count(array_filter($issues, fn($i) => $i['severity'] === 'critical'));

        if ($criticalCount > 0 || $score < 50) {
            return 'critical';
        } elseif ($score < 70) {
            return 'high';
        } elseif ($score < 85) {
            return 'medium';
        } else {
            return 'low';
        }
    }

    /**
     * Count religious references
     *
     * @param string $text
     * @param string $lang
     * @return int
     */
    protected function countReligiousReferences(string $text, string $lang): int
    {
        $terms = $this->getReligiousTerms($lang);
        $count = 0;

        foreach ($terms as $term => $info) {
            if (stripos($text, $term) !== false) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Count political references
     *
     * @param string $text
     * @param string $lang
     * @return int
     */
    protected function countPoliticalReferences(string $text, string $lang): int
    {
        $terms = $this->getPoliticalTerms($lang);
        $count = 0;

        foreach ($terms as $term => $info) {
            if (stripos($text, $term) !== false) {
                $count++;
            }
        }

        return $count;
    }
}
