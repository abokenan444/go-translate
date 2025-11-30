<?php

namespace App\Services\CIE;

use Illuminate\Support\Str;

class CulturalIntelligenceEngine
{
    public function analyzeCulture(string $cultureOrLanguage): array
    {
        $code = Str::lower($cultureOrLanguage);
        return [
            'code' => $code,
            'tone' => in_array($code, ['jp','ja','kr','ko']) ? 'formal' : 'neutral',
            'sensitivity' => 'medium',
            'directness' => 'balanced',
            'allowed_references' => ['local idioms','neutral metaphors'],
            'forbidden' => ['political slurs','religious mockery'],
            'marketing_style' => 'trust-focused',
        ];
    }

    public function modelBrandVoice(array $input): array
    {
        return [
            'tone' => $input['tone'] ?? 'friendly',
            'formality' => $input['formality'] ?? 'medium',
            'vocabulary_use' => $input['vocabulary_use'] ?? [],
            'vocabulary_avoid' => $input['vocabulary_avoid'] ?? [],
            'rules' => $input['rules'] ?? [],
        ];
    }

    public function reconstructMeaning(string $text, array $context): array
    {
        return [
            'original' => $text,
            'preserve' => ['intent','emotion','politeness'],
            'transformations' => ['idioms_rephrased' => true, 'cultural_refs_swapped' => true],
        ];
    }

    public function normalizeTerminology(string $text, array $glossary): array
    {
        $normalized = $text;
        foreach ($glossary as $term => $preferred) {
            $normalized = str_ireplace($term, $preferred, $normalized);
        }
        return ['text' => $normalized];
    }
    
    public function emotionMap(string $text, string $language = 'auto'): array
    {
        $signals = [
            'anger' => 0,
            'sarcasm' => 0,
            'threat' => 0,
            'frustration' => 0,
            'encouragement' => 0,
            'inspiration' => 0,
            'warmth' => 0,
        ];
        $lower = mb_strtolower($text);
        $heuristics = [
            'anger' => ['angry', 'furious', 'outrage', 'غضب', 'ساخط'],
            'sarcasm' => ['sarcas', 'irony', 'ساخر', 'سخرية'],
            'threat' => ['threat', 'warn', 'تهديد', 'سنعاقب'],
            'frustration' => ['frustrat', 'annoyed', 'محبط', 'غاضب'],
            'encouragement' => ['well done', 'keep going', 'أحسنت', 'تابع'],
            'inspiration' => ['inspire', 'vision', 'إلهام', 'رؤية'],
            'warmth' => ['kind', 'warm', 'ود', 'مرحبا'],
        ];
        foreach ($heuristics as $emo => $keys) {
            foreach ($keys as $k) {
                if (mb_strpos($lower, $k) !== false) {
                    $signals[$emo] += 25;
                }
            }
            $signals[$emo] = min(100, $signals[$emo]);
        }
        arsort($signals);
        return [
            'language' => $language,
            'signals' => $signals,
            'dominant' => array_key_first($signals),
        ];
    }

    public function mapEmotion(string $text): array
    {
        return [
            'anger' => 0.02,
            'sarcasm' => 0.03,
            'threat' => 0.0,
            'frustration' => 0.05,
            'encouragement' => 0.4,
            'inspiration' => 0.3,
            'warmth' => 0.2,
            'dominant' => 'encouragement',
        ];
    }

    public function getIndustryBehavior(string $industry, ?string $language = null, ?string $culture = null): ?array
    {
        $query = \App\Models\IndustryBehavior::where('industry', $industry)->where('active', true);
        
        if ($language) {
            $query->where(function($q) use ($language) {
                $q->where('language', $language)->orWhereNull('language');
            });
        }
        
        if ($culture) {
            $query->where(function($q) use ($culture) {
                $q->where('culture', $culture)->orWhereNull('culture');
            });
        }
        
        $behavior = $query->first();
        
        if (!$behavior) return null;
        
        return [
            'industry' => $behavior->industry,
            'tone' => $behavior->tone,
            'vocabulary_preferred' => $behavior->vocabulary_preferred ?? [],
            'vocabulary_avoid' => $behavior->vocabulary_avoid ?? [],
            'style_rules' => $behavior->style_rules ?? [],
            'description' => $behavior->description,
        ];
    }

    public function applyIndustryBehavior(string $text, array $behavior): string
    {
        $processed = $text;
        
        if (!empty($behavior['vocabulary_preferred'])) {
            foreach ($behavior['vocabulary_preferred'] as $term => $preferred) {
                $processed = str_ireplace($term, $preferred, $processed);
            }
        }
        
        if (!empty($behavior['vocabulary_avoid'])) {
            foreach ($behavior['vocabulary_avoid'] as $term) {
                $processed = preg_replace('/\b' . preg_quote($term, '/') . '\b/i', '[REDACTED]', $processed);
            }
        }
        
        return $processed;
    }
}
