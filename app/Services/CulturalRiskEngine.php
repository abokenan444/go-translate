<?php

namespace App\Services;

use App\Models\CulturalRiskRule;
use App\Models\CountryCulturalProfile;
use App\Models\CtsStandard;
use App\Models\RiskAssessment;

class CulturalRiskEngine
{
    /**
     * Analyze text for cultural risks
     */
    public function analyze(array $payload): array
    {
        $text = $payload['text'] ?? '';
        $sourceLanguage = $payload['source_language'] ?? 'en';
        $targetLanguage = $payload['target_language'] ?? 'ar';
        $targetCountry = $payload['target_country'] ?? null;
        $useCase = $payload['use_case'] ?? 'general';
        $domain = $payload['domain'] ?? 'general';

        // Get applicable cultural risk rules
        $rules = CulturalRiskRule::getActiveRulesFor($targetLanguage, $targetCountry);

        // Get country cultural profile
        $countryProfile = $targetCountry 
            ? CountryCulturalProfile::getByCountryCode($targetCountry)
            : null;

        // Detect risks
        $riskFlags = [];
        $severityScores = [];

        foreach ($rules as $rule) {
            if ($rule->matchesText($text)) {
                $riskFlags[] = [
                    'rule_code' => $rule->rule_code,
                    'category' => $rule->category,
                    'risk_level' => $rule->risk_level,
                    'description' => $rule->description,
                    'recommendation' => $rule->recommendation,
                    'severity_score' => $rule->severity_score,
                ];
                $severityScores[] = $rule->severity_score;
            }
        }

        // Check country-specific taboos
        if ($countryProfile) {
            foreach ($countryProfile->taboo_topics ?? [] as $taboo) {
                if (stripos($text, $taboo) !== false) {
                    $riskFlags[] = [
                        'rule_code' => 'COUNTRY-TABOO',
                        'category' => 'cultural',
                        'risk_level' => 'high',
                        'description' => "Contains taboo topic for {$countryProfile->country_name}: {$taboo}",
                        'recommendation' => 'Remove or rephrase this content',
                        'severity_score' => 80,
                    ];
                    $severityScores[] = 80;
                }
            }
        }

        // Calculate Cultural Impact Score
        $impactScore = $this->calculateImpactScore($riskFlags, $severityScores, $countryProfile);

        // Determine CTS Level
        $ctsLevel = $this->determineCtsLevel($impactScore, $riskFlags);

        // Generate recommendation
        $recommendation = $this->generateRecommendation($ctsLevel, $riskFlags, $impactScore);

        // Determine if human review is required
        $requiresHumanReview = $this->requiresHumanReview($ctsLevel, $riskFlags, $useCase);

        return [
            'cts_level' => $ctsLevel,
            'cultural_impact_score' => $impactScore,
            'risk_flags' => $riskFlags,
            'recommendation' => $recommendation,
            'requires_human_review' => $requiresHumanReview,
            'country_profile' => $countryProfile ? [
                'country_name' => $countryProfile->country_name,
                'tolerance_level' => $countryProfile->tolerance_level,
                'cultural_tolerance_score' => $countryProfile->cultural_tolerance_score,
            ] : null,
        ];
    }

    /**
     * Calculate Cultural Impact Score (0-100)
     * Higher score = safer/better
     */
    protected function calculateImpactScore(array $riskFlags, array $severityScores, ?CountryCulturalProfile $profile): int
    {
        // Start with perfect score
        $score = 100;

        // Deduct based on severity scores
        if (!empty($severityScores)) {
            $avgSeverity = array_sum($severityScores) / count($severityScores);
            $score -= $avgSeverity;
        }

        // Adjust based on country tolerance
        if ($profile) {
            $toleranceBonus = ($profile->cultural_tolerance_score - 50) / 10;
            $score += $toleranceBonus;
        }

        // Ensure score is within bounds
        return max(0, min(100, (int) $score));
    }

    /**
     * Determine CTS Level based on score and risks
     */
    protected function determineCtsLevel(int $impactScore, array $riskFlags): string
    {
        // Check for critical risks
        foreach ($riskFlags as $flag) {
            if ($flag['risk_level'] === 'critical') {
                return 'CTS-R';
            }
        }

        // Determine based on impact score
        if ($impactScore >= 85) {
            return 'CTS-A'; // Government-safe
        } elseif ($impactScore >= 65) {
            return 'CTS-B'; // Commercial-safe
        } elseif ($impactScore >= 40) {
            return 'CTS-C'; // Requires review
        } else {
            return 'CTS-R'; // High risk
        }
    }

    /**
     * Generate recommendation based on analysis
     */
    protected function generateRecommendation(string $ctsLevel, array $riskFlags, int $impactScore): string
    {
        if ($ctsLevel === 'CTS-A') {
            return 'Content is culturally safe and suitable for government and institutional use.';
        }

        if ($ctsLevel === 'CTS-B') {
            return 'Content is suitable for commercial publication with minor considerations.';
        }

        if ($ctsLevel === 'CTS-C') {
            return 'Human review required before publication. ' . count($riskFlags) . ' potential cultural risks detected.';
        }

        return 'High risk content detected. Significant revision required before publication.';
    }

    /**
     * Determine if human review is required
     */
    protected function requiresHumanReview(string $ctsLevel, array $riskFlags, string $useCase): bool
    {
        // Always require review for government use case
        if ($useCase === 'government' || $useCase === 'legal') {
            return true;
        }

        // Require review for CTS-C and CTS-R
        if (in_array($ctsLevel, ['CTS-C', 'CTS-R'])) {
            return true;
        }

        // Require review if any high or critical risks detected
        foreach ($riskFlags as $flag) {
            if (in_array($flag['risk_level'], ['high', 'critical'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Create and save risk assessment
     */
    public function createAssessment(array $payload): RiskAssessment
    {
        $analysis = $this->analyze($payload);

        return RiskAssessment::create([
            'user_id' => $payload['user_id'] ?? null,
            'project_id' => $payload['project_id'] ?? null,
            'assessment_type' => $payload['assessment_type'] ?? 'translation',
            'source_language' => $payload['source_language'] ?? 'en',
            'target_language' => $payload['target_language'] ?? 'ar',
            'target_country' => $payload['target_country'] ?? null,
            'use_case' => $payload['use_case'] ?? 'general',
            'domain' => $payload['domain'] ?? 'general',
            'source_text' => $payload['source_text'] ?? null,
            'translated_text' => $payload['text'] ?? null,
            'cts_level' => $analysis['cts_level'],
            'risk_flags' => $analysis['risk_flags'],
            'cultural_impact_score' => $analysis['cultural_impact_score'],
            'recommendation' => $analysis['recommendation'],
            'requires_human_review' => $analysis['requires_human_review'],
            'assessed_at' => now(),
        ]);
    }
}
