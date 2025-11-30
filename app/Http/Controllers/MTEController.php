<?php

namespace App\Http\Controllers;

use App\Services\MTE\MultimodalTranslationEngine;
use App\Services\MTE\AsrService;
use App\Services\MTE\TtsService;
use App\Services\Governance\GovernanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Jobs\ProcessPdfTranslation;

class MTEController extends Controller
{
    public function __construct(
        protected MultimodalTranslationEngine $mte,
        protected GovernanceService $gov,
        protected AsrService $asr,
        protected TtsService $tts
    ) {}

    public function translateText(Request $request)
    {
        $data = $request->validate([
            'text' => 'required|string',
            'target_language' => 'required|string',
            'source_language' => 'nullable|string',
            'target_culture' => 'nullable|string',
            'smart_correct' => 'boolean',
            'apply_glossary' => 'nullable|boolean',
        ]);
        $start = microtime(true);
        $result = $this->mte->translateText($data['text'], $data['target_language'], [
            'source_language' => $data['source_language'] ?? 'auto',
            'target_culture' => $data['target_culture'] ?? null,
            'smart_correct' => (bool)($data['smart_correct'] ?? false),
            'apply_glossary' => array_key_exists('apply_glossary', $data) ? (bool)$data['apply_glossary'] : true,
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
        ]);
        $elapsedMs = (microtime(true) - $start) * 1000;
        \App\Services\Monitoring\MonitoringService::increment('translation_requests_total');
        \App\Services\Monitoring\MonitoringService::observeLatency('translation_latency', $elapsedMs);

        $this->gov->log(\Illuminate\Support\Facades\Auth::id(), 'translation_text', 'translation', 0, [
            'target_language' => $data['target_language'],
            'smart_correct' => (bool)($data['smart_correct'] ?? false),
        ]);
        $ok = (bool)($result['success'] ?? true);
        $payload = ['success' => $ok, 'data' => $result];
        if (!$ok && !empty($result['error'])) $payload['error'] = $result['error'];
        return response()->json($payload, $ok ? 200 : 422);
    }

    public function translateImage(Request $request)
    {
        $data = $request->validate([
            'image' => 'required|file|mimes:jpg,jpeg,png,webp',
            'target_language' => 'required_unless:extract_only,true,1|string',
            'source_language' => 'nullable|string',
            'target_culture' => 'nullable|string',
            'extract_only' => 'nullable|boolean',
            'apply_glossary' => 'nullable|boolean',
        ]);

        $path = $request->file('image')->store('uploads/mte', 'local');
        $result = $this->mte->translateImage(Storage::disk('local')->path($path), $data['target_language'] ?? '', [
            'source_language' => $data['source_language'] ?? 'auto',
            'target_culture' => $data['target_culture'] ?? null,
            'extract_only' => (bool)($data['extract_only'] ?? false),
            'apply_glossary' => array_key_exists('apply_glossary', $data) ? (bool)$data['apply_glossary'] : true,
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
        ]);

        $this->gov->log(\Illuminate\Support\Facades\Auth::id(), 'translation_image_stub', 'mte_image', 0, [
            'target_language' => $data['target_language'],
            'file' => $path,
        ]);
        $ok = (bool)($result['success'] ?? true);
        $payload = ['success' => $ok, 'data' => $result];
        if (!$ok && !empty($result['error'])) $payload['error'] = $result['error'];
        return response()->json($payload, $ok ? 200 : 422);
    }

    public function translatePDF(Request $request)
    {
        $data = $request->validate([
            'pdf' => 'required|file|mimes:pdf',
            'target_language' => 'required_unless:extract_only,true,1|string',
            'source_language' => 'nullable|string',
            'target_culture' => 'nullable|string',
            'extract_only' => 'nullable|boolean',
            'apply_glossary' => 'nullable|boolean',
        ]);

        $path = $request->file('pdf')->store('uploads/mte', 'local');
        $result = $this->mte->translatePDF(Storage::disk('local')->path($path), $data['target_language'] ?? '', [
            'source_language' => $data['source_language'] ?? 'auto',
            'target_culture' => $data['target_culture'] ?? null,
            'extract_only' => (bool)($data['extract_only'] ?? false),
            'apply_glossary' => array_key_exists('apply_glossary', $data) ? (bool)$data['apply_glossary'] : true,
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
        ]);

        $this->gov->log(\Illuminate\Support\Facades\Auth::id(), 'translation_pdf_stub', 'mte_pdf', 0, [
            'target_language' => $data['target_language'],
            'file' => $path,
        ]);
        $ok = (bool)($result['success'] ?? true);
        $payload = ['success' => $ok, 'data' => $result];
        if (!$ok && !empty($result['error'])) $payload['error'] = $result['error'];
        return response()->json($payload, $ok ? 200 : 422);
    }

    public function enqueuePDF(Request $request)
    {
        $data = $request->validate([
            'pdf' => 'required|file|mimes:pdf',
            'target_language' => 'required_unless:extract_only,true,1|string',
            'source_language' => 'nullable|string',
            'target_culture' => 'nullable|string',
            'extract_only' => 'nullable|boolean',
            'apply_glossary' => 'nullable|boolean',
        ]);

        $path = $request->file('pdf')->store('uploads/mte', 'local');
        $jobId = (string) Str::uuid();
        Cache::put("pdf_trans_job:$jobId", [
            'status' => 'queued',
            'created_at' => now()->toIso8601String(),
        ], now()->addHours(1));

        ProcessPdfTranslation::dispatch(
            $jobId,
            Storage::disk('local')->path($path),
            $data['target_language'] ?? '',
            [
                'source_language' => $data['source_language'] ?? 'auto',
                'target_culture' => $data['target_culture'] ?? null,
                'extract_only' => (bool)($data['extract_only'] ?? false),
                'apply_glossary' => array_key_exists('apply_glossary', $data) ? (bool)$data['apply_glossary'] : true,
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
            ]
        );

        return response()->json(['success' => true, 'job_id' => $jobId]);
    }

    public function pdfStatus(string $jobId)
    {
        $state = Cache::get("pdf_trans_job:$jobId");
        if (!$state) {
            return response()->json(['success' => false, 'error' => 'Job not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $state]);
    }

    public function transcribeAudio(Request $request)
    {
        $data = $request->validate([
            'audio' => 'required|file|mimes:wav,mp3,m4a,ogg,flac',
            'source_language' => 'nullable|string',
        ]);
        
        $path = $request->file('audio')->store('uploads/mte', 'local');
        $fullPath = Storage::disk('local')->path($path);
        $start = microtime(true);
        $result = $this->asr->transcribe($fullPath, $data['source_language'] ?? 'auto');
        \App\Services\Monitoring\MonitoringService::increment('asr_requests_total');
        $elapsedMs = (microtime(true) - $start) * 1000;
        \App\Services\Monitoring\MonitoringService::observeLatency('asr_latency', $elapsedMs);
        
        $this->gov->log(\Illuminate\Support\Facades\Auth::id(), 'asr_transcription', 'audio', 0, [
            'success' => $result['success'] ?? false,
            'language' => $result['language'] ?? null,
        ]);
        
        if (!($result['success'] ?? false)) {
            return response()->json(['success' => false, 'error' => $result['error'] ?? 'Transcription failed'], 500);
        }
        
        return response()->json(['success' => true, 'data' => $result]);
    }

    public function synthesizeSpeech(Request $request)
    {
        $data = $request->validate([
            'text' => 'required|string',
            'language' => 'nullable|string',
            'voice' => 'nullable|string',
        ]);
        $start = microtime(true);
        $result = $this->tts->synthesize(
            $data['text'],
            $data['language'] ?? 'en',
            $data['voice'] ?? null
        );
        $elapsedMs = (microtime(true) - $start) * 1000;
        \App\Services\Monitoring\MonitoringService::increment('tts_requests_total');
        \App\Services\Monitoring\MonitoringService::observeLatency('tts_latency', $elapsedMs);
        
        $this->gov->log(\Illuminate\Support\Facades\Auth::id(), 'tts_synthesis', 'audio', 0, [
            'success' => $result['success'] ?? false,
            'language' => $data['language'] ?? 'en',
        ]);
        
        if (!($result['success'] ?? false)) {
            return response()->json(['success' => false, 'error' => $result['error'] ?? 'Synthesis failed'], 500);
        }
        
        return response()->json(['success' => true, 'data' => $result]);
    }
}
