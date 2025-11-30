<?php

namespace App\Services\MTE;

use App\Services\AdvancedTranslationService;
use App\Services\KBM\KnowledgeBase;
use App\Models\GlossaryTerm;

class MultimodalTranslationEngine
{
    public function __construct(
        protected AdvancedTranslationService $translator,
        protected KnowledgeBase $kb
    ) {}

    public function translateText(string $text, string $targetLanguage, array $options = []): array
    {
        $result = $this->translator->translate([
            'text' => $text,
            'source_language' => $options['source_language'] ?? 'auto',
            'target_language' => $targetLanguage,
            'target_culture' => $options['target_culture'] ?? null,
            'smart_correct' => $options['smart_correct'] ?? false,
            'tone' => $options['tone'] ?? null,
            'industry' => $options['industry'] ?? null,
            'task_type' => $options['task_type'] ?? null,
            'context' => $options['context'] ?? null,
        ]);

        // Apply glossary to translated text (post-translation normalization)
        $applyGlossary = array_key_exists('apply_glossary', $options) ? (bool)$options['apply_glossary'] : true;
        $userId = $options['user_id'] ?? null;
        if ($applyGlossary && !empty($result['translated_text'])) {
            [$glossText, $matchCount] = $this->applyGlossary($result['translated_text'], $targetLanguage, $userId);
            $result['translated_text'] = $glossText;
            $result['glossary_matches'] = $matchCount;
            $result['glossary_applied'] = true;
        }

        // Store in cultural memory
        $this->kb->storeMemory([
            'user_id' => $options['user_id'] ?? null,
            'source_language' => $options['source_language'] ?? null,
            'target_language' => $targetLanguage,
            'target_culture' => $options['target_culture'] ?? null,
            'source_text' => $text,
            'translated_text' => $result['translated_text'] ?? ($result['translation'] ?? ''),
            'brand_voice' => $options['brand_voice'] ?? null,
            'emotion' => $options['emotion'] ?? null,
            'tone' => $options['tone'] ?? null,
            'metadata' => [
                'quality_score' => $result['quality_score'] ?? null,
                'corrected_text' => $result['corrected_text'] ?? null,
            ],
        ]);

        return $result;
    }

    public function translateImage($imagePathOrFile, string $targetLanguage, array $options = []): array
    {
        $ocrLang = $options['ocr_language'] ?? ($options['source_language'] ?? 'eng');
        $imagePath = is_string($imagePathOrFile) ? $imagePathOrFile : (string) $imagePathOrFile;
        $extracted = $this->extractTextFromImage($imagePath, $ocrLang);
        if (!$extracted) {
            return [
                'success' => false,
                'error' => 'OCR not configured or failed. Set TESSERACT_PATH or configure OCR provider.',
            ];
        }
        if (!empty($options['extract_only'])) {
            return [
                'success' => true,
                'extracted_text' => $extracted,
            ];
        }
        $result = $this->translateText($extracted, $targetLanguage, $options);
        return $result + ['extracted_text' => $extracted];
    }

    public function translatePDF($pdfPathOrFile, string $targetLanguage, array $options = []): array
    {
        $pdfPath = is_string($pdfPathOrFile) ? $pdfPathOrFile : (string) $pdfPathOrFile;
        $text = $this->extractTextFromPdf($pdfPath);
        if (!$text) {
            return [
                'success' => false,
                'error' => 'PDF text extraction unavailable. Set PDFTOTEXT_PATH or install a PHP PDF parser.',
            ];
        }
        if (!empty($options['extract_only'])) {
            return [
                'success' => true,
                'extracted_text' => $text,
                'extracted_text_length' => mb_strlen($text),
            ];
        }
        // Chunk long text to avoid huge payloads/timeouts
        return $this->translatePDFInChunks($pdfPath, $targetLanguage, $options);
    }

    protected function extractTextFromImage(string $imagePath, string $lang = 'eng'): ?string
    {
        $tesseract = env('TESSERACT_PATH');
        if ($tesseract && file_exists($tesseract)) {
            $outPath = $imagePath . '.txt';
            $cmd = '"' . $tesseract . '" ' . escapeshellarg($imagePath) . ' ' . escapeshellarg($imagePath) . ' -l ' . escapeshellarg($lang) . ' 2>NUL';
            @shell_exec($cmd);
            if (file_exists($outPath)) {
                $txt = @file_get_contents($outPath);
                if ($txt && trim($txt) !== '') return trim($txt);
            }
        }
        // Future: add cloud OCR provider call here
        return null;
    }

    protected function extractTextFromPdf(string $pdfPath): ?string
    {
        $pdftotext = env('PDFTOTEXT_PATH');
        if ($pdftotext && file_exists($pdftotext)) {
            $tmp = $pdfPath . '.txt';
            $cmd = '"' . $pdftotext . '" -layout ' . escapeshellarg($pdfPath) . ' ' . escapeshellarg($tmp) . ' 2>NUL';
            @shell_exec($cmd);
            if (file_exists($tmp)) {
                $txt = @file_get_contents($tmp);
                if ($txt && trim($txt) !== '') return trim($txt);
            }
        }
        // If a PHP PDF parser is present via composer, try it
        if (class_exists('Smalot\\PdfParser\\Parser')) {
            try {
                $parserClass = 'Smalot\\PdfParser\\Parser';
                $parser = new $parserClass();
                $pdf = $parser->parseFile($pdfPath);
                $text = $pdf->getText();
                return $text ? trim($text) : null;
            } catch (\Throwable $e) {
            }
        }
        return null;
    }

    public function translateVideo($videoPathOrFile, string $targetLanguage, array $options = []): array
    {
        return ['status' => 'todo', 'message' => 'Video translation not yet implemented'];
    }

    public function translateSpeech($audioPathOrFile, string $targetLanguage, array $options = []): array
    {
        return ['status' => 'todo', 'message' => 'Speech pipeline not yet implemented'];
    }

    public function translatePDFInChunks($pdfPathOrFile, string $targetLanguage, array $options = [], callable $onProgress = null): array
    {
        $pdfPath = is_string($pdfPathOrFile) ? $pdfPathOrFile : (string) $pdfPathOrFile;
        $text = $this->extractTextFromPdf($pdfPath);
        if (!$text) {
            return [
                'success' => false,
                'error' => 'PDF text extraction unavailable. Set PDFTOTEXT_PATH or install a PHP PDF parser.',
            ];
        }
        if (!empty($options['extract_only'])) {
            return [
                'success' => true,
                'extracted_text' => $text,
                'extracted_text_length' => mb_strlen($text),
            ];
        }
        $totalLen = mb_strlen($text);
        $maxChunk = 6000;
        $maxChunks = 10;
        $chunks = $this->chunkByWords($text, $maxChunk, $maxChunks);
        $total = count($chunks);
        $translatedAll = '';
        $totalTranslatedChars = 0;
        $chunkResults = [];
        foreach ($chunks as $i => $chunkText) {
            $res = $this->translateText($chunkText, $targetLanguage, $options);
            $part = $res['translated_text'] ?? ($res['translation'] ?? '');
            $translatedAll .= ($translatedAll !== '' ? "\n\n" : '') . $part;
            $chars = mb_strlen($chunkText);
            $totalTranslatedChars += $chars;
            $meta = [
                'index' => $i,
                'source_chars' => $chars,
                'quality_score' => $res['quality_score'] ?? null,
            ];
            $chunkResults[] = $meta;
            if (is_callable($onProgress)) {
                $onProgress($i + 1, $total, [
                    'translated_chars_total' => $totalTranslatedChars,
                    'last_chunk' => $meta,
                ]);
            }
        }
        return [
            'success' => true,
            'translated_text' => $translatedAll,
            'extracted_text_length' => $totalLen,
            'chunks' => $total,
            'total_translated_chars' => $totalTranslatedChars,
            'chunk_results' => $chunkResults,
        ];
    }

    protected function applyGlossary(string $text, string $language, $userId = null): array
    {
        $matches = 0;
        try {
            $query = GlossaryTerm::query()->where('language', $language);
            if ($userId) $query->where('user_id', $userId);
            $terms = $query->whereNotNull('preferred')->where('preferred', '!=', '')->get(['term','preferred','forbidden']);
            foreach ($terms as $t) {
                $term = (string) $t->term;
                $pref = (string) $t->preferred;
                if ($term === '' || $pref === '') continue;
                $before = $text;
                $text = str_ireplace($term, $pref, $text, $count);
                if (!empty($count)) $matches += (int) $count;
            }
        } catch (\Throwable $e) {
            // No-op on failure
        }
        return [$text, $matches];
    }

    protected function chunkByWords(string $text, int $maxChunk, int $maxChunks): array
    {
        $chunks = [];
        $offset = 0;
        $len = mb_strlen($text);
        for ($i = 0; $i < $maxChunks && $offset < $len; $i++) {
            $remaining = mb_substr($text, $offset);
            if (mb_strlen($remaining) <= $maxChunk) {
                $chunks[] = $remaining;
                break;
            }
            $slice = mb_substr($remaining, 0, $maxChunk);
            // try to break on a whitespace/newline for cleaner splits
            $breakPos = max(mb_strrpos($slice, "\n"), mb_strrpos($slice, ' '));
            if ($breakPos === false) $breakPos = $maxChunk;
            $chunks[] = mb_substr($remaining, 0, $breakPos);
            $offset += $breakPos;
        }
        return $chunks;
    }
}
