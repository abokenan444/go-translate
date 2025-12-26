<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\TrustFramework\{VerificationService, ComplianceService, AuditLogService, AcceptanceRulesService};
use Illuminate\Http\Request;

class ApiVerificationController extends Controller
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
    
    public function verify(Request $request, string $code)
    {
        $result = $this->verificationService->verify($code);
        
        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Certificate not found',
                'code' => $code,
            ], 404);
        }
        
        // Log API verification
        $this->auditService->log([
            'action' => 'api_certificate_verified',
            'entity_type' => 'document_certificate',
            'entity_id' => 0,
            'user_id' => null,
            'metadata' => [
                'verification_code' => $code,
                'ip' => $request->ip(),
                'api_key' => $request->header('X-API-Key'),
            ],
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
        
        return response()->json([
            'success' => true,
            'certificate' => $result,
            'compliance_seals' => $seals,
            'acceptance_rules' => $acceptanceRules,
            'verified_at' => now()->toIso8601String(),
        ]);
    }
    
    public function countries()
    {
        $countries = $this->acceptanceService->getAllCountries();
        
        return response()->json([
            'success' => true,
            'countries' => $countries,
        ]);
    }
    
    public function sectors()
    {
        $sectors = $this->acceptanceService->getAllSectors();
        
        return response()->json([
            'success' => true,
            'sectors' => $sectors,
        ]);
    }
}
