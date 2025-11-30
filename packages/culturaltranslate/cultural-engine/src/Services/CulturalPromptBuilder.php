<?php

namespace CulturalTranslate\CulturalEngine\Services;

use CulturalTranslate\CulturalEngine\Models\CultureProfile;
use CulturalTranslate\CulturalEngine\Models\EmotionalTone;
use CulturalTranslate\CulturalEngine\Models\Industry;
use CulturalTranslate\CulturalEngine\Models\TaskTemplate;

class CulturalPromptBuilder
{
    public function __construct(
        protected array $config = []
    ) {}

    public function buildPrompt(array $params): string
    {
        $sourceText   = $params['source_text']   ?? '';
        $sourceLang   = $params['source_lang']   ?? $params['source_language'] ?? 'auto';
        $targetLang   = $params['target_lang']   ?? $params['target_language'] ?? 'en';
        $cultureKey   = $params['culture_key']   ?? $this->config['default_culture_key'];
        $toneKey      = $params['tone_key']      ?? $this->config['default_tone_key'];
        $industryKey  = $params['industry_key']  ?? $this->config['default_industry_key'];
        $taskKey      = $params['task_key']      ?? $this->config['default_task_key'];
        $extraContext = $params['context']       ?? '';

        $culture  = CultureProfile::where('key', $cultureKey)->first();
        $tone     = EmotionalTone::where('key', $toneKey)->first();
        $industry = Industry::where('key', $industryKey)->first();
        $task     = TaskTemplate::where('key', $taskKey)->first();

        if (! $task) {
            return strtr($this->config['fallback_prompt'] ?? '', [
                '{source_lang}' => $sourceLang,
                '{target_lang}' => $targetLang,
                '{source_text}' => $sourceText,
            ]);
        }

        $prompt = $task->base_prompt;

        $promptParts = [
            '{source_lang}'   => $sourceLang,
            '{target_lang}'   => $targetLang,
            '{source_text}'   => $sourceText,
            '{extra_context}' => $extraContext,
            '{culture_notes}' => $culture?->audience_notes ?? '',
            '{culture_desc}'  => $culture?->description ?? '',
            '{tone_label}'    => $tone?->label ?? '',
            '{tone_desc}'     => $tone?->description ?? '',
            '{industry_name}' => $industry?->name ?? 'general',
            '{industry_desc}' => $industry?->description ?? '',
        ];

        return strtr($prompt, $promptParts);
    }
}
