<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use thiagoalessio\TesseractOCR\TesseractOCR;

class OcrService
{
    /**
     * Extract text from PDF or image file
     */
    public function extractText(string $filePath, string $language = 'eng'): array
    {
        try {
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            
            if (in_array(strtolower($extension), ['pdf'])) {
                return $this->extractFromPdf($filePath, $language);
            } elseif (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'tiff', 'bmp'])) {
                return $this->extractFromImage($filePath, $language);
            }
            
            throw new \Exception('Unsupported file format');
            
        } catch (\Exception $e) {
            Log::error('OCR Extraction Error', ['error' => $e->getMessage(), 'file' => $filePath]);
            throw $e;
        }
    }

    /**
     * Extract text from image using Tesseract OCR
     */
    protected function extractFromImage(string $imagePath, string $language): array
    {
        try {
            $ocr = new TesseractOCR($imagePath);
            $ocr->lang($language);
            
            // Set configuration for better accuracy
            $ocr->psm(3); // Fully automatic page segmentation
            $ocr->oem(3); // Use best OCR Engine Mode
            
            $text = $ocr->run();
            
            // Get confidence score
            $confidence = $this->calculateConfidence($text);
            
            return [
                'success' => true,
                'text' => $text,
                'confidence' => $confidence,
                'language' => $language,
                'method' => 'tesseract',
                'file_type' => 'image',
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'text' => '',
                'confidence' => 0,
            ];
        }
    }

    /**
     * Extract text from PDF by converting to images first
     */
    protected function extractFromPdf(string $pdfPath, string $language): array
    {
        try {
            // Convert PDF to images using Imagick
            $imagick = new \Imagick();
            $imagick->setResolution(300, 300); // High resolution for better OCR
            $imagick->readImage($pdfPath);
            
            $allText = '';
            $totalConfidence = 0;
            $pageCount = $imagick->getNumberImages();
            
            // Process each page
            foreach ($imagick as $pageNumber => $page) {
                $page->setImageFormat('png');
                $tempImage = tempnam(sys_get_temp_dir(), 'ocr_') . '.png';
                $page->writeImage($tempImage);
                
                $result = $this->extractFromImage($tempImage, $language);
                
                if ($result['success']) {
                    $allText .= $result['text'] . "\n\n";
                    $totalConfidence += $result['confidence'];
                }
                
                // Cleanup temp file
                @unlink($tempImage);
            }
            
            $averageConfidence = $pageCount > 0 ? $totalConfidence / $pageCount : 0;
            
            return [
                'success' => true,
                'text' => trim($allText),
                'confidence' => $averageConfidence,
                'language' => $language,
                'method' => 'tesseract',
                'file_type' => 'pdf',
                'pages' => $pageCount,
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'text' => '',
                'confidence' => 0,
            ];
        }
    }

    /**
     * Calculate confidence score based on text quality
     */
    protected function calculateConfidence(string $text): float
    {
        if (empty($text)) {
            return 0.0;
        }
        
        $confidence = 80.0; // Base confidence
        
        // Check for common OCR errors
        $specialChars = preg_match_all('/[^a-zA-Z0-9\s\.\,\!\?\-\(\)]/', $text);
        if ($specialChars > strlen($text) * 0.1) {
            $confidence -= 20;
        }
        
        // Check for reasonable word length
        $words = str_word_count($text);
        $avgWordLength = $words > 0 ? strlen($text) / $words : 0;
        
        if ($avgWordLength < 2 || $avgWordLength > 15) {
            $confidence -= 15;
        }
        
        return max(0, min(100, $confidence));
    }

    /**
     * Detect language from image/PDF
     */
    public function detectLanguage(string $filePath): string
    {
        // Try common languages
        $languages = ['eng', 'ara', 'fra', 'deu', 'spa', 'chi_sim'];
        $bestLanguage = 'eng';
        $bestConfidence = 0;
        
        foreach ($languages as $lang) {
            $result = $this->extractText($filePath, $lang);
            
            if ($result['success'] && $result['confidence'] > $bestConfidence) {
                $bestConfidence = $result['confidence'];
                $bestLanguage = $lang;
            }
        }
        
        return $bestLanguage;
    }

    /**
     * Clean OCR text (remove common artifacts)
     */
    public function cleanText(string $text): string
    {
        // Remove multiple spaces
        $text = preg_replace('/\s+/', ' ', $text);
        
        // Fix common OCR mistakes
        $replacements = [
            '0' => 'O', // When 0 should be O
            'l' => 'I', // When l should be I
            '5' => 'S', // When 5 should be S
        ];
        
        // Apply context-aware replacements
        // (This is simplified - real implementation would be more sophisticated)
        
        return trim($text);
    }

    /**
     * Extract structured data from document
     */
    public function extractStructuredData(string $filePath, string $documentType): array
    {
        $result = $this->extractText($filePath);
        
        if (!$result['success']) {
            return $result;
        }
        
        $text = $result['text'];
        $data = [];
        
        switch ($documentType) {
            case 'passport':
                $data = $this->extractPassportData($text);
                break;
            case 'id_card':
                $data = $this->extractIdCardData($text);
                break;
            case 'diploma':
                $data = $this->extractDiplomaData($text);
                break;
            default:
                $data = ['raw_text' => $text];
        }
        
        return array_merge($result, ['structured_data' => $data]);
    }

    /**
     * Extract passport-specific data
     */
    protected function extractPassportData(string $text): array
    {
        $data = [];
        
        // Extract passport number (format: A1234567)
        if (preg_match('/[A-Z]\d{7}/', $text, $matches)) {
            $data['passport_number'] = $matches[0];
        }
        
        // Extract date of birth (various formats)
        if (preg_match('/\d{2}[\/\-]\d{2}[\/\-]\d{4}/', $text, $matches)) {
            $data['date_of_birth'] = $matches[0];
        }
        
        // Extract names (simplified - real implementation would be more complex)
        $lines = explode("\n", $text);
        foreach ($lines as $line) {
            if (preg_match('/surname|family name/i', $line)) {
                $data['surname'] = trim(str_replace(['Surname', 'Family Name', ':', '-'], '', $line));
            }
            if (preg_match('/given name|first name/i', $line)) {
                $data['given_name'] = trim(str_replace(['Given Name', 'First Name', ':', '-'], '', $line));
            }
        }
        
        return $data;
    }

    /**
     * Extract ID card data
     */
    protected function extractIdCardData(string $text): array
    {
        return ['raw_text' => $text]; // Simplified
    }

    /**
     * Extract diploma data
     */
    protected function extractDiplomaData(string $text): array
    {
        return ['raw_text' => $text]; // Simplified
    }
}
