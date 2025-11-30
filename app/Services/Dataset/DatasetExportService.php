<?php

namespace App\Services\Dataset;

use App\Models\CulturalMemory;
use Illuminate\Support\Facades\Storage;

class DatasetExportService
{
    public function exportJsonl(string $path = null): array
    {
        $path = $path ?: 'datasets/training_'.now()->format('Ymd_His').'.jsonl';
        $memories = CulturalMemory::query()->orderBy('id')->get();
        $count = 0;
        $lines = [];

        foreach ($memories as $m) {
            $record = [
                'source' => $m->source_text,
                'target' => $m->translated_text,
                'source_language' => $m->source_language,
                'target_language' => $m->target_language,
                'target_culture' => $m->target_culture,
                'brand_voice' => $m->brand_voice,
                'emotion' => $m->emotion,
                'tone' => $m->tone,
                'metadata' => $m->metadata,
            ];
            $jsonLine = json_encode($record, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $lines[] = $jsonLine;
            $count++;
        }
        Storage::disk('local')->put($path, implode("\n", $lines));
        return ['path' => $path, 'count' => $count];
    }
}
