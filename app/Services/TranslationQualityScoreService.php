<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationQualityScoreService
{
    const EXCELLENT = 90;
    const GOOD = 75;
    const FAIR = 60;
    const POOR = 40;

    /**
     * Calculate comprehensive quality score for translation
     */
    public function calculateScore(string $original, string $translated, string $targetLang, array $context = []): array
    {
        $scores = [
            'accuracy' => $this->scoreAccuracy($original, $translated, $targetLang),
            'fluency' => $this->scoreFluency($translated, $targetLang),
            'cultural_appropriateness' => $this->scoreCulturalAppropriate($translated, $targetLang, $context),
            'tone_consistency' => $this->scoreToneConsistency($original, $translated, $context['tone'] ?? 'formal'),
            'terminology' => $this->scoreTerminology($original, $translated, $context['glossary'] ?? []),
        ];

        $overall = $this->calculateOverallScore($scores);

        return [
            'overall_score' => $overall,
            'rating' => $this->getRating($overall),
            'scores' => $scores,
            'suggestions' => $this->generateSuggestions($scores),
            'metrics' => [
                'character_count' => strlen($translated),
                'word_count' => str_word_count($translated),
                'sentence_count' => $this->countSentences($translated),
                'complexity_score' => $this->calculateComplexity($translated),
            ],
        ];
    }

    /**
     * Score translation accuracy
     */
    private function scoreAccuracy(string $original, string $translated, string $targetLang): float
    {
        $score = 100;

        // Check for missing content
        $originalWords = str_word_count($original);
        $translatedWords = str_word_count($translated);
        
        $lengthRatio = $translatedWords / max($originalWords, 1);
        
        // Acceptable range: 0.7 to 1.5 (some languages need more/less words)
        if ($lengthRatio < 0.5 || $lengthRatio > 2.0) {
            $score -= 20; // Significant length mismatch
        } elseif ($lengthRatio < 0.7 || $lengthRatio > 1.5) {
            $score -= 10; // Moderate length mismatch
        }

        // Check for untranslated content (same as original)
        $similarity = similar_text(strtolower($original), strtolower($translated));
        $maxLength = max(strlen($original), strlen($translated));
        $similarityPercent = ($similarity / $maxLength) * 100;
        
        if ($similarityPercent > 80) {
            $score -= 30; // Likely untranslated
        }

        // Check for broken HTML/markdown
        if ($this->hasBrokenMarkup($translated)) {
            $score -= 15;
        }

        return max(0, min(100, $score));
    }

    /**
     * Score translation fluency
     */
    private function scoreFluency(string $translated, string $targetLang): float
    {
        $score = 100;

        // Check for repeated words (sign of poor fluency)
        $words = preg_split('/\s+/', strtolower($translated));
        $wordCounts = array_count_values($words);
        $maxRepetition = max($wordCounts);
        
        if ($maxRepetition > 5) {
            $score -= 20; // Excessive repetition
        } elseif ($maxRepetition > 3) {
            $score -= 10;
        }

        // Check sentence structure
        $avgSentenceLength = $this->getAverageSentenceLength($translated);
        
        // Very short or very long sentences indicate poor fluency
        if ($avgSentenceLength < 5 || $avgSentenceLength > 40) {
            $score -= 15;
        }

        // Check for proper punctuation
        if (!$this->hasProperPunctuation($translated)) {
            $score -= 10;
        }

        // Language-specific fluency checks
        $score -= $this->checkLanguageSpecificFluency($translated, $targetLang);

        return max(0, min(100, $score));
    }

    /**
     * Score cultural appropriateness
     */
    private function scoreCulturalAppropriate(string $translated, string $targetLang, array $context): float
    {
        $score = 100;

        // Check for culturally sensitive terms
        $sensitivePhrases = $this->getCulturalSensitivePhrases($targetLang);
        
        foreach ($sensitivePhrases as $phrase) {
            if (stripos($translated, $phrase) !== false) {
                $score -= 10;
            }
        }

        // Check formality level matches target culture
        if (isset($context['target_audience'])) {
            $expectedFormality = $this->getExpectedFormality($targetLang, $context['target_audience']);
            $actualFormality = $this->detectFormality($translated, $targetLang);
            
            if (abs($expectedFormality - $actualFormality) > 20) {
                $score -= 15;
            }
        }

        // Check for proper honorifics (for languages like Japanese, Korean)
        if (in_array($targetLang, ['ja', 'ko']) && !$this->hasProperHonorifics($translated, $targetLang)) {
            $score -= 10;
        }

        return max(0, min(100, $score));
    }

    /**
     * Score tone consistency
     */
    private function scoreToneConsistency(string $original, string $translated, string $expectedTone): float
    {
        $score = 100;

        $detectedTone = $this->detectTone($translated);
        
        $toneMatch = [
            'formal' => ['formal', 'professional'],
            'casual' => ['casual', 'conversational'],
            'technical' => ['technical', 'formal'],
            'marketing' => ['marketing', 'persuasive', 'casual'],
        ];

        $expectedTones = $toneMatch[$expectedTone] ?? [$expectedTone];
        
        if (!in_array($detectedTone, $expectedTones)) {
            $score -= 25; // Tone mismatch
        }

        // Check tone consistency throughout the text
        $sentences = $this->splitIntoSentences($translated);
        $tones = array_map(fn($s) => $this->detectTone($s), $sentences);
        $uniqueTones = array_unique($tones);
        
        if (count($uniqueTones) > 2) {
            $score -= 15; // Inconsistent tone
        }

        return max(0, min(100, $score));
    }

    /**
     * Score terminology accuracy
     */
    private function scoreTerminology(string $original, string $translated, array $glossary): float
    {
        if (empty($glossary)) {
            return 100; // No glossary to check against
        }

        $score = 100;
        $violations = 0;

        foreach ($glossary as $term => $expectedTranslation) {
            if (stripos($original, $term) !== false) {
                // Term found in original, check if correctly translated
                if (stripos($translated, $expectedTranslation) === false) {
                    $violations++;
                    $score -= 10;
                }
            }
        }

        return max(0, min(100, $score));
    }

    /**
     * Calculate overall weighted score
     */
    private function calculateOverallScore(array $scores): float
    {
        $weights = [
            'accuracy' => 0.30,
            'fluency' => 0.25,
            'cultural_appropriateness' => 0.20,
            'tone_consistency' => 0.15,
            'terminology' => 0.10,
        ];

        $overall = 0;
        foreach ($scores as $metric => $score) {
            $overall += $score * ($weights[$metric] ?? 0);
        }

        return round($overall, 2);
    }

    /**
     * Get rating label
     */
    private function getRating(float $score): string
    {
        if ($score >= self::EXCELLENT) return 'excellent';
        if ($score >= self::GOOD) return 'good';
        if ($score >= self::FAIR) return 'fair';
        if ($score >= self::POOR) return 'poor';
        return 'needs_improvement';
    }

    /**
     * Generate improvement suggestions
     */
    private function generateSuggestions(array $scores): array
    {
        $suggestions = [];

        if ($scores['accuracy'] < 70) {
            $suggestions[] = 'Review translation for missing or added content';
        }

        if ($scores['fluency'] < 70) {
            $suggestions[] = 'Improve sentence structure and readability';
        }

        if ($scores['cultural_appropriateness'] < 70) {
            $suggestions[] = 'Adjust content for target culture and audience';
        }

        if ($scores['tone_consistency'] < 70) {
            $suggestions[] = 'Ensure consistent tone throughout the translation';
        }

        if ($scores['terminology'] < 70) {
            $suggestions[] = 'Use approved glossary terms consistently';
        }

        return $suggestions;
    }

    // Helper methods
    
    private function countSentences(string $text): int
    {
        return preg_match_all('/[.!?]+/', $text);
    }

    private function calculateComplexity(string $text): float
    {
        $words = str_word_count($text);
        $sentences = max($this->countSentences($text), 1);
        
        // Flesch reading ease approximation
        $avgWordsPerSentence = $words / $sentences;
        $avgSyllablesPerWord = 1.5; // Approximation
        
        $score = 206.835 - 1.015 * $avgWordsPerSentence - 84.6 * $avgSyllablesPerWord;
        
        return round(max(0, min(100, $score)), 2);
    }

    private function hasBrokenMarkup(string $text): bool
    {
        // Check for unclosed HTML tags
        $openTags = preg_match_all('/<([a-z]+)[^>]*>/i', $text, $opens);
        $closeTags = preg_match_all('/<\/([a-z]+)>/i', $text, $closes);
        
        return abs($openTags - $closeTags) > 2;
    }

    private function getAverageSentenceLength(string $text): float
    {
        $sentences = $this->splitIntoSentences($text);
        if (empty($sentences)) return 0;
        
        $totalWords = array_sum(array_map('str_word_count', $sentences));
        return $totalWords / count($sentences);
    }

    private function hasProperPunctuation(string $text): bool
    {
        // Check if sentences end with proper punctuation
        return preg_match('/[.!?]$/', trim($text)) === 1;
    }

    private function checkLanguageSpecificFluency(string $text, string $lang): int
    {
        $penalty = 0;

        // Arabic: Check for proper diacritics usage
        if ($lang === 'ar' && !preg_match('/[\x{064B}-\x{065F}]/u', $text)) {
            // Missing diacritics is acceptable in modern Arabic
            $penalty += 0;
        }

        // Chinese: Check for proper character usage
        if ($lang === 'zh' && preg_match('/[a-zA-Z]{3,}/', $text)) {
            $penalty += 10; // Too much English in Chinese text
        }

        return $penalty;
    }

    private function getCulturalSensitivePhrases(string $lang): array
    {
        // Placeholder - would be expanded with actual culturally sensitive terms
        return [];
    }

    private function getExpectedFormality(string $lang, string $audience): float
    {
        $formalityMap = [
            'business' => 90,
            'academic' => 95,
            'casual' => 30,
            'marketing' => 50,
        ];

        return $formalityMap[$audience] ?? 70;
    }

    private function detectFormality(string $text, string $lang): float
    {
        // Simplified formality detection
        $formalIndicators = ['therefore', 'furthermore', 'moreover', 'consequently'];
        $casualIndicators = ['yeah', 'gonna', 'wanna', 'cool', 'awesome'];
        
        $formalCount = 0;
        $casualCount = 0;
        
        foreach ($formalIndicators as $indicator) {
            if (stripos($text, $indicator) !== false) $formalCount++;
        }
        
        foreach ($casualIndicators as $indicator) {
            if (stripos($text, $indicator) !== false) $casualCount++;
        }
        
        if ($formalCount > $casualCount) return 80;
        if ($casualCount > $formalCount) return 30;
        return 50;
    }

    private function hasProperHonorifics(string $text, string $lang): bool
    {
        // Simplified check - would need actual language-specific logic
        return true;
    }

    private function detectTone(string $text): string
    {
        $text = strtolower($text);
        
        if (preg_match('/\b(buy|purchase|limited|offer|discount)\b/', $text)) {
            return 'marketing';
        }
        
        if (preg_match('/\b(algorithm|API|database|function|variable)\b/', $text)) {
            return 'technical';
        }
        
        if (preg_match('/\b(hey|awesome|cool|yeah)\b/', $text)) {
            return 'casual';
        }
        
        return 'formal';
    }

    private function splitIntoSentences(string $text): array
    {
        return preg_split('/[.!?]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    }
}
