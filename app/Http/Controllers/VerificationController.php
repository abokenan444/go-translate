<?php
namespace App\Http\Controllers;
use App\Services\TrustFramework\{VerificationService, ComplianceService, AuditLogService, AcceptanceRulesService};
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class VerificationController extends Controller
{
    protected $verificationService;
    protected $complianceService;
    protected $auditService;
    protected $acceptanceService;
    
    public function __construct()
    {
        $this->verificationService = app(VerificationService::class);
        $this->complianceService = app(ComplianceService::class);
        $this->auditService = app(AuditLogService::class);
        $this->acceptanceService = app(AcceptanceRulesService::class);
    }
    
    public function show(Request $request, string $code)
    {
        $result = $this->verificationService->verify($code);
        
        if (!$result) {
            return view('verification.not-found', ['code' => $code]);
        }
        
        // Log verification
        $this->auditService->log([
            'action' => 'certificate_verified',
            'entity_type' => 'document_certificate',
            'entity_id' => 0,
            'user_id' => null,
            'metadata' => ['verification_code' => $code, 'ip' => $request->ip()],
        ]);
        
        // Get compliance seals
        $seals = [];
        foreach ($result['compliance_seals'] as $sealCode) {
            $seal = $this->complianceService->getSeal($sealCode);
            if ($seal) {
                $seals[] = $seal;
            }
        }
        
        // Get acceptance rules
        $country = $request->get('country'); // From query param or IP
        $sector = $request->get('sector'); // From query param or document type
        $lang = $request->get('lang', 'en');
        
        $acceptanceRules = $this->acceptanceService->getRules($country, $sector, $lang);
        $countries = $this->acceptanceService->getAllCountries();
        $sectors = $this->acceptanceService->getAllSectors();
        
        return view('verification.show', [
            'certificate' => $result,
            'seals' => $seals,
            'acceptanceRules' => $acceptanceRules,
            'countries' => $countries,
            'sectors' => $sectors,
            'selectedCountry' => $country,
            'selectedSector' => $sector,
        ]);
    }
    
    public function exportPdf(Request $request, string $code)
    {
        $result = $this->verificationService->verify($code);
        
        if (!$result) {
            abort(404, 'Certificate not found');
        }
        
        // Log PDF export
        $this->auditService->log([
            'action' => 'certificate_pdf_exported',
            'entity_type' => 'document_certificate',
            'entity_id' => 0,
            'user_id' => null,
            'metadata' => ['verification_code' => $code, 'ip' => $request->ip()],
        ]);
        
        // Get compliance seals
        $seals = [];
        foreach ($result['compliance_seals'] as $sealCode) {
            $seal = $this->complianceService->getSeal($sealCode);
            if ($seal) {
                $seals[] = $seal;
            }
        }
        
        // Get acceptance rules
        $country = $request->get('country');
        $sector = $request->get('sector');
        $lang = $request->get('lang', 'en');
        
        $acceptanceRules = $this->acceptanceService->getRules($country, $sector, $lang);
        
        // Generate QR Code URL
        $verificationUrl = url('/verify/' . $code);
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($verificationUrl);
        
        // Build HTML for PDF
        $html = $this->buildPdfHtml($result, $seals, $acceptanceRules, $qrCodeUrl, $verificationUrl);
        
        // Generate PDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 20,
            'margin_bottom' => 20,
        ]);
        
        $mpdf->SetTitle('Certificate Verification - ' . $result['certificate_number']);
        $mpdf->SetAuthor('CulturalTranslate');
        $mpdf->SetCreator('CulturalTranslate Trust Framework');
        
        $mpdf->WriteHTML($html);
        
        $filename = 'Certificate_' . $result['certificate_number'] . '.pdf';
        
        return $mpdf->Output($filename, 'D'); // D = Download
    }
    
    protected function buildPdfHtml($certificate, $seals, $acceptanceRules, $qrCodeUrl, $verificationUrl)
    {
        $sealsHtml = '';
        foreach ($seals as $seal) {
            $sealsHtml .= '<span style="display: inline-block; padding: 6px 12px; margin: 5px; background-color: ' . $seal['color'] . '; color: white; border-radius: 4px; font-size: 11px; font-weight: bold;">' . htmlspecialchars($seal['name']) . '</span>';
        }
        
        $statusColor = $certificate['status'] === 'valid' ? '#c6f6d5' : '#fed7d7';
        $statusTextColor = $certificate['status'] === 'valid' ? '#22543d' : '#742a2a';
        
        $html = '
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; color: #2d3748; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #4299e1; padding-bottom: 20px; }
        .header h1 { color: #1a202c; font-size: 28px; margin: 0; }
        .header p { color: #718096; margin: 5px 0; }
        .status { display: inline-block; padding: 8px 16px; background: ' . $statusColor . '; color: ' . $statusTextColor . '; border-radius: 20px; font-weight: bold; font-size: 14px; }
        .section { margin: 20px 0; padding: 15px; background: #f7fafc; border-left: 4px solid #4299e1; }
        .section h2 { color: #1a202c; font-size: 18px; margin-top: 0; }
        .info-grid { display: table; width: 100%; margin: 15px 0; }
        .info-row { display: table-row; }
        .info-label { display: table-cell; padding: 8px; font-weight: bold; color: #718096; font-size: 12px; text-transform: uppercase; width: 40%; }
        .info-value { display: table-cell; padding: 8px; color: #2d3748; font-size: 14px; }
        .qr-section { text-align: center; margin: 20px 0; }
        .footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 2px solid #e2e8f0; font-size: 11px; color: #718096; }
        .acceptance-content { white-space: pre-line; font-size: 12px; line-height: 1.8; }
    </style>
</head>
<body>
    <div class="header">
        <h1>✓ Certificate Verification</h1>
        <p><strong>CulturalTranslate</strong> Trust Framework</p>
        <p style="font-size: 12px;">This is an official verification document</p>
    </div>

    <div class="section">
        <h2>Certificate Details</h2>
        <div style="margin-bottom: 15px;">
            <span class="status">' . strtoupper($certificate['status']) . '</span>
        </div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Certificate Number</div>
                <div class="info-value">' . htmlspecialchars($certificate['certificate_number']) . '</div>
            </div>
            <div class="info-row">
                <div class="info-label">Issued Date</div>
                <div class="info-value">' . htmlspecialchars($certificate['issued_at']) . '</div>
            </div>
            <div class="info-row">
                <div class="info-label">Translator</div>
                <div class="info-value">' . htmlspecialchars($certificate['translator_name'] ?? 'N/A') . '</div>
            </div>
            <div class="info-row">
                <div class="info-label">Verified</div>
                <div class="info-value">' . $certificate['verification_count'] . ' times</div>
            </div>
        </div>
    </div>

    ' . (count($seals) > 0 ? '
    <div class="section">
        <h2>Compliance Seals</h2>
        <div>' . $sealsHtml . '</div>
    </div>
    ' : '') . '

    <div class="qr-section">
        <img src="' . $qrCodeUrl . '" alt="QR Code" style="width: 150px; height: 150px; border: 2px solid #e2e8f0; padding: 10px;">
        <p style="font-size: 11px; color: #718096; margin-top: 10px;">Scan to verify online</p>
        <p style="font-size: 10px; color: #a0aec0;">' . htmlspecialchars($verificationUrl) . '</p>
    </div>

    <div class="section">
        <h2>' . htmlspecialchars($acceptanceRules['global']['title']) . '</h2>
        <div class="acceptance-content">' . nl2br(htmlspecialchars($acceptanceRules['global']['content'])) . '</div>
    </div>

    ' . (isset($acceptanceRules['country']) ? '
    <div class="section">
        <h2>' . htmlspecialchars($acceptanceRules['country']['title']) . '</h2>
        <div class="acceptance-content">' . nl2br(htmlspecialchars($acceptanceRules['country']['content'])) . '</div>
    </div>
    ' : '') . '

    <div class="footer">
        <p><strong>CulturalTranslate Trust Framework</strong></p>
        <p>This verification was logged and is part of an immutable audit trail.</p>
        <p>Generated on ' . date('Y-m-d H:i:s') . ' UTC</p>
        <p style="font-size: 10px; margin-top: 10px;">This document is digitally verifiable at: ' . htmlspecialchars($verificationUrl) . '</p>
    </div>
</body>
</html>';
        
        return $html;
    }
}
