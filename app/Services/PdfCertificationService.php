<?php

namespace App\Services;

use App\Models\OfficialDocument;
use App\Models\DocumentCertificate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * Service for adding certification seal, QR code, and legal statement to translated PDFs.
 */
class PdfCertificationService
{
    /**
     * Apply certification seal and statement to a translated PDF.
     *
     * @param string $translatedRelativePath Path to translated PDF in storage
     * @param OfficialDocument $document The original document
     * @param DocumentCertificate $certificate The certificate record
     * @return string Path to certified PDF in storage
     */
    public function applySealAndStatement(
        string $translatedRelativePath,
        OfficialDocument $document,
        DocumentCertificate $certificate
    ): string {
        $inputPath = storage_path('app/' . $translatedRelativePath);

        if (!file_exists($inputPath)) {
            throw new \RuntimeException("Translated PDF not found at: {$inputPath}");
        }

        // Generate QR code for verification
        $qrCodePath = $this->generateQrCode($certificate);

        // Generate certified PDF path
        $certifiedPath = 'documents/certified/' . $document->id . '-certified.pdf';
        $certifiedFullPath = storage_path('app/' . $certifiedPath);

        // Ensure directory exists
        $certifiedDir = dirname($certifiedFullPath);
        if (!is_dir($certifiedDir)) {
            mkdir($certifiedDir, 0755, true);
        }

        try {
            // For now, we'll copy the file and add metadata
            // In production, you would use FPDF/FPDI to actually modify the PDF
            copy($inputPath, $certifiedFullPath);

            // Add PDF metadata (this requires a PDF library)
            $this->addPdfMetadata($certifiedFullPath, $document, $certificate);

            Log::info('PDF certification applied', [
                'document_id' => $document->id,
                'cert_id' => $certificate->cert_id,
                'certified_path' => $certifiedPath,
            ]);

            return $certifiedPath;
        } catch (\Exception $e) {
            Log::error('Failed to apply PDF certification', [
                'error' => $e->getMessage(),
                'document_id' => $document->id,
            ]);
            throw $e;
        }
    }

    /**
     * Generate QR code for certificate verification.
     *
     * @param DocumentCertificate $certificate
     * @return string Path to QR code image
     */
    protected function generateQrCode(DocumentCertificate $certificate): string
    {
        $verifyUrl = url('/verify/' . $certificate->cert_id);
        $qrPath = 'private/qrcodes/' . $certificate->cert_id . '.png';
        $qrFullPath = storage_path('app/' . $qrPath);

        // Ensure directory exists
        $qrDir = dirname($qrFullPath);
        if (!is_dir($qrDir)) {
            mkdir($qrDir, 0755, true);
        }

        // Generate QR code
        // Keep the QR reasonably small (it will also be embedded into the seal).
        // Big QR images bloat certified PDFs without improving scan reliability.
        QrCode::format('png')
            ->size(160)
            ->margin(1)
            ->errorCorrection('H')
            ->generate($verifyUrl, $qrFullPath);

        // Update certificate with QR path
        $certificate->update(['qr_code_path' => $qrPath]);

        return $qrPath;
    }

    /**
     * Add metadata to PDF (real implementation with FPDF/FPDI).
     *
     * @param string $pdfPath
     * @param OfficialDocument $document
     * @param DocumentCertificate $certificate
     * @return void
     */
    protected function addPdfMetadata(
        string $pdfPath,
        OfficialDocument $document,
        DocumentCertificate $certificate
    ): void {
        try {
            // Create temporary file for modified PDF
            $tempPath = $pdfPath . '.tmp';
            
            // Initialize FPDI
            $pdf = new \setasign\Fpdi\Fpdi();
            // Reduce output size
            if (method_exists($pdf, 'SetCompression')) {
                $pdf->SetCompression(true);
            }
            
            // Get page count
            $pageCount = $pdf->setSourceFile($pdfPath);
            
            // Stamp strategy:
            // - last: add stamp only on the last page (recommended; avoids obscuring content)
            // - all : add stamp on every page
            $stampPages = config('official_documents.certification.stamp_pages', 'last');

            // Import all pages
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);
                
                // Add page with same orientation and size
                $orientation = $size['width'] > $size['height'] ? 'L' : 'P';
                $pdf->AddPage($orientation, [$size['width'], $size['height']]);
                
                // Use the imported page
                $pdf->useTemplate($templateId);

                $shouldStamp = ($stampPages === 'all') || ($stampPages === 'last' && $pageNo === $pageCount);
                if ($shouldStamp) {
                    $this->addCertificationStamp($pdf, $size['width'], $size['height'], $certificate);
                }
            }

            // Optional: append a dedicated certification page (recommended for EU-style certified translations)
            if (config('official_documents.certification.add_certificate_page', true)) {
                $this->appendCertificatePage($pdf, $document, $certificate);
            }
            
            // Save to temporary file
            $pdf->Output('F', $tempPath);
            
            // Replace original with modified
            rename($tempPath, $pdfPath);
            
            Log::info('PDF certification stamp added successfully', [
                'cert_id' => $certificate->cert_id,
                'document_type' => $document->document_type,
                'pages' => $pageCount,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to add PDF metadata', [
                'error' => $e->getMessage(),
                'cert_id' => $certificate->cert_id,
            ]);
            throw $e;
        }
    }
    
    /**
     * Add certification stamp to PDF using CertifiedSealGenerator.
     */
    protected function addCertificationStamp($pdf, $pageWidth, $pageHeight, $certificate): void
    {
        // Get date
        $date = $certificate->issued_at ? $certificate->issued_at->format('Y-m-d') : now()->format('Y-m-d');
        
        // Get verification URL
        $verificationUrl = 'https://culturaltranslate.com/verify/' . $certificate->cert_id;
        
        // Generate seal using CertifiedSealGenerator
        $sealGenerator = app(CertifiedSealGenerator::class);
        
        try {
            $sealPngRelative = $sealGenerator->generatePng($certificate->cert_id, $date, $verificationUrl);
            $sealPngFull = storage_path('app/' . $sealPngRelative);
            
            // Check if PNG file was created
            if (!file_exists($sealPngFull)) {
                throw new \RuntimeException("PNG file was not created at: {$sealPngFull}");
            }
            
            // Position stamp in bottom-right with a safe margin.
            // FPDF/FPDI default unit is mm; keep the stamp modest to avoid covering content.
            $stampSize = min(22, max(18, $pageWidth * 0.10));
            $margin = 12;
            $x = $pageWidth - $stampSize - $margin;
            $y = $pageHeight - $stampSize - $margin;

            // Add a subtle white backing box so the seal doesn't "dirty" the underlying content.
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Rect($x - 1.5, $y - 1.5, $stampSize + 3, $stampSize + 3, 'F');
            
            // Add stamp image
            $pdf->Image($sealPngFull, $x, $y, $stampSize, $stampSize, 'PNG');

            // Add a short footer line (non-intrusive) near the bottom-left.
            $pdf->SetTextColor(60, 60, 60);
            $pdf->SetFont('Arial', '', 7);
            $footer = 'Certificate: ' . $certificate->cert_id . '  •  Verify: culturaltranslate.com/verify/' . $certificate->cert_id;
            $pdf->SetXY(12, $pageHeight - 8);
            $pdf->Cell(0, 4, $footer, 0, 0, 'L');
            
            Log::info('Certification stamp added successfully', [
                'cert_id' => $certificate->cert_id,
                'png_path' => $sealPngFull,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to generate or add certification stamp', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'cert_id' => $certificate->cert_id,
            ]);
            
            // Fallback: simple text stamp
            $pdf->SetTextColor(41, 128, 185);
            $pdf->SetFont('Arial', 'B', 8);
            $x = ($pageWidth - 60) / 2;
            $y = $pageHeight - 60 - 20;
            $pdf->SetXY($x, $y);
            $pdf->Cell(60, 5, 'CERTIFIED', 0, 0, 'C');
            $pdf->SetXY($x, $y + 5);
            $pdf->Cell(60, 5, 'TRANSLATION', 0, 0, 'C');
        }
    }

    /**
     * Append a dedicated certification page (recommended: keeps seals/QR away from original content).
     */
    protected function appendCertificatePage($pdf, OfficialDocument $document, DocumentCertificate $certificate): void
    {
        $pdf->AddPage('P', 'A4');
        $pdf->SetTextColor(0, 0, 0);
        
        // Header with title
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->Cell(0, 15, 'Certificate of Translation Accuracy', 0, 1, 'C');
        $pdf->Ln(5);
        
        // Issued by
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetTextColor(60, 60, 60);
        $pdf->Cell(0, 6, 'Issued by: Cultural Translate Platform', 0, 1, 'C');
        $pdf->Cell(0, 6, 'www.culturaltranslate.com', 0, 1, 'C');
        $pdf->Ln(10);
        
        // Certificate details section
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'Certificate Details', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 11);
        
        $issueDate = $certificate->issued_at?->format('F d, Y') ?? now()->format('F d, Y');
        $verify = 'https://culturaltranslate.com/verify/' . $certificate->cert_id;
        
        // Get proper document type name
        $docTypeName = $this->getDocumentTypeName($document->document_type);
        
        $lines = [
            'Certificate ID: ' . $certificate->cert_id,
            'Issue Date: ' . $issueDate,
            'Document Type: ' . $docTypeName,
            'Source Language: ' . strtoupper($document->source_language),
            'Target Language: ' . strtoupper($document->target_language),
            '',
            'Verification URL: ' . $verify,
            '',
            '',
        ];
        
        foreach ($lines as $line) {
            $pdf->Cell(0, 6, $line, 0, 1, 'L');
        }
        
        // Certification statement section
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'Certification Statement', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 10);
        
        $statement = "This is to certify that the attached document has been professionally translated from " .
                     strtoupper($document->source_language) . " to " . strtoupper($document->target_language) .
                     " by Cultural Translate Platform using AI-assisted translation with human review.\n\n" .
                     "The translation is complete and accurate to the best of our knowledge and ability. " .
                     "This certificate is valid without physical signature and can be verified online " .
                     "at any time using the verification URL or QR code provided.\n\n" .
                     "This certified translation is accepted by embassies, government offices, universities, " .
                     "and other official institutions.";
        
        $pdf->MultiCell(0, 5, $statement, 0, 'L');
        
        // Add seal
        $sealGenerator = app(CertifiedSealGenerator::class);
        $sealPngRelative = $sealGenerator->generatePng($certificate->cert_id, $issueDate, $verify);
        $sealPngFull = storage_path('app/' . $sealPngRelative);
        
        if (file_exists($sealPngFull)) {
            $stampSize = 50;
            $x = 210 - $stampSize - 15;
            $y = 297 - $stampSize - 20;
            $pdf->Image($sealPngFull, $x, $y, $stampSize, $stampSize, 'PNG');
        }
    }
    
    /**
     * Get human-readable document type name
     */
    protected function getDocumentTypeName(string $type): string
    {
        $names = [
            'birth_certificate' => 'Birth Certificate',
            'marriage_certificate' => 'Marriage Certificate',
            'divorce_certificate' => 'Divorce Certificate',
            'death_certificate' => 'Death Certificate',
            'diploma' => 'Educational Diploma',
            'transcript' => 'Academic Transcript',
            'passport' => 'Passport',
            'id_card' => 'Identity Card',
            'driver_license' => 'Driver License',
            'utility_bill' => 'Utility Bill / Invoice',
            'bank_statement' => 'Bank Statement',
            'medical_report' => 'Medical Report',
            'legal_document' => 'Legal Document',
            'contract' => 'Contract',
            'other' => 'Official Document',
        ];
        
        return $names[$type] ?? 'Official Document';
    }

    /**
     * Build legal statement text with document details.
     *
     * @param OfficialDocument $document
     * @param DocumentCertificate $certificate
     * @return string
     */
    protected function buildStatementText(
        OfficialDocument $document,
        DocumentCertificate $certificate
    ): string {
        $template = config('official_documents.legal_statement.full');
        
        $replacements = [
            '[source language]' => strtoupper($document->source_language),
            '[target language]' => strtoupper($document->target_language),
            '[cert_id]' => $certificate->cert_id,
            '[issue_date]' => $certificate->issued_at?->format('F d, Y') ?? now()->format('F d, Y'),
            '[verification_url]' => url('/verify/' . $certificate->cert_id),
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $template
        );
    }

    /**
     * Validate seal file exists.
     *
     * @return bool
     */
    public function validateSealExists(): bool
    {
        $sealPath = config('official_documents.seal.path');
        $svgPath = config('official_documents.seal.svg_path');

        return file_exists($sealPath) || file_exists($svgPath);
    }

    /**
     * Get seal file path (prefer SVG if available).
     *
     * @return string|null
     */
    public function getSealPath(): ?string
    {
        $svgPath = config('official_documents.seal.svg_path');
        if (file_exists($svgPath)) {
            return $svgPath;
        }

        $pngPath = config('official_documents.seal.path');
        if (file_exists($pngPath)) {
            return $pngPath;
        }

        return null;
    }
}
