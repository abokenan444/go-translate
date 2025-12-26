<?php

namespace App\Services;

use App\Models\OfficialDocument;
use App\Models\Partner;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use setasign\Fpdi\Fpdi;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * Service for generating certified documents with dual stamps
 * (Cultural Translate stamp + Certified Partner stamp)
 */
class CertifiedDocumentService
{
    /**
     * Physical copy pricing configuration
     */
    const PHYSICAL_COPY_BASE_PRICE = 50.00; // Base price in USD
    const SHIPPING_PRICE_PER_PAGE = 2.00;   // Additional cost per page
    const EXPRESS_SHIPPING_MULTIPLIER = 2.5; // Express shipping costs 2.5x more

    /**
     * Stamp dimensions and positions
     */
    const PLATFORM_STAMP_WIDTH = 60;
    const PLATFORM_STAMP_HEIGHT = 60;
    const PARTNER_STAMP_WIDTH = 55;
    const PARTNER_STAMP_HEIGHT = 55;
    
    /**
     * Generate certified document with dual stamps
     */
    public function generateCertifiedDocument(OfficialDocument $document): bool
    {
        try {
            // Ensure document has translated version
                Log::error('Translated document not found', ['document_id' => $document->id]);
                return false;
            }

            // Load the translated PDF
            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile(Storage::path($document->translated_path));

            // Generate QR code for verification
            $qrCodePath = $this->generateVerificationQR($document);

            // Add stamps to each page
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);
                
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);

                // Add Cultural Translate stamp (bottom right)
                $this->addPlatformStamp($pdf, $size, $qrCodePath);

                // Add Partner stamp if assigned (bottom left)
                if ($document->certified_partner_id) {
                    $this->addPartnerStamp($pdf, $size, $document->certifiedPartner);
                }

                // Add page number footer
                $this->addPageFooter($pdf, $pageNo, $pageCount, $size);
            }

            // Save certified document
            $certifiedPath = 'certified_documents/' . $document->certificate_id . '_certified.pdf';
            $pdf->Output(Storage::path($certifiedPath), 'F');

            // Update document record
            $document->update([
                'certified_path' => $certifiedPath,
                'qr_code_path' => $qrCodePath,
                'status' => 'certified',
            ]);

            Log::info('Certified document generated successfully', [
                'document_id' => $document->id,
                'certificate_id' => $document->certificate_id,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to generate certified document', [
                'document_id' => $document->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Add Cultural Translate platform stamp
     */
    private function addPlatformStamp(Fpdi $pdf, array $size, string $qrCodePath): void
    {
        $x = $size['width'] - self::PLATFORM_STAMP_WIDTH - 10;
        $y = $size['height'] - self::PLATFORM_STAMP_HEIGHT - 10;

        // Add semi-transparent background
        $pdf->SetAlpha(0.1);
        $pdf->SetFillColor(0, 102, 204); // Blue
        $pdf->Rect($x - 5, $y - 5, self::PLATFORM_STAMP_WIDTH + 10, self::PLATFORM_STAMP_HEIGHT + 10, 'F');
        $pdf->SetAlpha(1);

        // Add platform logo/stamp
        $stampPath = public_path('images/platform-stamp.png');
        if (file_exists($stampPath)) {
            $pdf->Image($stampPath, $x, $y, self::PLATFORM_STAMP_WIDTH, self::PLATFORM_STAMP_HEIGHT);
        }

        // Add QR code
        if (Storage::exists($qrCodePath)) {
            $pdf->Image(
                Storage::path($qrCodePath),
                $x + 5,
                $y + self::PLATFORM_STAMP_HEIGHT - 20,
                15,
                15
            );
        }

        // Add text
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(0, 102, 204);
        $pdf->SetXY($x, $y + self::PLATFORM_STAMP_HEIGHT + 2);
        $pdf->Cell(self::PLATFORM_STAMP_WIDTH, 5, 'Cultural Translate', 0, 0, 'C');
        
        $pdf->SetFont('Arial', '', 6);
        $pdf->SetXY($x, $y + self::PLATFORM_STAMP_HEIGHT + 7);
        $pdf->Cell(self::PLATFORM_STAMP_WIDTH, 4, 'Certified Translation', 0, 0, 'C');
    }

    /**
     * Add Certified Partner stamp
     */
    private function addPartnerStamp(Fpdi $pdf, array $size, Partner $partner): void
    {
        $x = 10;
        $y = $size['height'] - self::PARTNER_STAMP_HEIGHT - 10;

        // Add semi-transparent background
        $pdf->SetAlpha(0.1);
        $pdf->SetFillColor(204, 0, 0); // Red
        $pdf->Rect($x - 5, $y - 5, self::PARTNER_STAMP_WIDTH + 10, self::PARTNER_STAMP_HEIGHT + 10, 'F');
        $pdf->SetAlpha(1);

        // Add partner logo/stamp if available
        if ($partner->stamp_image_path && Storage::exists($partner->stamp_image_path)) {
            $pdf->Image(
                Storage::path($partner->stamp_image_path),
                $x,
                $y,
                self::PARTNER_STAMP_WIDTH,
                self::PARTNER_STAMP_HEIGHT
            );
        }

        // Add partner info text
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(204, 0, 0);
        $pdf->SetXY($x, $y + self::PARTNER_STAMP_HEIGHT + 2);
        $pdf->Cell(self::PARTNER_STAMP_WIDTH, 5, $partner->company_name, 0, 0, 'C');
        
        $pdf->SetFont('Arial', '', 6);
        $pdf->SetXY($x, $y + self::PARTNER_STAMP_HEIGHT + 7);
        $pdf->Cell(self::PARTNER_STAMP_WIDTH, 4, 'License: ' . $partner->license_number, 0, 0, 'C');
    }

    /**
     * Add page footer with document info
     */
    private function addPageFooter(Fpdi $pdf, int $pageNo, int $totalPages, array $size): void
    {
        $pdf->SetFont('Arial', 'I', 7);
        $pdf->SetTextColor(128, 128, 128);
        
        // Center footer
        $footerText = "Page {$pageNo} of {$totalPages} | Certified by Cultural Translate | " . date('Y-m-d H:i:s');
        $pdf->SetXY(0, $size['height'] - 5);
        $pdf->Cell($size['width'], 4, $footerText, 0, 0, 'C');
    }

    /**
     * Generate QR code for document verification
     */
    private function generateVerificationQR(OfficialDocument $document): string
    {
        $verificationUrl = route('verify.certificate', ['certificate_id' => $document->certificate_id]);
        
        $qrCodePath = 'qr_codes/' . $document->certificate_id . '.png';
        $fullPath = Storage::path($qrCodePath);

        // Ensure directory exists
        $directory = dirname($fullPath);
            mkdir($directory, 0755, true);
        }

        // Generate QR code
        QrCode::format('png')
            ->size(300)
            ->margin(1)
            ->errorCorrection('H')
            ->generate($verificationUrl, $fullPath);

        return $qrCodePath;
    }

    /**
     * Calculate physical copy price based on document pages
     */
    public function calculatePhysicalCopyPrice(int $pages, bool $expressShipping = false): float
    {
        $price = self::PHYSICAL_COPY_BASE_PRICE + ($pages * self::SHIPPING_PRICE_PER_PAGE);
        
        if ($expressShipping) {
            $price *= self::EXPRESS_SHIPPING_MULTIPLIER;
        }

        return round($price, 2);
    }

    /**
     * Apply partner stamp to document
     */
    public function applyPartnerStamp(OfficialDocument $document, Partner $partner): bool
    {
        try {
            // Verify partner is certified
                Log::error('Partner is not certified', ['partner_id' => $partner->id]);
                return false;
            }

            // Update document
            $document->update([
                'certified_partner_id' => $partner->id,
                'partner_stamp_applied' => true,
                'partner_stamp_date' => now(),
            ]);

            // Regenerate certified document with partner stamp
            $this->generateCertifiedDocument($document);

            Log::info('Partner stamp applied successfully', [
                'document_id' => $document->id,
                'partner_id' => $partner->id,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to apply partner stamp', [
                'document_id' => $document->id,
                'partner_id' => $partner->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Mark document as printed by partner
     */
    public function markAsPrinted(OfficialDocument $document): bool
    {
        try {
            $document->update([
                'printed_by_partner' => true,
                'printed_at' => now(),
                'shipping_status' => 'printed',
            ]);

            Log::info('Document marked as printed', ['document_id' => $document->id]);
            return true;

        } catch (\Exception $e) {
            Log::error('Failed to mark document as printed', [
                'document_id' => $document->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Process shipping for physical copy
     */
    public function processShipping(OfficialDocument $document, string $trackingNumber): bool
    {
        try {
            $document->update([
                'shipping_status' => 'shipped',
                'tracking_number' => $trackingNumber,
                'shipped_at' => now(),
            ]);

            // TODO: Send email notification to customer with tracking number

            Log::info('Document shipped successfully', [
                'document_id' => $document->id,
                'tracking_number' => $trackingNumber,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to process shipping', [
                'document_id' => $document->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Mark document as delivered
     */
    public function markAsDelivered(OfficialDocument $document): bool
    {
        try {
            $document->update([
                'shipping_status' => 'delivered',
                'delivered_at' => now(),
            ]);

            // TODO: Send delivery confirmation email

            Log::info('Document marked as delivered', ['document_id' => $document->id]);
            return true;

        } catch (\Exception $e) {
            Log::error('Failed to mark document as delivered', [
                'document_id' => $document->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
