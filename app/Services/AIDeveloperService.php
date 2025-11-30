<?php

namespace App\Services;

use App\Models\AiDevChange;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class AIDeveloperService
{
    public function handlePrompt(string $prompt, User $user): array
    {
        $mode = config('ai_developer.mode', 'review');

        $systemPrompt = <<<'SYS'
You are an AI Developer Agent working on a Laravel project.

GOAL:
- Analyze the project (Laravel/PHP/routes/views/config).
- Help the human owner to implement fixes & features.
- Never directly execute anything. You only PROPOSE actions as JSON.

OUTPUT FORMAT:
Always respond with valid JSON only:

{
  "analysis": "Short explanation in Arabic about what you found and what you will change",
  "actions": [
    {
      "type": "file_edit",
      "description": "What this file change will do (Arabic)",
      "target_path": "routes/web.php",
      "proposed_content": "FULL file content AFTER changes"
    },
    {
      "type": "command",
      "description": "Why we run this command (Arabic)",
      "command": "php artisan migrate"
    },
    {
      "type": "sql",
      "description": "Why we need this SQL (Arabic)",
      "sql": "UPDATE users SET active = 1 WHERE ..."
    }
  ],
  "api_review": {
    "summary": "If the user asked about API issues, give a short diagnosis in Arabic",
    "suggestions": [
      "Add validation to endpoint /api/...",
      "Return JSON:API compliant error format ...."
    ]
  }
}

RULES:
- Do NOT execute anything. Only propose changes.
- For file_edit: ALWAYS return the FULL final content of the file (not patches).
- For commands: use safe Laravel / composer commands only.
- For SQL: use simple, safe statements and never drop entire tables.
- Focus on cultural translation / SaaS / billing logic when relevant.
SYS;

        $openaiKey  = config('ai_developer.openai.api_key');
        $openaiBase = rtrim(config('ai_developer.openai.api_base'), '/');
        $model      = config('ai_developer.openai.model', 'gpt-4.1-mini');

        if (! $openaiKey) {
            return [
                'analysis' => 'لم يتم ضبط مفتاح OpenAI API. يرجى إضافة OPENAI_API_KEY في ملف .env.',
                'changes'  => [],
                'raw'      => null,
            ];
        }

        $projectSummary = $this->buildProjectSummary();

        $response = Http::withToken($openaiKey)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($openaiBase . '/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    [
                        'role' => 'user',
                        'content' => "Project context:
" . $projectSummary . "

User prompt:
" . $prompt,
                    ],
                ],
                'temperature' => 0.3,
            ]);

        if (! $response->successful()) {
            return [
                'analysis' => 'فشل الاتصال بـ OpenAI API: ' . $response->status(),
                'changes'  => [],
                'raw'      => $response->body(),
            ];
        }

        $content = $response->json('choices.0.message.content');

        $json = json_decode($content, true);

        if (! is_array($json)) {
            return [
                'analysis' => 'تعذر تحليل استجابة الذكاء الاصطناعي. تأكد من أن الاستجابة بصيغة JSON صحيحة.',
                'changes'  => [],
                'raw'      => $content,
            ];
        }

        $analysis = $json['analysis'] ?? '';
        $actions  = $json['actions'] ?? [];

        $savedChanges = [];

        foreach ($actions as $action) {
            $type = $action['type'] ?? null;

            if ($type === 'file_edit') {
                $relPath = $action['target_path'] ?? null;
                if (! $relPath) {
                    continue;
                }

                $fullPath = base_path($relPath);

                $original = File::exists($fullPath)
                    ? File::get($fullPath)
                    : '';

                $proposed = $action['proposed_content'] ?? '';

                $diff = $this->makeDiff($original, $proposed);

                $savedChanges[] = AiDevChange::create([
                    'type'             => 'file_edit',
                    'status'           => 'pending',
                    'target_path'      => $relPath,
                    'original_content' => $original,
                    'proposed_content' => $proposed,
                    'diff'             => $diff,
                    'user_id'          => $user->id,
                    'meta'             => [
                        'description' => $action['description'] ?? '',
                    ],
                ]);
            }

            if ($type === 'command') {
                $savedChanges[] = AiDevChange::create([
                    'type'       => 'command',
                    'status'     => 'pending',
                    'command'    => $action['command'] ?? '',
                    'user_id'    => $user->id,
                    'meta'       => [
                        'description' => $action['description'] ?? '',
                    ],
                ]);
            }

            if ($type === 'sql') {
                $savedChanges[] = AiDevChange::create([
                    'type'       => 'sql',
                    'status'     => 'pending',
                    'sql'        => $action['sql'] ?? '',
                    'user_id'    => $user->id,
                    'meta'       => [
                        'description' => $action['description'] ?? '',
                    ],
                ]);
            }
        }

        return [
            'analysis' => $analysis,
            'changes'  => $savedChanges,
            'raw'      => $json,
        ];
    }

    protected function buildProjectSummary(): string
    {
        $root = base_path();
        $paths = [
            $root . '/routes',
            $root . '/app',
            $root . '/resources/views',
        ];

        $summary = [];

        foreach ($paths as $path) {
            if (! is_dir($path)) {
                continue;
            }

            $files = collect(File::allFiles($path))
                ->take(50)
                ->map(function ($file) use ($path) {
                    return str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file->getPathname());
                })
                ->all();

            $summary[] = $path . ":
- " . implode("
- ", $files);
        }

        return implode("

", $summary);
    }

    protected function makeDiff(string $old, string $new): string
    {
        return "--- ORIGINAL ---\n" . $old . "\n\n--- PROPOSED ---\n" . $new;
    }
}
