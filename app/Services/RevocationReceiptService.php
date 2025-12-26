<?php

namespace App\Services;

use App\Models\CertificateRevocation;
use App\Models\DocumentCertificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * Revocation Receipt Service
 * 
 * Generates legal PDF receipt for freeze/revoke actions
 */
class RevocationReceiptService
{
    /**
     * Generate revocation receipt PDF
     */
    public function generate(int $certificateId, int $revocationId): array
    {
        $certificate = DocumentCertificate::with(['document', 'document.user'])
            ->findOrFail($certificateId);

        $revocation = CertificateRevocation::with(['requester', 'approver', 'ledgerEvent'])
            ->findOrFail($revocationId);

        // Generate QR code for verification
        $verifyUrl = route('verify.certificate', $certificate->certificate_number);
        $qrCode = base64_encode(QrCode::format('png')->size(200)->generate($verifyUrl));

        $data = [
            'certificate' => $certificate,
            'revocation' => $revocation,
            'document' => $certificate->document,
            'qrCode' => $qrCode,
            'verifyUrl' => $verifyUrl,
            'ledgerHash' => $revocation->ledgerEvent?->hash ?? 'N/A',
            'generatedAt' => now()
        ];

        // Generate PDF
        $pdf = Pdf::loadView('pdf.revocation-receipt', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('margin-top', '10mm')
            ->setOption('margin-bottom', '10mm')
            ->setOption('margin-left', '15mm')
            ->setOption('margin-right', '15mm');

        // Save PDF
        $filename = 'revocation_receipt_' . $certificate->certificate_number . '_' . time() . '.pdf';
        $path = 'revocation_receipts/' . $filename;
        
        Storage::put($path, $pdf->output());

        // Update revocation record
        $revocation->update(['receipt_path' => $path]);

        return [
            'success' => true,
            'receipt_path' => $path,
            'receipt_url' => Storage::url($path),
            'filename' => $filename
        ];
    }
}
