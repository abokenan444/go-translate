<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class TranslationCacheService
{
    const CACHE_PREFIX = 'translation:';
    const GLOSSARY_PREFIX = 'glossary:';
    const BRAND_VOICE_PREFIX = 'brand_voice:';
    const CULTURAL_MEMORY_PREFIX = 'cultural_memory:';
    
    const DEFAULT_TTL = 86400; // 24 hours
    const LONG_TTL = 604800; // 7 days

    /**
     * Cache translation result
     */
    public function cacheTranslation(string $hash, array $data, int $ttl = self::DEFAULT_TTL): void
    {
        Cache::put(self::CACHE_PREFIX . $hash, $data, $ttl);
    }

    /**
     * Get cached translation
     */
    public function getTranslation(string $hash): ?array
    {
        return Cache::get(self::CACHE_PREFIX . $hash);
    }

    /**
     * Generate cache key for translation
     */
    public function generateTranslationHash(string $text, string $targetLang, array $context = []): string
    {
        return md5($text . $targetLang . json_encode($context));
    }

    /**
     * Cache glossary for quick access
     */
    public function cacheGlossary(int $companyId, array $terms): void
    {
        Cache::put(self::GLOSSARY_PREFIX . $companyId, $terms, self::LONG_TTL);
    }

    /**
     * Get cached glossary
     */
    public function getGlossary(int $companyId): ?array
    {
        return Cache::get(self::GLOSSARY_PREFIX . $companyId);
    }

    /**
     * Cache brand voice profile
     */
    public function cacheBrandVoice(int $brandId, array $profile): void
    {
        Cache::put(self::BRAND_VOICE_PREFIX . $brandId, $profile, self::LONG_TTL);
    }

    /**
     * Get cached brand voice
     */
    public function getBrandVoice(int $brandId): ?array
    {
        return Cache::get(self::BRAND_VOICE_PREFIX . $brandId);
    }

    /**
     * Cache cultural memory
     */
    public function cacheCulturalMemory(int $companyId, array $memory): void
    {
        Cache::put(self::CULTURAL_MEMORY_PREFIX . $companyId, $memory, self::LONG_TTL);
    }

    /**
     * Get cached cultural memory
     */
    public function getCulturalMemory(int $companyId): ?array
    {
        return Cache::get(self::CULTURAL_MEMORY_PREFIX . $companyId);
    }

    /**
     * Invalidate all translation cache for a company
     */
    public function invalidateCompanyCache(int $companyId): void
    {
        Cache::forget(self::GLOSSARY_PREFIX . $companyId);
        Cache::forget(self::CULTURAL_MEMORY_PREFIX . $companyId);
        
        // Clear all translations (using Redis SCAN for pattern matching)
        $pattern = self::CACHE_PREFIX . '*';
        $keys = Redis::keys($pattern);
        if (!empty($keys)) {
            Redis::del($keys);
        }
    }

    /**
     * Get cache statistics
     */
    public function getCacheStats(): array
    {
        $redis = Redis::connection();
        $info = $redis->info('stats');
        
        return [
            'total_keys' => $redis->dbSize(),
            'hits' => $info['keyspace_hits'] ?? 0,
            'misses' => $info['keyspace_misses'] ?? 0,
            'hit_rate' => $this->calculateHitRate($info),
            'memory_used' => $this->getMemoryUsage(),
        ];
    }

    /**
     * Calculate cache hit rate
     */
    private function calculateHitRate(array $info): float
    {
        $hits = $info['keyspace_hits'] ?? 0;
        $misses = $info['keyspace_misses'] ?? 0;
        $total = $hits + $misses;
        
        return $total > 0 ? round(($hits / $total) * 100, 2) : 0;
    }

    /**
     * Get memory usage
     */
    private function getMemoryUsage(): string
    {
        $redis = Redis::connection();
        $info = $redis->info('memory');
        
        return $info['used_memory_human'] ?? '0B';
    }

    /**
     * Warm up cache for frequently accessed data
     */
    public function warmUp(int $companyId): void
    {
        // Preload glossary
        $glossary = \App\Models\GlossaryTerm::where('company_id', $companyId)->get()->toArray();
        if (!empty($glossary)) {
            $this->cacheGlossary($companyId, $glossary);
        }

        // Preload brand voices
        $brandVoices = \App\Models\BrandVoice::where('company_id', $companyId)->get();
        foreach ($brandVoices as $voice) {
            $this->cacheBrandVoice($voice->id, $voice->toArray());
        }

        // Preload cultural memories
        $memories = \App\Models\CulturalMemory::where('company_id', $companyId)->get()->toArray();
        if (!empty($memories)) {
            $this->cacheCulturalMemory($companyId, $memories);
        }
    }
}
