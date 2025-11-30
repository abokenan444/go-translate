<?php

namespace App\Services\MTE;

use Illuminate\Support\Facades\Http;
use App\Services\Monitoring\MonitoringService;

class AsrService
{
    public function transcribe(string $audioPath, string $language = 'auto'): array
    {
        $provider = config('services.asr.provider', 'whisper');
        $start = microtime(true);
        
        $result = match($provider) {
            'whisper' => $this->whisperTranscribe($audioPath, $language),
            'azure' => $this->azureTranscribe($audioPath, $language),
            'deepgram' => $this->deepgramTranscribe($audioPath, $language),
            default => ['success' => false, 'error' => 'ASR provider not configured']
        };
        
        $latency = (microtime(true) - $start) * 1000;
        MonitoringService::increment("asr_requests_total_{$provider}");
        MonitoringService::observeLatency("asr_latency_{$provider}", $latency);
        
        return $result;
    }

    protected function whisperTranscribe(string $audioPath, string $language): array
    {
        $whisperPath = env('WHISPER_CLI_PATH');
        if (!$whisperPath || !file_exists($whisperPath)) {
            return ['success' => false, 'error' => 'Whisper CLI not found. Set WHISPER_CLI_PATH'];
        }

        $lang = $language === 'auto' ? '' : '--language ' . escapeshellarg($language);
        $outputDir = dirname($audioPath);
        $cmd = sprintf('"%s" "%s" %s --output_dir "%s" --output_format json 2>&1',
            $whisperPath, $audioPath, $lang, $outputDir);
        
        $output = shell_exec($cmd);
        $jsonPath = $audioPath . '.json';
        
        if (file_exists($jsonPath)) {
            $data = json_decode(file_get_contents($jsonPath), true);
            @unlink($jsonPath);
            return [
                'success' => true,
                'text' => $data['text'] ?? '',
                'language' => $data['language'] ?? $language,
                'segments' => $data['segments'] ?? [],
            ];
        }

        return ['success' => false, 'error' => 'Whisper transcription failed', 'output' => $output];
    }

    protected function azureTranscribe(string $audioPath, string $language): array
    {
        $key = config('services.asr.azure.key');
        $region = config('services.asr.azure.region');
        $endpoint = "https://{$region}.api.cognitive.microsoft.com/speechtotext/v3.0/transcriptions";

        if (!$key || !$region) {
            return ['success' => false, 'error' => 'Azure Speech credentials not configured'];
        }

        // Azure requires uploading to blob or using SDK; simplified stub
        return ['success' => false, 'error' => 'Azure ASR requires SDK integration (not CLI-ready)'];
    }

    protected function deepgramTranscribe(string $audioPath, string $language): array
    {
        $apiKey = config('services.asr.deepgram.key');
        if (!$apiKey) {
            return ['success' => false, 'error' => 'Deepgram API key not configured'];
        }

        $audioData = file_get_contents($audioPath);
        $lang = $language === 'auto' ? 'en' : $language;

        try {
            $response = Http::withHeaders([
                'Authorization' => "Token {$apiKey}",
                'Content-Type' => 'audio/wav',
            ])->send('POST', "https://api.deepgram.com/v1/listen?language={$lang}", [
                'body' => $audioData
            ]);

            $data = $response->json();
            if ($response->successful() && isset($data['results']['channels'][0]['alternatives'][0]['transcript'])) {
                return [
                    'success' => true,
                    'text' => $data['results']['channels'][0]['alternatives'][0]['transcript'],
                    'language' => $lang,
                ];
            }

            return ['success' => false, 'error' => $data['err_msg'] ?? 'Deepgram error'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
