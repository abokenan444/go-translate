<?php

namespace App\Services;

use App\Models\DocumentCertificate;
use App\Models\OfficialDocument;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\Facades\Image;

class DocumentCertificationService
{
    /**
     * Generate certificate ID in format: CT-YYYY-MM-XXXXXXXX
     */
    public function generateCertificateId(): string
    {
        $prefix = 'CT';
        $date = now()->format('Y-m');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        
        return "{$prefix}-{$date}-{$random}";
    }

    /**
     * Generate QR code for certificate verification
     */
    public function generateQRCode(string $certId): string
    {
        $verificationUrl = route('verify.certificate', ['certId' => $certId]);
        
        $qrCode = QrCode::format('png')
            ->size(300)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($verificationUrl);
        
        $filename = "qr-codes/{$certId}.png";
        Storage::disk('public')->put($filename, $qrCode);
        
        return $filename;
    }

    /**
     * Generate official stamp image
     */
    public function generateStamp(DocumentCertificate $certificate): string
    {
        // Create stamp image (400x400)
        $stamp = Image::canvas(400, 400, 'transparent');
        
        // Draw outer circle (purple/blue gradient)
        $stamp->circle(380, 200, 200, function ($draw) {
            $draw->background('#6366f1');
            $draw->border(3, '#4f46e5');
        });
        
        // Draw inner circle
        $stamp->circle(340, 200, 200, function ($draw) {
            $draw->border(2, '#ffffff');
        });
        
        // Add company name (curved text - simplified as straight for now)
        $stamp->text('CULTURALTRANSLATE', 200, 80, function($font) {
            $font->file(public_path('fonts/Arial-Bold.ttf'));
            $font->size(24);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('middle');
        });
        
        // Add registration number
        $stamp->text('NL KvK 83656480', 200, 140, function($font) {
            $font->file(public_path('fonts/Arial.ttf'));
            $font->size(16);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('middle');
        });
        
        // Add "CERTIFIED TRANSLATION"
        $stamp->text('CERTIFIED', 200, 200, function($font) {
            $font->file(public_path('fonts/Arial-Bold.ttf'));
            $font->size(28);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('middle');
        });
        
        $stamp->text('TRANSLATION', 200, 230, function($font) {
            $font->file(public_path('fonts/Arial-Bold.ttf'));
            $font->size(28);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('middle');
        });
        
        // Add certificate ID
        $stamp->text($certificate->cert_id, 200, 280, function($font) {
            $font->file(public_path('fonts/Arial.ttf'));
            $font->size(14);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('middle');
        });
        
        // Add issue date
        $stamp->text($certificate->issued_at->format('d/m/Y'), 200, 310, function($font) {
            $font->file(public_path('fonts/Arial.ttf'));
            $font->size(14);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('middle');
        });
        
        // Save stamp
        $filename = "stamps/{$certificate->cert_id}.png";
        $stamp->save(storage_path("app/public/{$filename}"));
        
        return $filename;
    }

    /**
     * Create certificate for document
     */
    public function createCertificate(OfficialDocument $document): DocumentCertificate
    {
        $certId = $this->generateCertificateId();
        
        // Calculate expiration (6 months default)
        $expiresAt = now()->addMonths(6);
        
        $certificate = DocumentCertificate::create([
            'cert_id' => $certId,
            'document_id' => $document->id,
            'original_hash' => hash_file('sha256', Storage::path($document->original_file_path)),
            'translated_hash' => $document->translation ? 
                hash_file('sha256', Storage::path($document->translation->translated_file_path)) : null,
            'status' => 'valid',
            'issued_at' => now(),
            'expires_at' => $expiresAt,
            'metadata' => [
                'document_type' => $document->document_type,
                'source_language' => $document->source_language,
                'target_language' => $document->target_language,
                'issued_by' => 'CulturalTranslate',
                'registration_number' => 'NL KvK 83656480',
            ],
        ]);
        
        // Generate QR code
        $qrPath = $this->generateQRCode($certId);
        $certificate->update(['qr_code_path' => $qrPath]);
        
        // Generate stamp
        $stampPath = $this->generateStamp($certificate);
        
        return $certificate;
    }

    /**
     * Apply stamp and QR code to translated document
     */
    public function applyStampToDocument(DocumentCertificate $certificate, string $pdfPath): string
    {
        // This would use a PDF library like TCPDF or FPDF to:
        // 1. Open the translated PDF
        // 2. Add stamp image to first page
        // 3. Add QR code to bottom right
        // 4. Add certificate information footer
        // 5. Save as new certified PDF
        
        // For now, return the path where certified document will be saved
        $certifiedPath = str_replace('.pdf', '_certified.pdf', $pdfPath);
        
        // TODO: Implement PDF stamping logic
        
        return $certifiedPath;
    }
}
