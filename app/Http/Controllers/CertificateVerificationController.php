<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certificate;

class CertificateVerificationController extends Controller
{
    public function index()
    {
        return view('certificates.verify');
    }
    
    public function show($certificateId)
    {
        // Try to find by cert_id or id
        $certificate = Certificate::where('cert_id', $certificateId)
            ->orWhere('id', $certificateId)
            ->with(['revocation', 'document'])
            ->first();
        
        if ($certificate) {
            // Determine legal status
            $legalStatus = 'valid';
            if ($certificate->revocation) {
                $legalStatus = $certificate->revocation->action === 'revoked' ? 'revoked' : 'frozen';
            }
            
            return view('certificates.result', [
                'found' => true,
                'certificate' => $certificate,
                'legalStatus' => $legalStatus,
                'revocation' => $certificate->revocation,
            ]);
        }
        
        return view('certificates.result', [
            'found' => false,
            'certificateId' => $certificateId,
        ]);
    }
    
    public function search(Request $request)
    {
        $request->validate([
            'certificate_id' => 'required|string',
        ]);
        
        $certificateId = $request->input('certificate_id');
        
        return redirect()->route('verify.certificate', ['certificateId' => $certificateId]);
    }
    
    public function verify($certificateId)
    {
        $certificate = Certificate::where('certificate_id', $certificateId)
            ->orWhere('verification_code', $certificateId)
            ->first();
        
        if ($certificate) {
            return response()->json([
                'success' => true,
                'valid' => true,
                'certificate' => [
                    'id' => $certificate->certificate_id,
                    'issue_date' => $certificate->issue_date,
                    'expiry_date' => $certificate->expiry_date,
                    'status' => $certificate->status,
                    'translator' => [
                        'name' => $certificate->translator_name ?? 'CulturalTranslate',
                        'id' => $certificate->translator_id ?? 'CT-Official',
                    ],
                    'document' => [
                        'source_language' => $certificate->source_language,
                        'target_language' => $certificate->target_language,
                    ]
                ]
            ]);
        }
        
        return response()->json([
            'success' => false,
            'valid' => false,
            'message' => 'Certificate not found or invalid'
        ], 404);
    }
}
