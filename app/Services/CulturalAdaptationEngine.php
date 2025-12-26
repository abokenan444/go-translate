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
            
            // Get cultural values from JSON
            $values = json_decode($profile->values_json ?? '{}', true);
            
            return [
                'culture_code' => $profile->culture_code ?? $profile->code,
                'culture_name' => $profile->name,
                'native_name' => $profile->name,
                'description' => $profile->description,
                'characteristics' => $values,
                'preferred_tones' => [],
                'taboos' => [],
                'formality_level' => $values['formality'] ?? 'neutral',
                'directness' => $values['directness'] ?? 'moderate',
                'uses_honorifics' => false,
                'emotional_expressiveness' => 5,
                'common_expressions' => [],
                'text_direction' => $values['text_direction'] ?? 'ltr',
                'system_prompt' => '',
                'translation_guidelines' => '',
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
                ->where('key', $toneCode)
                ->first();
            
            if (!$tone) {
                // Return default tone if not found
                return [
                    'tone_code' => $toneCode,
                    'tone_name' => ucfirst($toneCode),
                    'tone_name_en' => ucfirst($toneCode),
                    'description' => "Default {$toneCode} tone",
                    'formality_score' => 5,
                    'warmth_score' => 5,
                    'enthusiasm_score' => 5,
                    'directness_score' => 5,
                    'system_instructions' => '',
                    'keywords_to_use' => [],
                    'keywords_to_avoid' => [],
                    'suitable_for' => [],
                ];
            }
            
            $params = json_decode($tone->parameters_json ?? '{}', true);
            
            return [
                'tone_code' => $tone->key,
                'tone_name' => $tone->name,
                'tone_name_en' => $tone->name,
                'description' => $tone->description ?? '',
                'formality_score' => $params['formality_score'] ?? $tone->intensity ?? 5,
                'warmth_score' => $params['warmth_score'] ?? 5,
                'enthusiasm_score' => $params['enthusiasm_score'] ?? 5,
                'directness_score' => $params['directness_score'] ?? 5,
                'system_instructions' => $params['system_instructions'] ?? '',
                'keywords_to_use' => $params['keywords_to_use'] ?? [],
                'keywords_to_avoid' => $params['keywords_to_avoid'] ?? [],
                'suitable_for' => $params['suitable_for'] ?? [],
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
                        'code' => $profile->culture_code ?? $profile->code,
                        'name' => $profile->name,
                        'native_name' => $profile->name,
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
