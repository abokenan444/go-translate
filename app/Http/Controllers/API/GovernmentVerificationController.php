<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\GovernmentVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GovernmentVerificationController extends Controller
{
    /**
     * Verify a document submission (Government API endpoint)
     */
    public function verifyDocument(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_id' => 'required|string',
            'government_id' => 'required|string',
            'api_key' => 'required|string',
            'verification_data' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify API key
        if (!$this->verifyApiKey($request->api_key, $request->government_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API key'
            ], 401);
        }

        try {
            $document = Document::where('reference_number', $request->document_id)->firstOrFail();

            $verification = GovernmentVerification::create([
                'document_id' => $document->id,
                'government_id' => $request->government_id,
                'verification_status' => 'verified',
                'verification_data' => $request->verification_data,
                'verified_at' => now(),
                'verified_by' => $request->government_id,
            ]);

            // Update document status
            $document->update([
                'government_verified' => true,
                'verification_date' => now(),
            ]);

            Log::info('Government verification successful', [
                'document_id' => $document->id,
                'government_id' => $request->government_id
            ]);

            return response()->json([
                'success' => true,
                'verification_id' => $verification->id,
                'document_id' => $document->reference_number,
                'status' => 'verified',
                'verified_at' => $verification->verified_at
            ]);

        } catch (\Exception $e) {
            Log::error('Government verification failed', [
                'error' => $e->getMessage(),
                'document_id' => $request->document_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Verification failed'
            ], 500);
        }
    }

    /**
     * Get document status (Government API endpoint)
     */
    public function getDocumentStatus(Request $request, $documentId)
    {
        $validator = Validator::make([
            'api_key' => $request->header('X-API-Key'),
            'government_id' => $request->header('X-Government-ID'),
        ], [
            'api_key' => 'required|string',
            'government_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify API key
        if (!$this->verifyApiKey($request->header('X-API-Key'), $request->header('X-Government-ID'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API key'
            ], 401);
        }

        try {
            $document = Document::where('reference_number', $documentId)->firstOrFail();

            return response()->json([
                'success' => true,
                'document_id' => $document->reference_number,
                'status' => $document->status,
                'government_verified' => $document->government_verified,
                'submitted_at' => $document->created_at,
                'completed_at' => $document->completed_at,
                'translator_id' => $document->translator_id,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Document not found'
            ], 404);
        }
    }

    /**
     * Verify API key
     */
    private function verifyApiKey($apiKey, $governmentId)
    {
        // Implement your API key verification logic here
        // This should check against a database of registered government entities
        return true; // Placeholder
    }

    /**
     * Get verification statistics
     */
    public function getStats(Request $request)
    {
        $validator = Validator::make([
            'api_key' => $request->header('X-API-Key'),
            'government_id' => $request->header('X-Government-ID'),
        ], [
            'api_key' => 'required|string',
            'government_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$this->verifyApiKey($request->header('X-API-Key'), $request->header('X-Government-ID'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API key'
            ], 401);
        }

        $governmentId = $request->header('X-Government-ID');

        $stats = [
            'total_verifications' => GovernmentVerification::where('government_id', $governmentId)->count(),
            'verified_today' => GovernmentVerification::where('government_id', $governmentId)
                ->whereDate('verified_at', today())
                ->count(),
            'verified_this_month' => GovernmentVerification::where('government_id', $governmentId)
                ->whereMonth('verified_at', now()->month)
                ->count(),
            'pending_verifications' => Document::whereHas('government', function($query) use ($governmentId) {
                $query->where('id', $governmentId);
            })->where('government_verified', false)->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}
