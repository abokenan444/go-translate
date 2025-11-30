<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Dataset\DatasetExportService;

class ExportTrainingDataset extends Command
{
    protected $signature = 'dataset:export {--path= : Custom output path relative to storage/app}';
    protected $description = 'Export cultural memories into a JSONL training dataset';

    public function handle(): int
    {
        $pathOpt = $this->option('path');
        $service = new DatasetExportService();
        $result = $service->exportJsonl($pathOpt ?: null);

        $this->info('Dataset exported: storage/app/'.$result['path'].' (records: '.$result['count'].')');
        return self::SUCCESS;
    }
}
