<?php

namespace App\Services\MTE;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Services\Monitoring\MonitoringService;

class TtsService
{
    public function synthesize(string $text, string $language = 'en', ?string $voice = null): array
    {
        $provider = config('services.tts.provider', 'azure');
        $start = microtime(true);
        
        $result = match($provider) {
            'azure' => $this->azureSynthesize($text, $language, $voice),
            'google' => $this->googleSynthesize($text, $language, $voice),
            'elevenlabs' => $this->elevenLabsSynthesize($text, $language, $voice),
            default => ['success' => false, 'error' => 'TTS provider not configured']
        };
        
        $latency = (microtime(true) - $start) * 1000;
        MonitoringService::increment("tts_requests_total_{$provider}");
        MonitoringService::observeLatency("tts_latency_{$provider}", $latency);
        
        return $result;
    }

    protected function azureSynthesize(string $text, string $language, ?string $voice): array
    {
        $key = config('services.tts.azure.key');
        $region = config('services.tts.azure.region');
        
        if (!$key || !$region) {
            return ['success' => false, 'error' => 'Azure TTS credentials not configured'];
        }

        $voiceMap = [
            'ar' => 'ar-SA-ZariyahNeural',
            'en' => 'en-US-JennyNeural',
            'es' => 'es-ES-ElviraNeural',
            'fr' => 'fr-FR-DeniseNeural',
            'de' => 'de-DE-KatjaNeural',
        ];
        
        $selectedVoice = $voice ?? ($voiceMap[$language] ?? 'en-US-JennyNeural');
        $endpoint = "https://{$region}.tts.speech.microsoft.com/cognitiveservices/v1";

        $ssml = <<<XML
<speak version='1.0' xml:lang='en-US'>
    <voice name='{$selectedVoice}'>
        {$text}
    </voice>
</speak>
XML;

        try {
            $response = Http::withHeaders([
                'Ocp-Apim-Subscription-Key' => $key,
                'Content-Type' => 'application/ssml+xml',
                'X-Microsoft-OutputFormat' => 'audio-16khz-128kbitrate-mono-mp3',
            ])->send('POST', $endpoint, ['body' => $ssml]);

            if ($response->successful()) {
                $filename = 'tts_' . uniqid() . '.mp3';
                Storage::disk('local')->put("tts/{$filename}", $response->body());
                
                return [
                    'success' => true,
                    'audio_path' => Storage::disk('local')->path("tts/{$filename}"),
                    'audio_url' => url("/storage/tts/{$filename}"),
                ];
            }

            return ['success' => false, 'error' => 'Azure TTS failed: ' . $response->status()];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    protected function googleSynthesize(string $text, string $language, ?string $voice): array
    {
        $apiKey = config('services.tts.google.key');
        if (!$apiKey) {
            return ['success' => false, 'error' => 'Google TTS API key not configured'];
        }

        $langCode = $language === 'ar' ? 'ar-XA' : ($language === 'en' ? 'en-US' : $language);
        $voiceName = $voice ?? "{$langCode}-Standard-A";

        try {
            $response = Http::post("https://texttospeech.googleapis.com/v1/text:synthesize?key={$apiKey}", [
                'input' => ['text' => $text],
                'voice' => [
                    'languageCode' => $langCode,
                    'name' => $voiceName,
                ],
                'audioConfig' => ['audioEncoding' => 'MP3'],
            ]);

            $data = $response->json();
            if ($response->successful() && isset($data['audioContent'])) {
                $filename = 'tts_' . uniqid() . '.mp3';
                Storage::disk('local')->put("tts/{$filename}", base64_decode($data['audioContent']));
                
                return [
                    'success' => true,
                    'audio_path' => Storage::disk('local')->path("tts/{$filename}"),
                    'audio_url' => url("/storage/tts/{$filename}"),
                ];
            }

            return ['success' => false, 'error' => $data['error']['message'] ?? 'Google TTS error'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    protected function elevenLabsSynthesize(string $text, string $language, ?string $voice): array
    {
        $apiKey = config('services.tts.elevenlabs.key');
        $voiceId = $voice ?? config('services.tts.elevenlabs.default_voice', '21m00Tcm4TlvDq8ikWAM');
        
        if (!$apiKey) {
            return ['success' => false, 'error' => 'ElevenLabs API key not configured'];
        }

        try {
            $response = Http::withHeaders([
                'xi-api-key' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post("https://api.elevenlabs.io/v1/text-to-speech/{$voiceId}", [
                'text' => $text,
                'model_id' => 'eleven_multilingual_v2',
            ]);

            if ($response->successful()) {
                $filename = 'tts_' . uniqid() . '.mp3';
                Storage::disk('local')->put("tts/{$filename}", $response->body());
                
                return [
                    'success' => true,
                    'audio_path' => Storage::disk('local')->path("tts/{$filename}"),
                    'audio_url' => url("/storage/tts/{$filename}"),
                ];
            }

            return ['success' => false, 'error' => 'ElevenLabs TTS failed: ' . $response->status()];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
