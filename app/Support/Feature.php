<?php

namespace App\Support;

use App\Models\FeatureFlag;
use Illuminate\Support\Facades\Cache;

class Feature
{
    /**
     * Check if a feature is enabled
     *
     * @param string $key Feature key (e.g., 'government_portal', 'partner_offers')
     * @return bool
     */
    public static function enabled(string $key): bool
    {
        return Cache::remember("feature_flag:$key", 3600, function () use ($key) {
            $flag = FeatureFlag::where('key', $key)->first();
            
            if (!$flag) {
                return false;
            }
            
            // Check if status is explicitly enabled
            if ($flag->status !== 'enabled') {
                return false;
            }
            
            // Check rollout percentage (0-100)
            if (isset($flag->rollout_percentage) && $flag->rollout_percentage < 100) {
                // Simple bucket algorithm: hash user/session ID to determine if enabled
                $userId = auth()->id() ?? session()->getId();
                $bucket = crc32($key . $userId) % 100;
                
                return $bucket < $flag->rollout_percentage;
            }
            
            return true;
        });
    }
    
    /**
     * Check if feature is disabled
     *
     * @param string $key
     * @return bool
     */
    public static function disabled(string $key): bool
    {
        return !static::enabled($key);
    }
    
    /**
     * Get feature configuration/metadata
     *
     * @param string $key
     * @return array|null
     */
    public static function config(string $key): ?array
    {
        return Cache::remember("feature_config:$key", 3600, function () use ($key) {
            $flag = FeatureFlag::where('key', $key)->first();
            
            if (!$flag) {
                return null;
            }
            
            return [
                'key' => $flag->key,
                'name' => $flag->name,
                'description' => $flag->description,
                'enabled' => static::enabled($key),
                'rollout_percentage' => $flag->rollout_percentage ?? 0,
            ];
        });
    }
    
    /**
     * Clear feature flag cache
     *
     * @param string|null $key Specific key or all if null
     * @return void
     */
    public static function clearCache(?string $key = null): void
    {
        if ($key) {
            Cache::forget("feature_flag:$key");
            Cache::forget("feature_config:$key");
        } else {
            Cache::tags(['feature_flags'])->flush();
        }
    }
}
