<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OfficialDocument;
use App\Jobs\ProcessOfficialDocumentTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DashboardOfficialDocumentController extends Controller
{
    /**
     * Estimate cost for document translation
     */
    public function estimate(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
                'source_language' => 'required|string',
                'target_language' => 'required|string'
            ]);

            $file = $request->file('file');
            $fileSize = $file->getSize();
            
            // Estimate: 50KB per page, 500 words per page, $0.05 per word
            $estimatedPages = max(1, ceil($fileSize / 51200));
            $estimatedWords = $estimatedPages * 500;
            $estimatedCost = number_format($estimatedWords * 0.05, 2, '.', '');

            // Store file temporarily
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('documents/originals', $filename);

            // Create document record
            $document = OfficialDocument::create([
                'user_id' => auth()->id(),
                'original_filename' => $file->getClientOriginalName(),
                'stored_filename' => $filename,
                'file_path' => $path,
                'file_size' => $fileSize,
                'source_language' => $request->source_language,
                'document_type' => $request->document_type ?? 'other',
                'target_language' => $request->target_language,
                'estimated_pages' => $estimatedPages,
                'estimated_words' => $estimatedWords,
                'estimated_cost' => $estimatedCost,
                'status' => 'pending',
                'certificate_id' => 'CT-' . date('Y-m-') . strtoupper(Str::random(8))
            ]);

            return response()->json([
                'success' => true,
                'document_id' => $document->id,
                'estimated_pages' => $estimatedPages,
                'estimated_words' => $estimatedWords,
                'estimated_cost' => $estimatedCost,
                'message' => 'Cost estimated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Estimation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to estimate cost: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create Stripe payment session
     */
    public function createPayment(Request $request)
    {
        try {
            $request->validate([
                'document_id' => 'required|exists:official_documents,id'
            ]);

            $document = OfficialDocument::where('id', $request->document_id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            if ($document->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Document is not in pending status'
                ], 400);
            }

            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Official Document Translation',
                            'description' => 'Translation of ' . $document->original_filename,
                        ],
                        'unit_amount' => intval($document->estimated_cost * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('dashboard.official-documents.success', ['document_id' => $document->id]),
                'cancel_url' => route('dashboard.official-documents.cancel', ['document_id' => $document->id]),
                'metadata' => [
                    'document_id' => $document->id,
                    'user_id' => auth()->id(),
                    'type' => 'official_document_translation'
                ]
            ]);

            $document->update([
                'stripe_session_id' => $session->id
            ]);

            return response()->json([
                'success' => true,
                'checkout_url' => $session->url,
                'session_id' => $session->id
            ]);

        } catch (\Exception $e) {
            Log::error('Payment creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle successful payment
     */
    public function paymentSuccess(Request $request)
    {
        try {
            $documentId = $request->query('document_id');
            $document = OfficialDocument::where('id', $documentId)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            // Verify payment with Stripe
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $session = \Stripe\Checkout\Session::retrieve($document->stripe_session_id);

            if ($session->payment_status === 'paid') {
                $document->update([
                    'status' => 'processing',
                    'payment_status' => 'paid',
                    'amount' => $document->estimated_cost,
                    'paid_at' => now()
                ]);

                // Dispatch translation job (asynchronous for registered users)
                ProcessOfficialDocumentTranslation::dispatch($document->id);

                return redirect()->route('dashboard')
                    ->with('success', 'Payment successful! Your document is being translated. You will be notified when it\'s ready.')
                    ->with('document_id', $document->id);
            }

            return redirect()->route('dashboard')
                ->with('error', 'Payment verification failed. Please contact support.');

        } catch (\Exception $e) {
            Log::error('Payment success handler error: ' . $e->getMessage());
            return redirect()->route('dashboard')
                ->with('error', 'An error occurred. Please contact support.');
        }
    }

    /**
     * Handle cancelled payment
     */
    public function paymentCancel(Request $request)
    {
        $documentId = $request->query('document_id');
        
        return redirect()->route('dashboard')
            ->with('error', 'Payment was cancelled. Your document has not been processed.');
    }

    /**
     * Get user's documents
     */
    public function myDocuments(Request $request)
    {
        try {
            $perPage = 10;
            $page = $request->query('page', 1);

            $documents = OfficialDocument::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'documents' => $documents->items(),
                'current_page' => $documents->currentPage(),
                'total_pages' => $documents->lastPage(),
                'total' => $documents->total()
            ]);

        } catch (\Exception $e) {
            Log::error('Load documents error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load documents'
            ], 500);
        }
    }

    /**
     * Download certified document
     */
    public function download($id)
    {
        try {
            $document = OfficialDocument::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            if ($document->status !== 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Document is not ready for download yet'
                ], 400);
            }

            if (!$document->certified_path || !Storage::exists($document->certified_path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Certified document file not found'
                ], 404);
            }

            $filePath = Storage::path($document->certified_path);
            $filename = 'certified_' . pathinfo($document->original_filename, PATHINFO_FILENAME) . '.pdf';

            // Force download with proper headers
            return response()->download($filePath, $filename, [
                'Content-Type' => 'application/pdf',
            ]);

        } catch (\Exception $e) {
            Log::error('Download error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to download document'
            ], 500);
        }
    }

    /**
     * Get document details
     */
    public function show($id)
    {
        try {
            $document = OfficialDocument::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'document' => $document
            ]);

        } catch (\Exception $e) {
            Log::error('Show document error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Document not found'
            ], 404);
        }
    }
}
