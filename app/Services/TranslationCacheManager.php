<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class TranslationCacheManager
{
    /**
     * Generate cache key
     */
    public function generateKey(
        string $text,
        string $sourceLang,
        string $targetLang,
        string $tone,
        ?string $industry = null
    ): string {
        $parts = [
            md5($text),
            $sourceLang,
            $targetLang,
            $tone,
            $industry ?? 'general',
        ];
        
        return 'translation_' . implode('_', $parts);
    }
    
    /**
     * Get cached translation
     */
    public function get(string $cacheKey): ?array
    {
        $cached = DB::table('translation_cache')
            ->where('cache_key', $cacheKey)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->first();
        
        if (!$cached) {
            return null;
        }
        
        // Update hit count and last used
        DB::table('translation_cache')
            ->where('id', $cached->id)
            ->update([
                'hit_count' => $cached->hit_count + 1,
                'last_used_at' => now(),
            ]);
        
        return [
            'translated_text' => $cached->translated_text,
            'quality_score' => $cached->quality_score,
            'tokens_used' => $cached->tokens_used,
            'response_time_ms' => $cached->response_time_ms,
            'hit_count' => $cached->hit_count + 1,
        ];
    }
    
    /**
     * Store translation in cache
     */
    public function store(string $cacheKey, array $data): void
    {
        $sourceText = $data['source_text'] ?? '';
        $translatedText = $data['translated_text'];
        $sourceLang = $data['source_language'] ?? '';
        $targetLang = $data['target_language'] ?? '';
        $tone = $data['tone'] ?? '';
        $industry = $data['industry'] ?? null;
        $taskType = $data['task_type'] ?? null;
        $qualityScore = $data['quality_score'] ?? null;
        $tokensUsed = $data['tokens_used'] ?? 0;
        $responseTime = $data['response_time_ms'] ?? 0;
        
        // Calculate expiration (30 days for high quality, 7 days for low quality)
        $expiresAt = $qualityScore && $qualityScore >= 80
            ? now()->addDays(30)
            : now()->addDays(7);
        
        DB::table('translation_cache')->updateOrInsert(
            ['cache_key' => $cacheKey],
            [
                'source_text' => $sourceText,
                'source_language' => $sourceLang,
                'target_language' => $targetLang,
                'tone' => $tone,
                'industry' => $industry,
                'task_type' => $taskType,
                'translated_text' => $translatedText,
                'metadata' => json_encode([
                    'cached_at' => now()->toIso8601String(),
                    'quality_score' => $qualityScore,
                ]),
                'tokens_used' => $tokensUsed,
                'response_time_ms' => $responseTime,
                'quality_score' => $qualityScore,
                'expires_at' => $expiresAt,
                'updated_at' => now(),
            ]
        );
    }
    
    /**
     * Clear expired cache entries
     */
    public function clearExpired(): int
    {
        return DB::table('translation_cache')
            ->where('expires_at', '<', now())
            ->delete();
    }
    
    /**
     * Get cache statistics
     */
    public function getStats(): array
    {
        $total = DB::table('translation_cache')->count();
        $active = DB::table('translation_cache')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->count();
        
        $totalHits = DB::table('translation_cache')->sum('hit_count');
        $avgQuality = DB::table('translation_cache')->avg('quality_score');
        $totalTokensSaved = DB::table('translation_cache')
            ->selectRaw('SUM(tokens_used * (hit_count - 1)) as saved')
            ->value('saved');
        
        return [
            'total_entries' => $total,
            'active_entries' => $active,
            'expired_entries' => $total - $active,
            'total_hits' => $totalHits ?? 0,
            'average_quality' => round($avgQuality ?? 0, 2),
            'tokens_saved' => $totalTokensSaved ?? 0,
        ];
    }
}
