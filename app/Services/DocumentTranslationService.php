<?php

namespace App\Services;

use OpenAI;
use Smalot\PdfParser\Parser as PdfParser;
use Mpdf\Mpdf;
use Exception;
use Illuminate\Support\Facades\Log;

class DocumentTranslationService
{
    protected $openai;
    protected $pdfParser;
    
    public function __construct()
    {
        $this->openai = OpenAI::client(config('services.openai.key'));
        $this->pdfParser = new PdfParser();
    }
    
    /**
     * Translate PDF document from source language to target language
     */
    public function translatePdf(string $sourcePath, string $targetPath, string $sourceLang, string $targetLang): bool
    {
        try {
            Log::info('Starting PDF translation', [
                'source' => $sourcePath,
                'target' => $targetPath,
                'from' => $sourceLang,
                'to' => $targetLang
            ]);
            
            // 1. Extract text from PDF
            $text = $this->extractTextFromPdf($sourcePath);
            
            if (empty($text)) {
                Log::warning('No text extracted from PDF, copying original');
                copy($sourcePath, $targetPath);
                return true;
            }
            
            // 2. Translate text using OpenAI
            $translatedText = $this->translateText($text, $sourceLang, $targetLang);
            
            // 3. Post-translation cleanup (NEW!)
            $translatedText = $this->cleanupTranslatedText($translatedText, $targetLang);
            
            // 4. Create new PDF with translated text
            $this->createTranslatedPdf($sourcePath, $targetPath, $translatedText, $targetLang);
            
            Log::info('PDF translation completed successfully');
            return true;
            
        } catch (Exception $e) {
            Log::error('PDF translation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Fallback: copy original if translation fails
            if (file_exists($sourcePath)) {
                copy($sourcePath, $targetPath);
            }
            
            return false;
        }
    }
    
    /**
     * Extract text from PDF
     */
    protected function extractTextFromPdf(string $pdfPath): string
    {
        try {
            $pdf = $this->pdfParser->parseFile($pdfPath);
            $text = $pdf->getText();

            // IMPORTANT:
            // Do NOT collapse all whitespace to a single space. That destroys layout
            // (tables, invoices, certificates) and results in "packed" unreadable output.
            // Instead, normalize line endings and trim only excessive trailing spaces.
            $text = str_replace(["\r\n", "\r"], "\n", $text);
            $text = preg_replace('/[ \t]+/m', ' ', $text);           // collapse spaces per-line
            $text = preg_replace('/\n{3,}/', "\n\n", $text);        // keep paragraph breaks
            $text = trim($text);

            return $text;
        } catch (Exception $e) {
            Log::error('Failed to extract text from PDF', ['error' => $e->getMessage()]);
            return '';
        }
    }
    
    /**
     * Translate text using OpenAI GPT-4 with improved prompt
     */
    protected function translateText(string $text, string $sourceLang, string $targetLang): string
    {
        try {
            // Split text into chunks if too long (max 4000 chars per chunk)
            $chunks = $this->splitTextIntoChunks($text, 4000);
            $translatedChunks = [];
            
            // Enhanced system prompt for better translation quality
            $systemPrompt = "You are a professional certified document translator specializing in official documents.

INSTRUCTIONS:
1. Translate from {$sourceLang} to {$targetLang}
2. Maintain document structure (tables, lists, sections)
3. Keep numbers, codes, IBANs, URLs, and dates in their original format
4. Use professional terminology appropriate for official documents
5. For Arabic: use formal Modern Standard Arabic (فصحى)
6. Preserve line breaks that indicate structure
7. Do NOT add explanations or notes
8. Output ONLY the translation

FORMATTING RULES:
- Keep table structures clear with proper spacing
- Maintain paragraph breaks
- Preserve bullet points and numbering
- Keep contact information organized
- Do not merge separate lines into one paragraph";
            
            foreach ($chunks as $chunk) {
                $response = $this->openai->chat()->create([
                    'model' => 'gpt-5',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $systemPrompt
                        ],
                        [
                            'role' => 'user',
                            'content' => $chunk
                        ]
                    ],
                    'temperature' => 0.3,
                ]);
                
                $translatedChunks[] = $response->choices[0]->message->content;
            }
            
            return implode("\n\n", $translatedChunks);
            
        } catch (Exception $e) {
            Log::error('OpenAI translation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    
    /**
     * Post-translation cleanup to improve quality (NEW!)
     */
    protected function cleanupTranslatedText(string $text, string $targetLang): string
    {
        try {
            // 1. Remove duplicate consecutive lines
            $lines = explode("\n", $text);
            $cleaned = [];
            $lastLine = null;
            
            foreach ($lines as $line) {
                $trimmed = trim($line);
                if ($trimmed !== $lastLine || empty($trimmed)) {
                    $cleaned[] = $line;
                }
                $lastLine = $trimmed;
            }
            
            $text = implode("\n", $cleaned);
            
            // 2. Fix excessive spacing
            $text = preg_replace('/\n{4,}/', "\n\n\n", $text);
            
            // 3. Improve readability for Arabic
            if (in_array(strtolower($targetLang), ['arabic', 'ar'])) {
                // Add proper spacing around colons
                $text = preg_replace('/(\S):(\S)/', '$1: $2', $text);
            }
            
            return $text;
            
        } catch (Exception $e) {
            Log::warning('Cleanup failed, returning original', ['error' => $e->getMessage()]);
            return $text;
        }
    }
    
    /**
     * Split text into chunks
     */
    protected function splitTextIntoChunks(string $text, int $maxLength): array
    {
        if (strlen($text) <= $maxLength) {
            return [$text];
        }
        
        $chunks = [];
        $sentences = preg_split('/(?<=[.!?])\s+/', $text);
        $currentChunk = '';
        
        foreach ($sentences as $sentence) {
            if (strlen($currentChunk . ' ' . $sentence) <= $maxLength) {
                $currentChunk .= ($currentChunk ? ' ' : '') . $sentence;
            } else {
                if ($currentChunk) {
                    $chunks[] = $currentChunk;
                }
                $currentChunk = $sentence;
            }
        }
        
        if ($currentChunk) {
            $chunks[] = $currentChunk;
        }
        
        return $chunks;
    }
    
    /**
     * Create PDF with translated text using mPDF (supports Arabic/UTF-8)
     * WITH RTL/LTR fixes for numbers and codes (NEW!)
     */
    protected function createTranslatedPdf(string $sourcePath, string $targetPath, string $translatedText, string $targetLang): void
    {
        try {
            // Ensure directory exists
            $dir = dirname($targetPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0775, true);
            }
            
            // Create mPDF instance with Arabic support
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'default_font' => 'dejavusans',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 16,
                'margin_bottom' => 16,
                'margin_header' => 9,
                'margin_footer' => 9,
                'orientation' => 'P',
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
            ]);

            // Directionality: RTL only when the *target* language is RTL.
            $rtlLanguages = ['arabic', 'ar', 'hebrew', 'he', 'persian', 'fa', 'urdu', 'ur'];
            $isRtl = in_array(mb_strtolower($targetLang, 'UTF-8'), $rtlLanguages, true);
            if ($isRtl) {
                $mpdf->SetDirectionality('rtl');
            }

            // Apply LTR wrapping for numbers, codes, and dates (NEW!)
            $translatedText = $this->wrapLTRContent($translatedText);

            // Preserve line breaks and spacing
            $safe = $translatedText; // Already has HTML entities from wrapLTRContent
            $html = <<<HTML
<div style="font-size: 11pt; line-height: 1.45; white-space: pre-wrap; font-family: DejaVu Sans, sans-serif;">
{$safe}
</div>
HTML;
            
            $mpdf->WriteHTML($html);
            $mpdf->Output($targetPath, 'F');
            
            Log::info('PDF created successfully with mPDF', ['path' => $targetPath]);
            
        } catch (Exception $e) {
            Log::error('Failed to create translated PDF with mPDF', ['error' => $e->getMessage()]);
            // Fallback: copy original
            copy($sourcePath, $targetPath);
        }
    }
    
    /**
     * Wrap numbers, codes, IBANs, URLs, and dates with LTR direction (NEW!)
     */
    protected function wrapLTRContent(string $text): string
    {
        // Escape HTML first
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        
        // 1. Wrap IBANs (e.g., NL34 ABNA 0243 2400 58)
        $text = preg_replace(
            '/\b([A-Z]{2}[0-9]{2}[A-Z0-9\s]+)\b/',
            '<span dir="ltr">$1</span>',
            $text
        );
        
        // 2. Wrap currency amounts (e.g., €1.386,12 or €493,12)
        $text = preg_replace(
            '/(€\s*[\d.,]+)/',
            '<span dir="ltr">$1</span>',
            $text
        );
        
        // 3. Wrap dates (DD-MM-YYYY format)
        $text = preg_replace(
            '/\b(\d{2}-\d{2}-\d{4})\b/',
            '<span dir="ltr">$1</span>',
            $text
        );
        
        // 4. Wrap dates (YYYY-MM-DD format)
        $text = preg_replace(
            '/\b(\d{4}-\d{2}-\d{2})\b/',
            '<span dir="ltr">$1</span>',
            $text
        );
        
        // 5. Wrap phone numbers (e.g., 036 - 20 31 900)
        $text = preg_replace(
            '/\b(\d{3}\s*-\s*\d{2}\s*\d{2}\s*\d{3})\b/',
            '<span dir="ltr">$1</span>',
            $text
        );
        
        // 6. Wrap URLs
        $text = preg_replace(
            '/(https?:\/\/[^\s]+|www\.[^\s]+)/',
            '<span dir="ltr">$1</span>',
            $text
        );
        
        // 7. Wrap email addresses
        $text = preg_replace(
            '/([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/',
            '<span dir="ltr">$1</span>',
            $text
        );
        
        // 8. Wrap reference numbers and codes (e.g., P022, 12100763)
        $text = preg_replace(
            '/\b([A-Z]\d{3,}|\d{6,})\b/',
            '<span dir="ltr">$1</span>',
            $text
        );
        
        // 9. Wrap postal codes (e.g., 7413 AA, 1314 CJ)
        $text = preg_replace(
            '/\b(\d{4}\s*[A-Z]{2})\b/',
            '<span dir="ltr">$1</span>',
            $text
        );
        
        return $text;
    }
    
    /**
     * Detect language of text
     */
    public function detectLanguage(string $text): string
    {
        try {
            $response = $this->openai->chat()->create([
                'model' => 'gpt-5',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Detect the language of the following text and respond with only the language name in English (e.g., "English", "Arabic", "French", etc.).'
                    ],
                    [
                        'role' => 'user',
                        'content' => substr($text, 0, 500) // Use first 500 chars for detection
                    ]
                ],
                'temperature' => 0.1,
            ]);
            
            return trim($response->choices[0]->message->content);
            
        } catch (Exception $e) {
            Log::error('Language detection failed', ['error' => $e->getMessage()]);
            return 'English'; // Default fallback
        }
    }
}
