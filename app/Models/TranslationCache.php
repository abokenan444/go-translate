<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TranslationCache extends Model
{
    protected $table = 'translation_cache';

    protected $fillable = [
        'source_lang',
        'target_lang',
        'source_text',
        'translated_text',
        'source_hash',
        'content_type',
        'industry',
        'tone',
        'culture_code',
        'hit_count',
        'last_used_at',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
    ];

    /**
     * البحث عن ترجمة في الكاش
     */
    public static function findTranslation(
        string $sourceText,
        string $sourceLang,
        string $targetLang,
        ?string $contentType = null
    ): ?string {
        $hash = md5(trim(strtolower($sourceText)));
        
        $cacheKey = "translation_{$hash}_{$sourceLang}_{$targetLang}_{$contentType}";
        
        return Cache::remember($cacheKey, 3600, function () use ($hash, $sourceLang, $targetLang, $contentType) {
            $query = static::where('source_hash', $hash)
                ->where('source_lang', $sourceLang)
                ->where('target_lang', $targetLang);
            
            if ($contentType) {
                $query->where('content_type', $contentType);
            }
            
            $cached = $query->first();
            
            if ($cached) {
                // تحديث الإحصائيات
                $cached->increment('hit_count');
                $cached->update(['last_used_at' => now()]);
                
                return $cached->translated_text;
            }
            
            return null;
        });
    }

    /**
     * حفظ ترجمة جديدة في الكاش
     */
    public static function store(
        string $sourceText,
        string $translatedText,
        string $sourceLang,
        string $targetLang,
        ?string $contentType = null,
        ?string $industry = null,
        ?string $tone = null,
        ?string $cultureCode = null
    ): void {
        $hash = md5(trim(strtolower($sourceText)));
        
        static::updateOrCreate(
            [
                'source_hash' => $hash,
                'source_lang' => $sourceLang,
                'target_lang' => $targetLang,
                'content_type' => $contentType,
            ],
            [
                'source_text' => $sourceText,
                'translated_text' => $translatedText,
                'industry' => $industry,
                'tone' => $tone,
                'culture_code' => $cultureCode,
                'last_used_at' => now(),
            ]
        );
        
        // مسح الكاش القديم
        $cacheKey = "translation_{$hash}_{$sourceLang}_{$targetLang}_{$contentType}";
        Cache::forget($cacheKey);
    }

    /**
     * تنظيف الكاش القديم (أكثر من 30 يوم بدون استخدام)
     */
    public static function cleanup(int $days = 30): int
    {
        return static::where('last_used_at', '<', now()->subDays($days))
            ->where('hit_count', '<', 5) // فقط الترجمات قليلة الاستخدام
            ->delete();
    }
}
