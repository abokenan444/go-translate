<?php

namespace App\Services;

use App\Models\PromptLog;
use Illuminate\Support\Facades\Log;

class PromptLogger
{
    /**
     * تسجيل استخدام Prompt
     */
    public static function log(
        string $taskKey,
        string $cultureCode,
        ?string $toneCode,
        ?string $emotionCode,
        string $sourceLang,
        string $targetLang,
        string $generatedPrompt,
        ?string $inputText,
        ?string $outputText,
        int $responseTimeMs = 0,
        bool $fromCache = false,
        ?array $metadata = []
    ): void {
        try {
            $tokenCount = self::estimateTokenCount($generatedPrompt);
            $cost = PromptLog::estimateCost($tokenCount);

            PromptLog::create([
                'task_key' => $taskKey,
                'culture_code' => $cultureCode,
                'tone_code' => $toneCode,
                'emotion_code' => $emotionCode,
                'source_lang' => $sourceLang,
                'target_lang' => $targetLang,
                'prompt_text' => $generatedPrompt,
                'input_text' => $inputText,
                'output_text' => $outputText,
                'token_count' => $tokenCount,
                'response_time_ms' => $responseTimeMs,
                'from_cache' => $fromCache,
                'cost' => $cost,
                'metadata' => $metadata,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log prompt', [
                'error' => $e->getMessage(),
                'task_key' => $taskKey,
            ]);
        }
    }

    /**
     * تقدير عدد الـ tokens تقريبياً
     */
    private static function estimateTokenCount(string $text): int
    {
        // تقدير تقريبي: كل 4 أحرف = 1 token
        return (int) ceil(strlen($text) / 4);
    }

    /**
     * الحصول على إحصائيات الاستخدام
     */
    public static function getStatistics(string $period = 'today'): array
    {
        return PromptLog::getStatistics($period);
    }
}
