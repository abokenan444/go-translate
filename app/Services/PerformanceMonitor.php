<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PerformanceMonitor
{
    /**
     * Track certified translation performance
     */
    public static function trackTranslation($certNumber, $metrics)
    {
        $data = [
            'cert_number' => $certNumber,
            'timestamp' => now()->toDateTimeString(),
            'upload_time' => $metrics['upload_time'] ?? 0,
            'extraction_time' => $metrics['extraction_time'] ?? 0,
            'translation_time' => $metrics['translation_time'] ?? 0,
            'pdf_generation_time' => $metrics['pdf_generation_time'] ?? 0,
            'total_time' => $metrics['total_time'] ?? 0,
            'file_size' => $metrics['file_size'] ?? 0,
            'text_length' => $metrics['text_length'] ?? 0,
            'memory_usage' => memory_get_peak_usage(true),
        ];
        
        // Log to file
        Log::channel('performance')->info('Certified Translation Performance', $data);
        
        // Store in cache for dashboard
        self::updateDashboardMetrics($data);
        
        return $data;
    }
    
    /**
     * Update dashboard metrics
     */
    private static function updateDashboardMetrics($data)
    {
        $key = 'certified_translation_metrics';
        $metrics = Cache::get($key, []);
        
        // Keep last 100 records
        $metrics[] = $data;
        if (count($metrics) > 100) {
            array_shift($metrics);
        }
        
        Cache::put($key, $metrics, now()->addDays(7));
    }
    
    /**
     * Get performance statistics
     */
    public static function getStatistics()
    {
        $metrics = Cache::get('certified_translation_metrics', []);
        
        if (empty($metrics)) {
            return [
                'total_translations' => 0,
                'avg_total_time' => 0,
                'avg_translation_time' => 0,
                'avg_file_size' => 0,
                'peak_memory' => 0,
            ];
        }
        
        $totalCount = count($metrics);
        
        return [
            'total_translations' => $totalCount,
            'avg_total_time' => round(array_sum(array_column($metrics, 'total_time')) / $totalCount, 2),
            'avg_translation_time' => round(array_sum(array_column($metrics, 'translation_time')) / $totalCount, 2),
            'avg_file_size' => round(array_sum(array_column($metrics, 'file_size')) / $totalCount / 1024, 2),
            'peak_memory' => max(array_column($metrics, 'memory_usage')),
            'last_24h' => self::getLast24HoursCount($metrics),
        ];
    }
    
    /**
     * Get count of translations in last 24 hours
     */
    private static function getLast24HoursCount($metrics)
    {
        $yesterday = now()->subDay();
        $count = 0;
        
        foreach ($metrics as $metric) {
            if (strtotime($metric['timestamp']) > $yesterday->timestamp) {
                $count++;
            }
        }
        
        return $count;
    }
    
    /**
     * Log system error
     */
    public static function logError($context, $error)
    {
        Log::channel('certified_errors')->error('Certified Translation Error', [
            'context' => $context,
            'error' => $error,
            'timestamp' => now()->toDateTimeString(),
            'memory' => memory_get_usage(true),
        ]);
    }
    
    /**
     * Check system health
     */
    public static function checkHealth()
    {
        $health = [
            'status' => 'healthy',
            'checks' => [],
        ];
        
        // Check database
        try {
            \DB::connection()->getPdo();
            $health['checks']['database'] = 'OK';
        } catch (\Exception $e) {
            $health['checks']['database'] = 'FAIL';
            $health['status'] = 'unhealthy';
        }
        
        // Check storage
        $storageDir = storage_path('app/certified-documents');
        if (is_writable($storageDir)) {
            $health['checks']['storage'] = 'OK';
        } else {
            $health['checks']['storage'] = 'FAIL';
            $health['status'] = 'unhealthy';
        }
        
        // Check memory
        $memoryLimit = ini_get('memory_limit');
        $memoryUsage = memory_get_usage(true);
        $health['checks']['memory'] = [
            'limit' => $memoryLimit,
            'usage' => round($memoryUsage / 1024 / 1024, 2) . ' MB',
            'status' => 'OK'
        ];
        
        // Check disk space
        $freeSpace = disk_free_space(storage_path());
        $totalSpace = disk_total_space(storage_path());
        $usagePercent = (1 - $freeSpace / $totalSpace) * 100;
        
        $health['checks']['disk'] = [
            'free' => round($freeSpace / 1024 / 1024 / 1024, 2) . ' GB',
            'usage' => round($usagePercent, 1) . '%',
            'status' => $usagePercent > 90 ? 'WARNING' : 'OK'
        ];
        
        return $health;
    }
}
