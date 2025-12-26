<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\PdfCertificationService;
use App\Models\CertifiedTranslation;
use App\Models\ShippingOrder;
use App\Models\User;

class CertifiedTranslationController extends Controller
{
    protected $pdfService;

    public function __construct(PdfCertificationService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    /**
     * Display the certified translation page
     */
    public function index()
    {
        return view('certified-translation');
    }

    /**
     * Generate certified translation PDF
     */
    public function generate(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'document_type' => 'required|string',
                'source_language' => 'required|string|max:10',
                'target_language' => 'required|string|max:10',
                'delivery_method' => 'required|in:digital,physical',
                'original_pdf' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
                'shipping_address' => 'required_if:delivery_method,physical|array',
                'shipping_address.name' => 'required_if:delivery_method,physical|string|max:255',
                'shipping_address.phone' => 'required_if:delivery_method,physical|string|max:50',
                'shipping_address.street' => 'required_if:delivery_method,physical|string|max:255',
                'shipping_address.city' => 'required_if:delivery_method,physical|string|max:100',
                'shipping_address.state' => 'nullable|string|max:100',
                'shipping_address.zip' => 'required_if:delivery_method,physical|string|max:20',
                'shipping_address.country' => 'required_if:delivery_method,physical|string|max:100',
            ]);

            // Get authenticated user (or create guest user for demo)
            $user = auth()->user() ?? User::first(); // For demo purposes
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'Please login or register to use certified translation service.'
                ], 401);
            }

            // Store uploaded file
            $originalFile = $request->file('original_pdf');
            $originalFileName = time() . '_' . $originalFile->getClientOriginalName();
            $originalPath = $originalFile->storeAs('originals', $originalFileName, 'public');

            // Generate unique certificate ID
            $certificateId = 'CT-' . strtoupper(substr(md5(uniqid()), 0, 4)) . '-' . 
                             strtoupper(substr(md5(uniqid()), 0, 4)) . '-' . 
                             strtoupper(substr(md5(uniqid()), 0, 4));

            // Prepare translation data
            $translationData = [
                'certificate_id' => $certificateId,
                'document_type' => $validated['document_type'],
                'source_language' => $validated['source_language'],
                'target_language' => $validated['target_language'],
                'original_file_path' => $originalPath,
                'user_id' => $user->id,
                'delivery_method' => $validated['delivery_method'],
                'status' => 'processing',
            ];

            // Create certified translation record
            $certifiedTranslation = CertifiedTranslation::create($translationData);

            // Generate certified PDF
            $pdfData = [
                'certificate_id' => $certificateId,
                'document_title' => ucfirst(str_replace('_', ' ', $validated['document_type'])),
                'original_language' => $this->getLanguageName($validated['source_language']),
                'translated_language' => $this->getLanguageName($validated['target_language']),
                'translation_date' => now()->format('Y-m-d'),
                'translator_name' => 'Cultural Translate Certified Team',
                'partner_name' => 'Global Translation Partners Inc.',
                'partner_license' => 'GTP-2024-CERT-001',
            ];

            // Generate PDF using PdfCertificationService
            $pdfPath = $this->pdfService->generateCertifiedPdf(
                storage_path('app/public/' . $originalPath),
                $pdfData
            );

            // Store PDF path in database
            $relativePdfPath = str_replace(storage_path('app/public/'), '', $pdfPath);
            $certifiedTranslation->update([
                'certified_file_path' => $relativePdfPath,
                'status' => 'completed',
            ]);

            // Create shipping order if physical delivery
            if ($validated['delivery_method'] === 'physical') {
                $shippingOrder = ShippingOrder::create([
                    'certified_translation_id' => $certifiedTranslation->id,
                    'recipient_name' => $validated['shipping_address']['name'],
                    'phone' => $validated['shipping_address']['phone'],
                    'street_address' => $validated['shipping_address']['street'],
                    'city' => $validated['shipping_address']['city'],
                    'state' => $validated['shipping_address']['state'] ?? '',
                    'postal_code' => $validated['shipping_address']['zip'],
                    'country' => $validated['shipping_address']['country'],
                    'status' => 'pending',
                    'tracking_number' => null,
                ]);
            }

            // Generate URLs
            $downloadUrl = route('certified.download', ['id' => $certifiedTranslation->id]);
            $verificationUrl = route('verify.certificate', ['certificate_id' => $certificateId]);

            return response()->json([
                'success' => true,
                'certificate_id' => $certificateId,
                'download_url' => $downloadUrl,
                'verification_url' => $verificationUrl,
                'delivery_method' => $validated['delivery_method'],
                'message' => 'Certified translation generated successfully!',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed: ' . implode(', ', $e->errors()),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Certified translation generation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while generating your certified translation. Please try again or contact support.',
            ], 500);
        }
    }

    /**
     * Download certified PDF
     */
    public function download($id)
    {
        try {
            $certifiedTranslation = CertifiedTranslation::findOrFail($id);
            
            // Check authorization (simplified for demo)
            // In production, add proper authorization checks
            
            $filePath = storage_path('app/public/' . $certifiedTranslation->certified_file_path);
            
            if (!file_exists($filePath)) {
                abort(404, 'File not found');
            }

            $fileName = 'Certified_Translation_' . $certifiedTranslation->certificate_id . '.pdf';
            
            return response()->download($filePath, $fileName);
            
        } catch (\Exception $e) {
            Log::error('Download failed: ' . $e->getMessage());
            abort(404, 'Translation not found');
        }
    }

    /**
     * Verify certificate
     */
    public function verify($certificateId)
    {
        try {
            $certifiedTranslation = CertifiedTranslation::where('certificate_id', $certificateId)->first();
            
            if (!$certifiedTranslation) {
                return view('verify-certificate', [
                    'found' => false,
                    'certificate_id' => $certificateId,
                ]);
            }

            $shippingOrder = $certifiedTranslation->shippingOrder;

            return view('verify-certificate', [
                'found' => true,
                'certificate_id' => $certificateId,
                'document_type' => ucfirst(str_replace('_', ' ', $certifiedTranslation->document_type)),
                'source_language' => $this->getLanguageName($certifiedTranslation->source_language),
                'target_language' => $this->getLanguageName($certifiedTranslation->target_language),
                'translation_date' => $certifiedTranslation->created_at->format('Y-m-d'),
                'status' => $certifiedTranslation->status,
                'delivery_method' => $certifiedTranslation->delivery_method,
                'shipping_status' => $shippingOrder->status ?? null,
                'tracking_number' => $shippingOrder->tracking_number ?? null,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Verification failed: ' . $e->getMessage());
            
            return view('verify-certificate', [
                'found' => false,
                'certificate_id' => $certificateId,
                'error' => 'An error occurred during verification.',
            ]);
        }
    }

    /**
     * Get language name from code
     */
    private function getLanguageName($code)
    {
        // This should query your cultural_profiles table
        // For now, using a simple mapping
        $languages = [
            'en' => 'English',
            'ar' => 'Arabic (العربية)',
            'es' => 'Spanish (Español)',
            'fr' => 'French (Français)',
            'de' => 'German (Deutsch)',
            'zh' => 'Chinese (中文)',
            'ja' => 'Japanese (日本語)',
            'ko' => 'Korean (한국어)',
            'ru' => 'Russian (Русский)',
            'pt' => 'Portuguese (Português)',
            'it' => 'Italian (Italiano)',
            'nl' => 'Dutch (Nederlands)',
            'pl' => 'Polish (Polski)',
            'tr' => 'Turkish (Türkçe)',
            'hi' => 'Hindi (हिन्दी)',
        ];

        return $languages[$code] ?? ucfirst($code);
    }
}
