<?php

namespace App\Http\Controllers;

use App\Models\CtsCertificate;
use App\Services\CtsCertificateService;
use Illuminate\Http\Request;

class CtsVerificationController extends Controller
{
    protected $certificateService;

    public function __construct(CtsCertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * Display the CTS verification page
     */
    public function index()
    {
        return view('cts-verification.index');
    }

    /**
     * Verify a CTS certificate by ID
     */
    public function verify(Request $request, ?string $certificateId = null)
    {
        // Get certificate ID from route or request
        $certificateId = $certificateId ?? $request->input('certificate_id');

        if (!$certificateId) {
            return view('cts-verification.index');
        }

        // Prepare verifier information
        $verifierInfo = [
            'ip' => $request->ip(),
            'country' => $this->getCountryFromIp($request->ip()),
            'user_agent' => $request->userAgent(),
        ];

        // Verify certificate
        $result = $this->certificateService->verify($certificateId, $verifierInfo);

        if (!$result) {
            return view('cts-verification.not-found', compact('certificateId'));
        }

        $certificate = $result['certificate'];
        $isValid = $result['is_valid'];
        $verificationCount = $result['verification_count'];
        $lastVerifiedAt = $result['last_verified_at'];

        return view('cts-verification.show', compact(
            'certificate',
            'isValid',
            'verificationCount',
            'lastVerifiedAt'
        ));
    }

    /**
     * Get country from IP address (simplified)
     */
    protected function getCountryFromIp(string $ip): ?string
    {
        // In production, use a proper GeoIP service
        // For now, return null
        return null;
    }

    /**
     * Download certificate PDF
     */
    public function download(string $certificateId)
    {
        $certificate = CtsCertificate::where('certificate_id', $certificateId)->firstOrFail();

        if (!$certificate->certificate_pdf_path) {
            abort(404, 'Certificate PDF not found');
        }

        return response()->download(
            storage_path('app/' . $certificate->certificate_pdf_path),
            "CTS-Certificate-{$certificateId}.pdf"
        );
    }
}
