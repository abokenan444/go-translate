<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Audience Tolerance Model Service
 * 
 * Layer 5 of 5: Analyzes content appropriateness for target audience
 * Considers age groups, professional contexts, and cultural tolerance levels
 */
class AudienceToleranceService
{
    /**
     * Analyze audience tolerance and appropriateness
     *
     * @param string $text
     * @param string $lang
     * @param array $audienceProfile
     * @return array
     */
    public function analyze(string $text, string $lang, array $audienceProfile = []): array
    {
        $issues = [];
        $score = 100;

        $targetAudience = $audienceProfile['type'] ?? 'general';
        $ageGroup = $audienceProfile['age_group'] ?? 'adult';
        $professionalContext = $audienceProfile['professional'] ?? false;
        $targetCountry = $audienceProfile['country'] ?? null;

        // 1. Age appropriateness
        $ageIssues = $this->checkAgeAppropriateness($text, $lang, $ageGroup);
        if (!empty($ageIssues)) {
            $issues = array_merge($issues, $ageIssues);
            $score -= count($ageIssues) * 10;
        }

        // 2. Professional context appropriateness
        if ($professionalContext) {
            $professionalIssues = $this->checkProfessionalAppropriateness($text, $lang);
            if (!empty($professionalIssues)) {
                $issues = array_merge($issues, $professionalIssues);
                $score -= count($professionalIssues) * 8;
            }
        }

        // 3. Cultural tolerance level
        $toleranceIssues = $this->checkCulturalTolerance($text, $lang, $targetCountry);
        if (!empty($toleranceIssues)) {
            $issues = array_merge($issues, $toleranceIssues);
            $score -= count($toleranceIssues) * 12;
        }

        // 4. Sensitive topics
        $sensitiveIssues = $this->checkSensitiveTopics($text, $lang, $targetAudience);
        if (!empty($sensitiveIssues)) {
            $issues = array_merge($issues, $sensitiveIssues);
            $score -= count($sensitiveIssues) * 15;
        }

        // 5. Tone and register
        $toneIssues = $this->checkToneAppropriateness($text, $lang, $targetAudience, $professionalContext);
        if (!empty($toneIssues)) {
            $issues = array_merge($issues, $toneIssues);
            $score -= count($toneIssues) * 5;
        }

        return [
            'layer' => 'audience_tolerance',
            'score' => max(0, $score),
            'issues' => $issues,
            'passed' => $score >= 70,
            'audience_profile' => [
                'type' => $targetAudience,
                'age_group' => $ageGroup,
                'professional_context' => $professionalContext,
                'target_country' => $targetCountry,
            ],
            'recommendations' => $this->generateRecommendations($issues, $targetAudience),
        ];
    }

    /**
     * Check age appropriateness
     *
     * @param string $text
     * @param string $lang
     * @param string $ageGroup
     * @return array
     */
    protected function checkAgeAppropriateness(string $text, string $lang, string $ageGroup): array
    {
        $issues = [];

        $restrictedContent = $this->getAgeRestrictedContent($lang, $ageGroup);

        foreach ($restrictedContent as $content => $info) {
            if (stripos($text, $content) !== false) {
                $issues[] = [
                    'type' => 'age_inappropriate',
                    'severity' => $info['severity'],
                    'content' => $content,
                    'message' => "Content may be inappropriate for {$ageGroup} audience",
                    'recommendation' => $info['recommendation'],
                    'age_restriction' => $info['min_age']
                ];
            }
        }

        return $issues;
    }

    /**
     * Get age-restricted content
     *
     * @param string $lang
     * @param string $ageGroup
     * @return array
     */
    protected function getAgeRestrictedContent(string $lang, string $ageGroup): array
    {
        // Age groups: child (0-12), teen (13-17), adult (18+)
        
        if ($ageGroup === 'child' || $ageGroup === 'teen') {
            return [
                'violence' => [
                    'severity' => 'high',
                    'min_age' => 18,
                    'recommendation' => 'Remove or soften violent content'
                ],
                'عنف' => [
                    'severity' => 'high',
                    'min_age' => 18,
                    'recommendation' => 'Remove or soften violent content'
                ],
            ];
        }

        return [];
    }

    /**
     * Check professional appropriateness
     *
     * @param string $text
     * @param string $lang
     * @return array
     */
    protected function checkProfessionalAppropriateness(string $text, string $lang): array
    {
        $issues = [];

        // Check for informal language in professional context
        $informalMarkers = $this->getInformalMarkers($lang);

        foreach ($informalMarkers as $marker => $info) {
            if (stripos($text, $marker) !== false) {
                $issues[] = [
                    'type' => 'professional_tone_mismatch',
                    'severity' => 'medium',
                    'marker' => $marker,
                    'message' => 'Informal language detected in professional context',
                    'recommendation' => "Replace with formal alternative: {$info['formal_alternative']}"
                ];
            }
        }

        // Check for slang
        $slangTerms = $this->getSlangTerms($lang);

        foreach ($slangTerms as $slang => $info) {
            if (stripos($text, $slang) !== false) {
                $issues[] = [
                    'type' => 'slang_in_professional',
                    'severity' => 'high',
                    'slang' => $slang,
                    'message' => 'Slang term detected in professional document',
                    'recommendation' => "Use professional term: {$info['professional_term']}"
                ];
            }
        }

        return $issues;
    }

    /**
     * Get informal language markers
     *
     * @param string $lang
     * @return array
     */
    protected function getInformalMarkers(string $lang): array
    {
        $markers = [
            'ar' => [
                'يعني' => [
                    'formal_alternative' => 'أي / بمعنى آخر'
                ],
                'خلاص' => [
                    'formal_alternative' => 'حسناً / تم'
                ],
            ],
            'en' => [
                'gonna' => [
                    'formal_alternative' => 'going to'
                ],
                'wanna' => [
                    'formal_alternative' => 'want to'
                ],
                'yeah' => [
                    'formal_alternative' => 'yes'
                ],
            ]
        ];

        return $markers[$lang] ?? [];
    }

    /**
     * Get slang terms
     *
     * @param string $lang
     * @return array
     */
    protected function getSlangTerms(string $lang): array
    {
        // This would be a comprehensive database in production
        return [];
    }

    /**
     * Check cultural tolerance
     *
     * @param string $text
     * @param string $lang
     * @param string|null $country
     * @return array
     */
    protected function checkCulturalTolerance(string $text, string $lang, ?string $country): array
    {
        $issues = [];

        $toleranceLevels = $this->getCulturalToleranceLevels($country);

        // Check topics against tolerance levels
        $topics = $this->detectTopics($text, $lang);

        foreach ($topics as $topic => $confidence) {
            if (isset($toleranceLevels[$topic])) {
                $tolerance = $toleranceLevels[$topic];
                
                if ($tolerance === 'prohibited') {
                    $issues[] = [
                        'type' => 'culturally_prohibited',
                        'severity' => 'critical',
                        'topic' => $topic,
                        'message' => "Topic '{$topic}' is culturally prohibited in {$country}",
                        'recommendation' => 'Remove or significantly modify content'
                    ];
                } elseif ($tolerance === 'low') {
                    $issues[] = [
                        'type' => 'low_cultural_tolerance',
                        'severity' => 'high',
                        'topic' => $topic,
                        'message' => "Topic '{$topic}' has low tolerance in {$country}",
                        'recommendation' => 'Handle with extreme caution and cultural sensitivity'
                    ];
                }
            }
        }

        return $issues;
    }

    /**
     * Get cultural tolerance levels by country
     *
     * @param string|null $country
     * @return array
     */
    protected function getCulturalToleranceLevels(?string $country): array
    {
        $levels = [
            'SA' => [ // Saudi Arabia
                'alcohol' => 'prohibited',
                'gambling' => 'prohibited',
                'lgbtq' => 'prohibited',
                'religious_criticism' => 'prohibited',
            ],
            'AE' => [ // UAE
                'alcohol' => 'low',
                'gambling' => 'prohibited',
                'lgbtq' => 'prohibited',
            ],
            'US' => [ // United States
                'alcohol' => 'high',
                'gambling' => 'medium',
                'lgbtq' => 'high',
            ],
        ];

        return $levels[$country] ?? [];
    }

    /**
     * Detect topics in text
     *
     * @param string $text
     * @param string $lang
     * @return array
     */
    protected function detectTopics(string $text, string $lang): array
    {
        $topics = [];

        $topicKeywords = [
            'alcohol' => ['alcohol', 'wine', 'beer', 'خمر', 'نبيذ', 'كحول'],
            'gambling' => ['gambling', 'casino', 'bet', 'قمار', 'مراهنة'],
            'lgbtq' => ['lgbt', 'gay', 'lesbian', 'مثلي'],
        ];

        foreach ($topicKeywords as $topic => $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($text, $keyword) !== false) {
                    $topics[$topic] = 0.8; // Confidence score
                    break;
                }
            }
        }

        return $topics;
    }

    /**
     * Check sensitive topics
     *
     * @param string $text
     * @param string $lang
     * @param string $audience
     * @return array
     */
    protected function checkSensitiveTopics(string $text, string $lang, string $audience): array
    {
        $issues = [];

        $sensitiveTopics = $this->getSensitiveTopics($lang, $audience);

        foreach ($sensitiveTopics as $topic => $info) {
            if (stripos($text, $topic) !== false) {
                $issues[] = [
                    'type' => 'sensitive_topic',
                    'severity' => $info['severity'],
                    'topic' => $topic,
                    'message' => "Sensitive topic detected for {$audience} audience",
                    'recommendation' => $info['recommendation']
                ];
            }
        }

        return $issues;
    }

    /**
     * Get sensitive topics
     *
     * @param string $lang
     * @param string $audience
     * @return array
     */
    protected function getSensitiveTopics(string $lang, string $audience): array
    {
        // This would be comprehensive in production
        return [];
    }

    /**
     * Check tone appropriateness
     *
     * @param string $text
     * @param string $lang
     * @param string $audience
     * @param bool $professional
     * @return array
     */
    protected function checkToneAppropriateness(string $text, string $lang, string $audience, bool $professional): array
    {
        $issues = [];

        $detectedTone = $this->detectTone($text, $lang);
        $expectedTone = $professional ? 'formal' : 'neutral';

        if ($detectedTone !== $expectedTone && $professional) {
            $issues[] = [
                'type' => 'tone_mismatch',
                'severity' => 'medium',
                'detected_tone' => $detectedTone,
                'expected_tone' => $expectedTone,
                'message' => "Tone mismatch: detected '{$detectedTone}', expected '{$expectedTone}'",
                'recommendation' => 'Adjust tone to match professional context'
            ];
        }

        return $issues;
    }

    /**
     * Detect tone of text
     *
     * @param string $text
     * @param string $lang
     * @return string
     */
    protected function detectTone(string $text, string $lang): string
    {
        // Simplified detection - would use NLP in production
        return 'neutral';
    }

    /**
     * Generate recommendations based on issues
     *
     * @param array $issues
     * @param string $audience
     * @return array
     */
    protected function generateRecommendations(array $issues, string $audience): array
    {
        $recommendations = [];

        $criticalCount = count(array_filter($issues, fn($i) => $i['severity'] === 'critical'));
        $highCount = count(array_filter($issues, fn($i) => $i['severity'] === 'high'));

        if ($criticalCount > 0) {
            $recommendations[] = "CRITICAL: {$criticalCount} critical issue(s) found. Content requires major revision before use with {$audience} audience.";
        }

        if ($highCount > 0) {
            $recommendations[] = "WARNING: {$highCount} high-severity issue(s) found. Review and modify content carefully.";
        }

        if (empty($issues)) {
            $recommendations[] = "Content appears appropriate for {$audience} audience.";
        }

        return $recommendations;
    }
}
