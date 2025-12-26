<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Certificate Generation Service
 * Generates professional certificates for translations
 */
class CertificateGenerationService
{
    protected QRCodeVerificationService $qrService;
    
    public function __construct(QRCodeVerificationService $qrService)
    {
        $this->qrService = $qrService;
    }
    
    /**
     * Generate certificate for document
     *
     * @param array $documentData
     * @return array
     */
    public function generateCertificate(array $documentData): array
    {
        // Generate unique certificate ID
        $certificateId = $this->generateCertificateId();
        $serialNumber = $this->generateSerialNumber();
        
        // Generate QR code
        $qrCode = $this->qrService->generateQRCode($certificateId, [
            'size' => 250,
            'format' => 'svg',
            'error_correction' => 'H'
        ]);
        
        // Save QR code
        $qrPath = $this->saveQRCode($certificateId, $qrCode);
        
        // Prepare certificate data
        $certificateData = [
            'certificate_id' => $certificateId,
            'serial_number' => $serialNumber,
            'document_type' => $documentData['document_type'] ?? 'Official Translation',
            'source_language' => $documentData['source_language'],
            'target_language' => $documentData['target_language'],
            'original_filename' => $documentData['original_filename'],
            'page_count' => $documentData['page_count'] ?? 1,
            'word_count' => $documentData['word_count'] ?? 0,
            'issue_date' => now()->format('F d, Y'),
            'verification_url' => $this->qrService->getVerificationUrl($certificateId),
            'qr_code_svg' => $qrCode,
            'qr_code_path' => $qrPath,
            'translator' => $documentData['translator'] ?? null,
            'partner' => $documentData['partner'] ?? null,
            'legal_statement' => $this->getLegalStatement($documentData),
            'platform_stamp' => $this->getPlatformStamp(),
            'partner_stamp' => isset($documentData['partner']) ? $this->getPartnerStamp($documentData['partner']) : null
        ];
        
        // Generate PDF certificate
        $certificatePdfPath = $this->generateCertificatePDF($certificateData);
        
        // Store certificate record
        $this->storeCertificateRecord($certificateId, $certificateData, $certificatePdfPath);
        
        return [
            'success' => true,
            'certificate_id' => $certificateId,
            'serial_number' => $serialNumber,
            'certificate_pdf_path' => $certificatePdfPath,
            'qr_code_path' => $qrPath,
            'verification_url' => $certificateData['verification_url']
        ];
    }
    
    /**
     * Generate unique certificate ID
     *
     * @return string
     */
    private function generateCertificateId(): string
    {
        $year = date('Y');
        $random = strtoupper(Str::random(8));
        return "CT-{$year}-{$random}";
    }
    
    /**
     * Generate serial number
     *
     * @return string
     */
    private function generateSerialNumber(): string
    {
        $count = DB::table('official_documents')->count() + 1;
        $year = date('Y');
        return sprintf("SN-%s-%06d", $year, $count);
    }
    
    /**
     * Save QR code to storage
     *
     * @param string $certificateId
     * @param string $qrCode
     * @return string
     */
    private function saveQRCode(string $certificateId, string $qrCode): string
    {
        $filename = "qr_codes/{$certificateId}.svg";
        Storage::disk('public')->put($filename, $qrCode);
        return $filename;
    }
    
    /**
     * Get legal statement for certificate
     *
     * @param array $data
     * @return string
     */
    private function getLegalStatement(array $data): string
    {
        return <<<EOT
CERTIFICATE OF TRANSLATION

This is to certify that the attached document has been accurately translated from {$data['source_language']} 
to {$data['target_language']} by CulturalTranslate, a certified translation platform.

The translation has been completed in accordance with international translation standards and cultural 
adaptation guidelines. The translated content preserves the meaning, context, and legal implications 
of the original document.

Certificate Details:
• Certificate ID: {$data['certificate_id']}
• Serial Number: {$data['serial_number']}
• Issue Date: {$data['issue_date']}
• Document Type: {$data['document_type']}
• Page Count: {$data['page_count']}

This certificate can be verified at any time by:
1. Visiting: {$data['verification_url']}
2. Scanning the QR code provided
3. Contacting our verification department

This certificate is issued under the authority of CulturalTranslate Platform and is valid for official use.

_____________________
Digital Signature
CulturalTranslate Platform
EOT;
    }
    
    /**
     * Get platform stamp SVG
     *
     * @return string
     */
    private function getPlatformStamp(): string
    {
        $stampPath = public_path('images/certified-translation/official_stamp.svg');
        
        if (file_exists($stampPath)) {
            return file_get_contents($stampPath);
        }
        
        // Generate default stamp SVG
        return $this->generateDefaultStampSVG('CulturalTranslate', 'Certified Platform');
    }
    
    /**
     * Get partner stamp
     *
     * @param array $partner
     * @return string|null
     */
    private function getPartnerStamp(array $partner): ?string
    {
        if (isset($partner['stamp_path']) && file_exists($partner['stamp_path'])) {
            return file_get_contents($partner['stamp_path']);
        }
        
        if (isset($partner['name'])) {
            return $this->generateDefaultStampSVG($partner['name'], 'Certified Partner');
        }
        
        return null;
    }
    
    /**
     * Generate default stamp SVG
     *
     * @param string $name
     * @param string $subtitle
     * @return string
     */
    private function generateDefaultStampSVG(string $name, string $subtitle): string
    {
        return <<<SVG
<svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
    <circle cx="100" cy="100" r="90" fill="none" stroke="#2563eb" stroke-width="4"/>
    <circle cx="100" cy="100" r="80" fill="none" stroke="#2563eb" stroke-width="2"/>
    <text x="100" y="90" text-anchor="middle" font-family="Arial" font-size="18" font-weight="bold" fill="#2563eb">
        {$name}
    </text>
    <text x="100" y="110" text-anchor="middle" font-family="Arial" font-size="12" fill="#2563eb">
        {$subtitle}
    </text>
    <text x="100" y="130" text-anchor="middle" font-family="Arial" font-size="10" fill="#2563eb">
        {$this->getCurrentYear()}
    </text>
</svg>
SVG;
    }
    
    /**
     * Get current year
     *
     * @return string
     */
    private function getCurrentYear(): string
    {
        return date('Y');
    }
    
    /**
     * Generate certificate PDF
     *
     * @param array $data
     * @return string
     */
    private function generateCertificatePDF(array $data): string
    {
        $html = view('certificates.certificate-template', $data)->render();
        
        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'portrait')
            ->setOption('margin-top', 20)
            ->setOption('margin-bottom', 20)
            ->setOption('margin-left', 20)
            ->setOption('margin-right', 20);
        
        $filename = "certificates/{$data['certificate_id']}.pdf";
        $pdfPath = storage_path("app/public/{$filename}");
        
        // Ensure directory exists
        $directory = dirname($pdfPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $pdf->save($pdfPath);
        
        return $filename;
    }
    
    /**
     * Store certificate record in database
     *
     * @param string $certificateId
     * @param array $data
     * @param string $pdfPath
     * @return void
     */
    private function storeCertificateRecord(string $certificateId, array $data, string $pdfPath): void
    {
        DB::table('certificates')->insert([
            'certificate_id' => $certificateId,
            'serial_number' => $data['serial_number'],
            'document_type' => $data['document_type'],
            'source_language' => $data['source_language'],
            'target_language' => $data['target_language'],
            'pdf_path' => $pdfPath,
            'qr_code_path' => $data['qr_code_path'],
            'verification_url' => $data['verification_url'],
            'status' => 'active',
            'issued_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
    
    /**
     * Append certificate to translated document
     *
     * @param string $documentPath
     * @param string $certificatePath
     * @return string Path to combined PDF
     */
    public function appendCertificateToDocument(string $documentPath, string $certificatePath): string
    {
        // This would use a PDF library like FPDI to merge PDFs
        // For now, returning certificate path separately
        return $certificatePath;
    }
}
