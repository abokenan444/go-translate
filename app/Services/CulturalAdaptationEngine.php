<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class CulturalAdaptationEngine
{
    /**
     * Get cultural profile for a language
     */
    public function getProfile(string $cultureCode): ?array
    {
        return Cache::remember("cultural_profile_{$cultureCode}", 3600, function () use ($cultureCode) {
            $profile = DB::table('cultural_profiles')
                ->where('culture_code', $cultureCode)
                ->where('is_active', 1)
                ->first();
            
            if (!$profile) {
                return null;
            }
            
            return [
                'culture_code' => $profile->culture_code,
                'culture_name' => $profile->culture_name,
                'native_name' => $profile->native_name,
                'description' => $profile->description,
                'characteristics' => json_decode($profile->characteristics ?? '{}', true),
                'preferred_tones' => json_decode($profile->preferred_tones ?? '[]', true),
                'taboos' => json_decode($profile->taboos ?? '[]', true),
                'formality_level' => $profile->formality_level ?? 'neutral',
                'directness' => $profile->directness ?? 'moderate',
                'uses_honorifics' => (bool)$profile->uses_honorifics,
                'emotional_expressiveness' => $profile->emotional_expressiveness ?? 5,
                'common_expressions' => json_decode($profile->common_expressions ?? '[]', true),
                'text_direction' => $profile->text_direction ?? 'ltr',
                'system_prompt' => $profile->system_prompt ?? '',
                'translation_guidelines' => $profile->translation_guidelines ?? '',
            ];
        });
    }
    
    /**
     * Get emotional tone profile
     */
    public function getTone(string $toneCode): ?array
    {
        return Cache::remember("emotional_tone_{$toneCode}", 3600, function () use ($toneCode) {
            $tone = DB::table('emotional_tones')
                ->where('tone_code', $toneCode)
                ->where('is_active', 1)
                ->first();
            
            if (!$tone) {
                return null;
            }
            
            return [
                'tone_code' => $tone->tone_code,
                'tone_name' => $tone->tone_name,
                'tone_name_en' => $tone->tone_name_en,
                'description' => $tone->description,
                'formality_score' => $tone->formality_score ?? 5,
                'warmth_score' => $tone->warmth_score ?? 5,
                'enthusiasm_score' => $tone->enthusiasm_score ?? 5,
                'directness_score' => $tone->directness_score ?? 5,
                'system_instructions' => $tone->system_instructions ?? '',
                'keywords_to_use' => json_decode($tone->keywords_to_use ?? '[]', true),
                'keywords_to_avoid' => json_decode($tone->keywords_to_avoid ?? '[]', true),
                'suitable_for' => json_decode($tone->suitable_for ?? '[]', true),
            ];
        });
    }
    
    /**
     * Get industry template
     */
    public function getIndustry(string $industryCode): ?array
    {
        return Cache::remember("industry_template_{$industryCode}", 3600, function () use ($industryCode) {
            $industry = DB::table('industry_templates')
                ->where('industry_code', $industryCode)
                ->where('is_active', 1)
                ->first();
            
            if (!$industry) {
                return null;
            }
            
            return [
                'industry_code' => $industry->industry_code,
                'industry_name' => $industry->industry_name,
                'industry_name_en' => $industry->industry_name_en,
                'description' => $industry->description,
                'common_terms' => json_decode($industry->common_terms ?? '{}', true),
                'glossary' => json_decode($industry->glossary ?? '{}', true),
                'preferred_tones' => json_decode($industry->preferred_tones ?? '[]', true),
                'system_prompt' => $industry->system_prompt ?? '',
                'translation_rules' => json_decode($industry->translation_rules ?? '[]', true),
            ];
        });
    }
    
    /**
     * Get task template
     */
    public function getTaskTemplate(string $taskCode): ?array
    {
        return Cache::remember("task_template_{$taskCode}", 3600, function () use ($taskCode) {
            $task = DB::table('task_templates')
                ->where('task_code', $taskCode)
                ->where('is_active', 1)
                ->first();
            
            if (!$task) {
                return null;
            }
            
            return [
                'task_code' => $task->task_code,
                'task_name' => $task->task_name,
                'task_name_en' => $task->task_name_en,
                'category' => $task->category,
                'type' => $task->type,
                'description' => $task->description,
                'system_prompt' => $task->system_prompt ?? '',
                'user_prompt_template' => $task->user_prompt_template ?? '',
                'recommended_tones' => json_decode($task->recommended_tones ?? '[]', true),
                'recommended_industries' => json_decode($task->recommended_industries ?? '[]', true),
            ];
        });
    }
    
    /**
     * Get all available cultures
     */
    public function getAllCultures(): array
    {
        return Cache::remember('all_cultures', 3600, function () {
            return DB::table('cultural_profiles')
                ->where('is_active', 1)
                ->orderBy('priority')
                ->get()
                ->map(function ($profile) {
                    return [
                        'code' => $profile->culture_code,
                        'name' => $profile->culture_name,
                        'native_name' => $profile->native_name,
                    ];
                })
                ->toArray();
        });
    }
    
    /**
     * Get all available tones
     */
    public function getAllTones(): array
    {
        return Cache::remember('all_tones', 3600, function () {
            return DB::table('emotional_tones')
                ->where('is_active', 1)
                ->orderBy('priority')
                ->get()
                ->map(function ($tone) {
                    return [
                        'code' => $tone->tone_code,
                        'name' => $tone->tone_name,
                        'name_en' => $tone->tone_name_en,
                        'icon' => $tone->icon ?? '',
                        'color' => $tone->color ?? '#000000',
                    ];
                })
                ->toArray();
        });
    }
    
    /**
     * Get all available industries
     */
    public function getAllIndustries(): array
    {
        return Cache::remember('all_industries', 3600, function () {
            return DB::table('industry_templates')
                ->where('is_active', 1)
                ->orderBy('priority')
                ->get()
                ->map(function ($industry) {
                    return [
                        'code' => $industry->industry_code,
                        'name' => $industry->industry_name,
                        'name_en' => $industry->industry_name_en,
                        'icon' => $industry->icon ?? '',
                        'color' => $industry->color ?? '#000000',
                    ];
                })
                ->toArray();
        });
    }
    
    /**
     * Get all available task templates
     */
    public function getAllTaskTemplates(): array
    {
        return Cache::remember('all_task_templates', 3600, function () {
            return DB::table('task_templates')
                ->where('is_active', 1)
                ->orderBy('priority')
                ->get()
                ->map(function ($task) {
                    return [
                        'code' => $task->task_code,
                        'name' => $task->task_name,
                        'name_en' => $task->task_name_en,
                        'category' => $task->category,
                        'description' => $task->description,
                        'is_featured' => (bool)$task->is_featured,
                    ];
                })
                ->toArray();
        });
    }
}
