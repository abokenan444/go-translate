<?php
namespace App\Services;

use App\Models\Culture;
use App\Models\Tone;
use App\Models\Industry;
use App\Models\TaskTemplate;
use App\Models\PromptPreset;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CulturalPromptEngine
{
    protected $apiKey;
    protected $model = 'gpt-5';

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
    }

    /**
     * Translate text with cultural context - for demo purposes
     */
    public function translateWithCulturalContext(string $text, string $sourceLang, string $targetLang, array $options = []): string
    {
        try {
            $formalityLevel = $options['formality_level'] ?? 'neutral';
            $preserveTone = $options['preserve_tone'] ?? true;
            $adaptIdioms = $options['adapt_idioms'] ?? true;

            $systemPrompt = "You are a professional cultural translator. Your job is to translate text while preserving cultural context, emotional tone, and meaning. Always provide natural, culturally appropriate translations that resonate with the target audience.";

            $userPrompt = "Translate the following text from {$sourceLang} to {$targetLang}.\n\n";
            $userPrompt .= "Formality level: {$formalityLevel}\n";
            $userPrompt .= "Preserve tone: " . ($preserveTone ? 'yes' : 'no') . "\n";
            $userPrompt .= "Adapt idioms: " . ($adaptIdioms ? 'yes' : 'no') . "\n\n";
            $userPrompt .= "Text to translate:\n{$text}\n\n";
            $userPrompt .= "Provide only the translation without any explanations or additional text.";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post('https://api.openai.com/v1/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt
                    ],
                    [
                        'role' => 'user',
                        'content' => $userPrompt
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 2000,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $translation = $data['choices'][0]['message']['content'] ?? '';
                return trim($translation);
            }

            throw new \Exception('Translation API request failed: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Cultural translation error: ' . $e->getMessage());
            throw new \Exception('Translation service is temporarily unavailable');
        }
    }

    public function buildPrompt(array $payload): array
    {
        $culture = Culture::where('slug', $payload['culture_slug'] ?? null)->first();
        $tone    = Tone::where('slug', $payload['tone_slug'] ?? null)->first();
        $industry = Industry::where('slug', $payload['industry_slug'] ?? null)->first();
        $task     = TaskTemplate::where('slug', $payload['task_slug'] ?? null)->first();

        if (! $culture || ! $tone || ! $task) {
            throw new \RuntimeException('Missing culture, tone, or task template for prompt generation.');
        }

        $preset = null;
        if (! empty($payload['preset_slug'])) {
            $preset = PromptPreset::where('slug', $payload['preset_slug'])->first();
        }

        $systemPieces = [];
        $systemPieces[] = 'You are a senior cultural and emotional localization expert.';
        $systemPieces[] = 'Target culture: '.$culture->name.' (region: '.($culture->region ?? 'n/a').').';

        if ($culture->traits) {
            $traits = implode(', ', Arr::wrap($culture->traits));
            $systemPieces[] = 'Cultural traits to respect: '.$traits.'.';
        }

        $systemPieces[] = 'Tone: '.$tone->name.' – '.$tone->description;

        if ($industry) {
            $systemPieces[] = 'Industry / domain: '.$industry->name.'.';
        }

        if ($preset && $preset->system_prompt) {
            $systemPieces[] = $preset->system_prompt;
        }

        $systemPrompt = implode("\n", $systemPieces);

        $userPrompt = $task->base_prompt ?? 'Translate the following text preserving meaning and emotional impact.';

        $replacements = [
            '{source_lang}'   => $payload['source_language'] ?? 'ar',
            '{target_lang}'   => $payload['target_language'] ?? 'en',
            '{tone}'          => $tone->name,
            '{industry}'      => $industry->name ?? 'General',
            '{culture_name}'  => $culture->name,
            '{region}'        => $culture->region ?? '',
        ];

        $userPrompt = strtr($userPrompt, $replacements);

        if ($preset && $preset->user_prompt_template) {
            $extra = strtr($preset->user_prompt_template, $replacements);
            $userPrompt .= "\n\n".$extra;
        }

        return [
            'system' => $systemPrompt,
            'user'   => $userPrompt,
        ];
    }
}
