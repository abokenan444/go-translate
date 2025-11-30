<?php

namespace App\Services\Monitoring;

use Illuminate\Support\Facades\Cache;

class MonitoringService
{
    protected static function key(string $name): string
    {
        return 'metrics:'.$name;
    }

    public static function increment(string $metric, int $value = 1): void
    {
        Cache::increment(self::key($metric), $value);
    }

    public static function observeLatency(string $metric, float $ms): void
    {
        // store rolling average + count
        $countKey = self::key($metric.':count');
        $avgKey = self::key($metric.':avg');
        $count = Cache::increment($countKey, 1);
        $prevAvg = Cache::get($avgKey, 0);
        $newAvg = $prevAvg + (($ms - $prevAvg) / $count);
        Cache::put($avgKey, $newAvg, 3600);
    }

    public static function export(): string
    {
        $lines = [
            '# HELP sessions_total Total realtime sessions created',
            '# TYPE sessions_total counter',
            'sessions_total '.Cache::get(self::key('sessions_total'), 0),
            '',
            '# HELP asr_requests_total Total ASR transcription requests by provider',
            '# TYPE asr_requests_total counter',
        ];
        
        // ASR provider-level counters
        foreach (['whisper', 'azure', 'deepgram'] as $provider) {
            $count = Cache::get(self::key("asr_requests_total_{$provider}"), 0);
            if ($count > 0) {
                $lines[] = "asr_requests_total{provider=\"{$provider}\"} {$count}";
            }
        }
        
        $lines[] = '';
        $lines[] = '# HELP asr_latency_avg_ms Average ASR processing latency in ms by provider';
        $lines[] = '# TYPE asr_latency_avg_ms gauge';
        
        foreach (['whisper', 'azure', 'deepgram'] as $provider) {
            $latency = Cache::get(self::key("asr_latency_{$provider}:avg"), 0);
            if ($latency > 0) {
                $lines[] = "asr_latency_avg_ms{provider=\"{$provider}\"} ".round($latency, 2);
            }
        }
        
        $lines[] = '';
        $lines[] = '# HELP tts_requests_total Total TTS synthesis requests by provider';
        $lines[] = '# TYPE tts_requests_total counter';
        
        // TTS provider-level counters
        foreach (['azure', 'google', 'elevenlabs'] as $provider) {
            $count = Cache::get(self::key("tts_requests_total_{$provider}"), 0);
            if ($count > 0) {
                $lines[] = "tts_requests_total{provider=\"{$provider}\"} {$count}";
            }
        }
        
        $lines[] = '';
        $lines[] = '# HELP tts_latency_avg_ms Average TTS processing latency in ms by provider';
        $lines[] = '# TYPE tts_latency_avg_ms gauge';
        
        foreach (['azure', 'google', 'elevenlabs'] as $provider) {
            $latency = Cache::get(self::key("tts_latency_{$provider}:avg"), 0);
            if ($latency > 0) {
                $lines[] = "tts_latency_avg_ms{provider=\"{$provider}\"} ".round($latency, 2);
            }
        }
        
        $lines[] = '';
        $lines[] = '# HELP translation_requests_total Total translation requests';
        $lines[] = '# TYPE translation_requests_total counter';
        $lines[] = 'translation_requests_total '.Cache::get(self::key('translation_requests_total'), 0);
        
        $lines[] = '';
        $lines[] = '# HELP translation_latency_avg_ms Average translation latency in ms';
        $lines[] = '# TYPE translation_latency_avg_ms gauge';
        $lines[] = 'translation_latency_avg_ms '.round(Cache::get(self::key('translation_latency:avg'), 0), 2);
        
        return implode("\n", $lines)."\n";
    }
}
