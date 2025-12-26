<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CulturalPrompt extends Model
{
    protected $fillable = [
        'category',
        'language_pair',
        'tone',
        'industry',
        'prompt_text',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    /**
     * Get active prompts for specific criteria
     */
    public static function getPromptsFor(
        ?string $category = null,
        ?string $languagePair = null,
        ?string $tone = null,
        ?string $industry = null
    ) {
        return static::query()
            ->where('is_active', true)
            ->when($category, fn($q) => $q->where('category', $category))
            ->when($languagePair, fn($q) => $q->where(function($query) use ($languagePair) {
                $query->where('language_pair', $languagePair)
                      ->orWhere('language_pair', 'all');
            }))
            ->when($tone, fn($q) => $q->where(function($query) use ($tone) {
                $query->where('tone', $tone)
                      ->orWhere('tone', 'all');
            }))
            ->when($industry, fn($q) => $q->where(function($query) use ($industry) {
                $query->where('industry', $industry)
                      ->orWhere('industry', 'all');
            }))
            ->orderBy('priority', 'desc')
            ->get();
    }

    /**
     * Get system prompt for language pair
     */
    public static function getSystemPrompt(string $languagePair): ?string
    {
        $prompt = static::where('category', 'system')
            ->where('language_pair', $languagePair)
            ->where('is_active', true)
            ->orderBy('priority', 'desc')
            ->first();

        return $prompt?->prompt_text;
    }

    /**
     * Get industry-specific prompt
     */
    public static function getIndustryPrompt(string $languagePair, string $industry, string $tone = 'professional'): ?string
    {
        $prompt = static::where('category', 'industry')
            ->where('language_pair', $languagePair)
            ->where('industry', $industry)
            ->where(function($query) use ($tone) {
                $query->where('tone', $tone)
                      ->orWhere('tone', 'all');
            })
            ->where('is_active', true)
            ->orderBy('priority', 'desc')
            ->first();

        return $prompt?->prompt_text;
    }

    /**
     * Build comprehensive prompt for translation
     */
    public static function buildTranslationPrompt(
        string $languagePair,
        ?string $industry = null,
        ?string $tone = null,
        ?string $context = null
    ): string {
        $prompts = [];

        // System prompt (core rules)
        $systemPrompt = static::getSystemPrompt($languagePair);
        if ($systemPrompt) {
            $prompts[] = $systemPrompt;
        }

        // Industry-specific
        if ($industry) {
            $industryPrompt = static::where('category', 'industry')
                ->where('language_pair', $languagePair)
                ->where('industry', $industry)
                ->where('is_active', true)
                ->orderBy('priority', 'desc')
                ->first();
            
            if ($industryPrompt) {
                $prompts[] = $industryPrompt->prompt_text;
            }
        }

        // Tone-specific
        if ($tone) {
            $tonePrompt = static::where('category', 'tone')
                ->where('language_pair', $languagePair)
                ->where('tone', $tone)
                ->where('is_active', true)
                ->orderBy('priority', 'desc')
                ->first();
            
            if ($tonePrompt) {
                $prompts[] = $tonePrompt->prompt_text;
            }
        }

        // Context-specific
        if ($context) {
            $contextPrompt = static::where('category', 'context')
                ->where('language_pair', $languagePair)
                ->where('industry', $context)
                ->where('is_active', true)
                ->orderBy('priority', 'desc')
                ->first();
            
            if ($contextPrompt) {
                $prompts[] = $contextPrompt->prompt_text;
            }
        }

        // Adaptation rules
        $adaptationPrompt = static::where('category', 'adaptation')
            ->where('language_pair', $languagePair)
            ->where('is_active', true)
            ->orderBy('priority', 'desc')
            ->first();
        
        if ($adaptationPrompt) {
            $prompts[] = $adaptationPrompt->prompt_text;
        }

        // Sensitivity guidelines
        $sensitivityPrompt = static::where('category', 'sensitivity')
            ->where('language_pair', $languagePair)
            ->where('is_active', true)
            ->orderBy('priority', 'desc')
            ->first();
        
        if ($sensitivityPrompt) {
            $prompts[] = $sensitivityPrompt->prompt_text;
        }

        // Quality checklist
        $qualityPrompt = static::where('category', 'quality')
            ->where('is_active', true)
            ->orderBy('priority', 'desc')
            ->first();
        
        if ($qualityPrompt) {
            $prompts[] = $qualityPrompt->prompt_text;
        }

        return implode("\n\n---\n\n", $prompts);
    }
}
