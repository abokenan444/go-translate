<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\ContextAnalysisEngine;
use App\Services\BrandVoiceEngine;
use App\Services\CertificateGenerationService;
use App\Services\QRCodeVerificationService;
use App\Services\PDFStampingService;
use App\Services\PartnerWorkflowService;

class OfficialDocumentController extends Controller
{
    protected $contextEngine;
    protected $brandVoiceEngine;
    protected $certificateService;
    protected $qrService;
    protected $pdfStampingService;
    protected $partnerWorkflow;

    public function __construct(
        ContextAnalysisEngine $contextEngine,
        BrandVoiceEngine $brandVoiceEngine,
        CertificateGenerationService $certificateService,
        QRCodeVerificationService $qrService,
        PDFStampingService $pdfStampingService,
        PartnerWorkflowService $partnerWorkflow
    ) {
        $this->contextEngine = $contextEngine;
        $this->brandVoiceEngine = $brandVoiceEngine;
        $this->certificateService = $certificateService;
        $this->qrService = $qrService;
        $this->pdfStampingService = $pdfStampingService;
        $this->partnerWorkflow = $partnerWorkflow;
    }

    /**
     * Display a listing of official documents.
     */
    public function index()
    {
        $documentTypes = config('pdf_translation.document_types', config('official_documents.document_types', [
            'passport' => 'Passport',
            'birth_certificate' => 'Birth Certificate',
            'marriage_certificate' => 'Marriage Certificate',
            'diploma' => 'Educational Diploma',
            'transcript' => 'Academic Transcript',
            'contract' => 'Legal Contract',
            'other' => 'Other Official Document',
        ]));

        $pricing = [
            'default_price' => (float) env('OFFICIAL_DOCUMENT_DEFAULT_PRICE', 10.00),
            'currency' => (string) env('OFFICIAL_DOCUMENT_CURRENCY', 'EUR'),
        ];

        return view('official-documents.index', compact('documentTypes', 'pricing'));
    }

    /**
     * Display user's documents.
     */
    public function myDocuments()
    {
        $user = Auth::user();
        
        // Get user's documents from database
        // This is a placeholder - adjust based on your actual model
        $documents = collect([]); // Replace with: $user->officialDocuments()->latest()->get();
        
        return view('official-documents.my-documents', compact('documents'));
    }

    /**
     * Show upload form.
     */
    public function create()
    {
        $documentTypes = config('pdf_translation.document_types', config('official_documents.document_types', [
            'passport' => 'Passport',
            'birth_certificate' => 'Birth Certificate',
            'marriage_certificate' => 'Marriage Certificate',
            'diploma' => 'Educational Diploma',
            'transcript' => 'Academic Transcript',
            'contract' => 'Legal Contract',
            'other' => 'Other Official Document',
        ]));

        $languages = config('pdf_translation.supported_languages', config('languages.supported', []));

        return view('official-documents.upload', compact('documentTypes', 'languages'));
    }

    /**
     * Show upload form (alternative).
     */
    public function uploadForm()
    {
        return $this->create();
    }

    /**
     * Handle document upload and create order
     */
    public function store(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'document_type' => 'required|string|max:255',
            'source_language' => 'required|string',
            'target_language' => 'required|string',
            'description' => 'nullable|string|max:1000',
            'brand_voice_id' => 'nullable|exists:brand_voices,id',
            'assign_to_partner' => 'nullable|boolean',
            'partner_id' => 'nullable|exists:partner_profiles,id',
            'priority' => 'nullable|in:normal,high,urgent'
        ]);

        $user = Auth::user();
        $file = $request->file('document');
        
        // Generate unique filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        
        // Store file temporarily
        $path = $file->storeAs('official-documents/pending/' . $user->id, $filename, 'private');
        
        // Calculate pricing
        $basePrice = (float) env('OFFICIAL_DOCUMENT_DEFAULT_PRICE', 10.00); // Base price in EUR
        $urgencyMultiplier = match($request->priority ?? 'normal') {
            'urgent' => 2.0,
            'high' => 1.5,
            'normal' => 1.0,
            default => 1.0
        };
        
        $totalPrice = $basePrice * $urgencyMultiplier;
        
        // Create order in session (or database if you have orders table)
        $orderId = 'ORD-' . strtoupper(Str::random(10));
        session([
            "order_{$orderId}" => [
                'id' => $orderId,
                'user_id' => $user->id,
                'document_type' => $request->document_type,
                'description' => $request->description,
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'source_language' => $request->source_language,
                'target_language' => $request->target_language,
                'brand_voice_id' => $request->brand_voice_id,
                'assign_to_partner' => $request->assign_to_partner ?? false,
                'partner_id' => $request->partner_id,
                'priority' => $request->priority ?? 'normal',
                'base_price' => $basePrice,
                'urgency_multiplier' => $urgencyMultiplier,
                'total_price' => $totalPrice,
                'currency' => 'EUR',
                'status' => 'pending_payment',
                'created_at' => now()->toDateTimeString()
            ]
        ]);

        // Redirect to checkout
        return redirect()->route('official.documents.checkout', ['order' => $orderId]);
    }

    /**
     * Extract text from document
     */
    protected function extractTextFromDocument(string $path): string
    {
        // Placeholder - implement with PDF parser or OCR
        // For now, return placeholder text
        return "Document content placeholder. Implement PDF/OCR extraction here.";
    }

    /**
     * Perform translation with context
     */
    protected function performTranslation(
        string $text,
        string $sourceLanguage,
        string $targetLanguage,
        array $contextAnalysis
    ): string {
        // Placeholder - integrate with OpenAI or your translation service
        // Use context analysis to improve translation quality
        
        $domain = $contextAnalysis['layer_1_domain']['primary_domain'] ?? 'general';
        $formality = $contextAnalysis['layer_2_formality']['formality_level'] ?? 'neutral';
        
        // For now, return placeholder
        return "Translated text with context (Domain: {$domain}, Formality: {$formality})";
    }

    /**
     * Download a document.
     */
    public function download($id)
    {
        // Placeholder - implement based on your OfficialDocument model
        // $document = OfficialDocument::findOrFail($id);
        // 
        // // Check authorization
        // if ($document->user_id !== Auth::id()) {
        //     abort(403);
        // }
        // 
        // return Storage::disk('private')->download($document->file_path, $document->original_name);
        
        abort(404, 'Document not found');
    }

    /**
     * Show checkout page
     */
    public function checkout($orderId)
    {
        $order = session("order_{$orderId}");
        
        if (!$order || $order['user_id'] !== Auth::id()) {
            return redirect()->route('official.documents.index')
                ->with('error', 'Order not found or unauthorized');
        }

        $documentTypes = config('pdf_translation.document_types', config('official_documents.document_types', []));
        $documentTypeName = $documentTypes[$order['document_type']] ?? $order['document_type'];
        $currency = (string)($order['currency'] ?? env('OFFICIAL_DOCUMENT_CURRENCY', 'EUR'));
        $totalPrice = (float)($order['total_price'] ?? 0);

        $orderView = (object) [
            'id' => $order['id'],
            'formatted_price' => number_format($totalPrice, 2) . ' ' . $currency,
            'document' => (object) [
                'document_type_name' => $documentTypeName,
                'source_language' => (string)($order['source_language'] ?? ''),
                'target_language' => (string)($order['target_language'] ?? ''),
            ],
        ];

        return view('official-documents.checkout', ['order' => $orderView]);
    }

    /**
     * Create Stripe checkout session
     */
    public function createCheckoutSession($orderId)
    {
        $order = session("order_{$orderId}");
        
        if (!$order || $order['user_id'] !== Auth::id()) {
            return back()->with('error', 'Order not found or unauthorized');
        }
        
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Official Document Translation',
                            'description' => $order['document_type'] . ' - ' . $order['source_language'] . ' to ' . $order['target_language'],
                        ],
                        'unit_amount' => (int)($order['total_price'] * 100), // Convert to cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('official.documents.payment.success', ['order' => $orderId]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('official.documents.checkout', ['order' => $orderId]),
                'client_reference_id' => $orderId,
                'metadata' => [
                    'order_id' => $orderId,
                    'user_id' => Auth::id(),
                    'document_type' => $order['document_type'],
                ]
            ]);
            
            return redirect($session->url);
            
        } catch (\Exception $e) {
            Log::error('Stripe checkout error: ' . $e->getMessage());
            return back()->with('error', 'Payment processing error. Please try again.');
        }
    }

    /**
     * Handle successful payment
     */
    public function paymentSuccess($orderId, Request $request)
    {
        $sessionId = $request->get('session_id');
        $order = session("order_{$orderId}");
        
        if (!$order || $order['user_id'] !== Auth::id()) {
            return redirect()->route('official.documents.index')
                ->with('error', 'Order not found');
        }
        
        try {
            // Verify payment with Stripe
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            
            if ($session->payment_status === 'paid') {
                // Move file from pending to active
                $newPath = str_replace('/pending/', '/', $order['file_path']);
                Storage::disk('private')->move($order['file_path'], $newPath);
                
                // Update order status
                $order['status'] = 'paid';
                $order['payment_id'] = $session->payment_intent;
                $order['file_path'] = $newPath;
                session(["order_{$orderId}" => $order]);
                
                // Here you would save to database and start processing
                // Process translation in background job
                
                return view('official-documents.success', compact('order'));
            }
        } catch (\Exception $e) {
            Log::error('Payment verification error: ' . $e->getMessage());
        }
        
        return redirect()->route('official.documents.index')
            ->with('error', 'Payment verification failed');
    }

    /**
     * Delete a document.
     */
    public function destroy($id)
    {
        // Placeholder - implement based on your OfficialDocument model
        // $document = OfficialDocument::findOrFail($id);
        // 
        // // Check authorization
        // if ($document->user_id !== Auth::id()) {
        //     abort(403);
        // }
        // 
        // // Delete file
        // Storage::disk('private')->delete($document->file_path);
        // 
        // // Delete record
        // $document->delete();
        
        return back()->with('success', 'Document deleted successfully!');
    }
}
