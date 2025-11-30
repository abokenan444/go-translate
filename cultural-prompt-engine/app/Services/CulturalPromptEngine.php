<?php

namespace App\Services;

use App\Models\Cultural\CulturalProfile;
use App\Models\Cultural\EmotionalTone;
use App\Models\Cultural\IndustryTemplate;
use App\Models\Cultural\TaskTemplate;
use Illuminate\Support\Arr;

class CulturalPromptEngine
{
    public function buildPrompt(
        string $sourceText,
        string $sourceLocale,
        string $targetCultureCode,
        string $taskKey,
        ?string $industryKey = null,
        ?string $emotionalToneKey = null,
        array $extra = []
    ): array {
        $culture = CulturalProfile::where('code', $targetCultureCode)->first();
        $task    = TaskTemplate::where('key', $taskKey)->first();
        $industry = $industryKey
            ? IndustryTemplate::where('key', $industryKey)->first()
            : null;
        $tone = $emotionalToneKey
            ? EmotionalTone::where('key', $emotionalToneKey)->first()
            : null;

        if (! $culture || ! $task) {
            throw new \RuntimeException('Missing culture or task template for prompt generation.');
        }

        $systemParts = [];

        $systemParts[] = 'You are an expert cultural and emotional localization assistant called CulturalTranslate.';
        $systemParts[] = 'Your job is to culturally adapt content, not just translate words.';
        $systemParts[] = 'Always keep the original business goal, but rewrite the message so it feels natural, safe and persuasive in the target culture.';

        $systemParts[] = 'Target culture profile: ' . json_encode([
            'code'        => $culture->code,
            'name'        => $culture->name,
            'locale'      => $culture->locale,
            'region'      => $culture->region,
            'values'      => $culture->values_json,
            'examples'    => $culture->examples_json,
        ], JSON_UNESCAPED_UNICODE);

        if ($tone) {
            $systemParts[] = 'Desired emotional tone: ' . json_encode([
                'key'        => $tone->key,
                'name'       => $tone->name,
                'parameters' => $tone->parameters_json,
            ], JSON_UNESCAPED_UNICODE);
        }

        if ($industry) {
            $systemParts[] = 'Industry context: ' . json_encode([
                'key'   => $industry->key,
                'name'  => $industry->name,
                'brief' => $industry->description,
            ], JSON_UNESCAPED_UNICODE);
        }

        $systemParts[] = 'Forbidden behaviours: no hate speech, no explicit sexual content, no political propaganda, no illegal activity. If the user asks for these, politely refuse.';

        $forbiddenTerms = config('cultural_prompts.forbidden_terms', []);
        if (! empty($forbiddenTerms)) {
            $systemParts[] = 'Avoid the following sensitive terms unless absolutely necessary: ' . implode(', ', $forbiddenTerms) . '.';
        }

        $systemPrompt = implode("\n\n", $systemParts);

        $taskPrompt = $task->prompt_template;

        $userContent = [
            'source_locale' => $sourceLocale,
            'target_culture_code' => $culture->code,
            'task_key' => $task->key,
            'industry_key' => $industry?->key,
            'emotional_tone_key' => $tone?->key,
            'source_text' => $sourceText,
            'extra' => $extra,
        ];

        $userPrompt = $taskPrompt . "\n\n" . '--- CONTEXT JSON ---' . "\n" . json_encode($userContent, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user',   'content' => $userPrompt],
        ];

        return [
            'model'    => 'gpt-4o-mini',
            'messages' => $messages,
        ];
    }

    public function enforcePostProcessing(string $generatedText): string
    {
        $forbidden = config('cultural_prompts.forbidden_terms', []);
        $cleaned = $generatedText;

        foreach ($forbidden as $term) {
            if (! $term) {
                continue;
            }
            $cleaned = str_ireplace($term, '[filtered]', $cleaned);
        }

        return $cleaned;
    }
}
