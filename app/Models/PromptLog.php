<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromptLog extends Model
{
    protected $fillable = [
        'task_key',
        'culture_code',
        'tone_code',
        'emotion_code',
        'source_lang',
        'target_lang',
        'prompt_text',
        'input_text',
        'output_text',
        'token_count',
        'response_time_ms',
        'from_cache',
        'cost',
        'metadata',
    ];

    protected $casts = [
        'from_cache' => 'boolean',
        'metadata' => 'array',
        'cost' => 'decimal:6',
    ];

    /**
     * حساب التكلفة التقديرية بناءً على عدد الـ tokens
     */
    public static function estimateCost(int $tokenCount): float
    {
        // تكلفة GPT-4: ~$0.03 per 1K tokens
        return ($tokenCount / 1000) * 0.03;
    }

    /**
     * الحصول على إحصائيات الاستخدام
     */
    public static function getStatistics(string $period = 'today')
    {
        $query = static::query();
        
        switch ($period) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->where('created_at', '>=', now()->subWeek());
                break;
            case 'month':
                $query->where('created_at', '>=', now()->subMonth());
                break;
        }
        
        return [
            'total_requests' => $query->count(),
            'cache_hits' => $query->where('from_cache', true)->count(),
            'total_tokens' => $query->sum('token_count'),
            'total_cost' => $query->sum('cost'),
            'avg_response_time' => $query->avg('response_time_ms'),
        ];
    }
}
