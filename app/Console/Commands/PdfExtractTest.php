<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MTE\MultimodalTranslationEngine;

class PdfExtractTest extends Command
{
    protected $signature = 'pdf:extract {path : Absolute path to a PDF file}';

    protected $description = 'Extract text from a PDF using configured tools or Smalot parser, for verification.';

    public function handle(MultimodalTranslationEngine $mte)
    {
        $path = $this->argument('path');
        if (!is_file($path)) {
            $this->error('File not found: ' . $path);
            return 1;
        }
        $this->info('Extracting text from: ' . $path);
        $res = $mte->translatePDF($path, 'en', ['extract_only' => true]);
        if (empty($res['success'])) {
            $this->error($res['error'] ?? 'Extraction failed');
            return 2;
        }
        $len = $res['extracted_text_length'] ?? mb_strlen($res['extracted_text'] ?? '');
        $this->info('Extraction OK. Length: ' . $len);
        $snippet = mb_substr($res['extracted_text'] ?? '', 0, 400);
        $this->line('--- SNIPPET ---');
        $this->line($snippet);
        $this->line('--- END ---');
        return 0;
    }
}
