<?php

namespace App\Jobs;

use App\Services\MTE\MultimodalTranslationEngine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ProcessPdfTranslation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $jobId;
    public string $pdfPath;
    public string $targetLanguage;
    public array $options;

    public function __construct(string $jobId, string $pdfPath, string $targetLanguage, array $options = [])
    {
        $this->jobId = $jobId;
        $this->pdfPath = $pdfPath;
        $this->targetLanguage = $targetLanguage;
        $this->options = $options;
    }

    public function handle(MultimodalTranslationEngine $mte): void
    {
        Cache::put("pdf_trans_job:{$this->jobId}", [
            'status' => 'processing',
            'started_at' => now()->toIso8601String(),
            'progress' => [ 'current' => 0, 'total' => null, 'translated_chars_total' => 0 ],
        ], now()->addHours(1));

        try {
            $res = $mte->translatePDFInChunks($this->pdfPath, $this->targetLanguage, $this->options, function($current, $total, $meta) {
                Cache::put("pdf_trans_job:{$this->jobId}", [
                    'status' => 'processing',
                    'progress' => [
                        'current' => $current,
                        'total' => $total,
                        'translated_chars_total' => $meta['translated_chars_total'] ?? 0,
                        'last_chunk' => $meta['last_chunk'] ?? null,
                    ],
                    'updated_at' => now()->toIso8601String(),
                ], now()->addHours(1));
            });
            Cache::put("pdf_trans_job:{$this->jobId}", [
                'status' => ($res['success'] ?? false) ? 'completed' : 'failed',
                'result' => $res,
                'finished_at' => now()->toIso8601String(),
            ], now()->addHours(1));
        } catch (\Throwable $e) {
            Cache::put("pdf_trans_job:{$this->jobId}", [
                'status' => 'failed',
                'error' => $e->getMessage(),
                'finished_at' => now()->toIso8601String(),
            ], now()->addHours(1));
        }
    }
}
