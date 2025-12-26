<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DocumentCertificate;
use Illuminate\Http\JsonResponse;

class CertificateApiController extends Controller
{
    /**
     * Verify certificate via API.
     *
     * This endpoint allows embassies, government authorities, and institutions
     * to programmatically verify certified translations.
     *
     * @param string $certId Certificate ID
     * @return JsonResponse
     */
    public function show(string $certId): JsonResponse
    {
        $certificate = DocumentCertificate::with('document')
            ->where('cert_id', $certId)
            ->first();

        if (!$certificate) {
            return response()->json([
                'found' => false,
                'status' => 'not_found',
                'message' => 'No certificate found for the provided Certificate ID.',
                'cert_id' => $certId,
            ], 404);
        }

        $isValid = $certificate->isValid();
        $isExpired = $certificate->isExpired();

        // Determine status
        $status = $isValid ? 'valid' : ($isExpired ? 'expired' : $certificate->status);

        return response()->json([
            'found' => true,
            'status' => $status,
            'cert_id' => $certificate->cert_id,
            'issued_at' => $certificate->issued_at?->toIso8601String(),
            'expires_at' => $certificate->expires_at?->toIso8601String(),
            'issuer' => [
                'name' => config('official_documents.certificate.issuer_name'),
                'location' => config('official_documents.certificate.issuer_location'),
            ],
            'document' => $certificate->document ? [
                'id' => $certificate->document->id,
                'type' => $certificate->document->document_type,
                'type_name' => $certificate->document->document_type_name,
                'source_language' => $certificate->document->source_language,
                'target_language' => $certificate->document->target_language,
                'status' => $certificate->document->status,
                'created_at' => $certificate->document->created_at->toIso8601String(),
            ] : null,
            'verification' => [
                'web_url' => url('/verify/' . $certificate->cert_id),
                'api_url' => url('/api/certificates/' . $certificate->cert_id),
            ],
            'integrity' => [
                'original_hash' => $certificate->original_hash,
                'translated_hash' => $certificate->translated_hash,
            ],
            'note' => 'This endpoint is provided by Cultural Translate to allow embassies, government authorities, and institutions to verify certified translations. Acceptance of this translation remains subject to the rules of the receiving authority.',
        ]);
    }
}
