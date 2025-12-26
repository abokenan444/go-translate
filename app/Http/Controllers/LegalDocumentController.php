<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LegalDocumentController extends Controller
{
    /**
     * رفع الملف وحساب التكلفة
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file'            => 'required|file|mimes:pdf,doc,docx|max:10240',
            'source_language' => 'required|string',
            'target_language' => 'required|string',
            'document_type'   => 'required|string',
        ]);

        try {
            $file = $request->file('file');

            // نخزن الملف على الـ disk local
            $path = $file->store('legal-documents/original', 'local');

            // تقدير عدد الصفحات باستخدام المسار الصحيح
            $pdfProcessor = app(\App\Services\PDFProcessor::class);
            $fullPath     = Storage::disk('local')->path($path);
            $pages        = max(1, $pdfProcessor->estimatePages($fullPath));
            $words        = $pages * 250;
            $pricePerPage = 5.00;
            $totalCost    = $pages * $pricePerPage;

            session([
                'legal_doc_upload' => [
                    'file_path'       => $path,
                    'original_name'   => $file->getClientOriginalName(),
                    'source_language' => $request->source_language,
                    'target_language' => $request->target_language,
                    'document_type'   => $request->document_type,
                    'pages'           => $pages,
                    'words'           => $words,
                    'price_per_page'  => $pricePerPage,
                    'total_cost'      => $totalCost,
                ],
            ]);

            return response()->json([
                'success'        => true,
                'pages'          => $pages,
                'words'          => $words,
                'price_per_page' => $pricePerPage,
                'total_cost'     => $totalCost,
            ]);
        } catch (\Exception $e) {
            Log::error('Legal document upload error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload document: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * معالجة الدفع عبر Stripe
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|string',
        ]);

        try {
            $sessionData = session('legal_doc_upload');

            if (!$sessionData) {
                return response()->json([
                    'success' => false,
                    'message' => 'No upload session found',
                ], 400);
            }

            // معالجة الدفع عبر Stripe
            $stripeService = app(\App\Services\StripeService::class);
            $paymentIntent = $stripeService->createPaymentIntent(
                $sessionData['total_cost'] * 100, // Convert to cents
                'usd',
                $request->payment_method_id,
                [
                    'description' => 'Legal Document Translation: ' . $sessionData['original_name'],
                    'metadata' => [
                        'document_type' => $sessionData['document_type'],
                        'pages' => $sessionData['pages'],
                    ],
                ]
            );

            if ($paymentIntent->status === 'succeeded') {
                // حفظ معلومات الدفع
                session([
                    'legal_doc_payment' => [
                        'payment_intent_id' => $paymentIntent->id,
                        'amount' => $sessionData['total_cost'],
                        'status' => 'succeeded',
                    ],
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment successful',
                    'payment_intent_id' => $paymentIntent->id,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment failed',
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Legal document payment error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * ترجمة الوثيقة وإضافة الختم والباركود
     */
    public function translate(Request $request)
    {
        try {
            $sessionData = session('legal_doc_upload');

            if (!$sessionData) {
                return response()->json([
                    'success' => false,
                    'message' => 'No upload session found',
                ], 400);
            }

            // المسار الصحيح باستخدام Storage facade
            $originalPath = Storage::disk('local')->path($sessionData['file_path']);

            if (!file_exists($originalPath)) {
                Log::error('PDF file not found: ' . $originalPath);

                return response()->json([
                    'success' => false,
                    'message' => 'Original file not found at: ' . $originalPath,
                ], 404);
            }

            // استخراج النص من PDF
            $pdfProcessor = app(\App\Services\PDFProcessor::class);
            $text = $pdfProcessor->extractText($originalPath);

            // ترجمة النص
            $translationService = app(\App\Services\TranslationService::class);
            $translatedText = $translationService->translateDocument(
                $text,
                $sessionData['source_language'],
                $sessionData['target_language']
            );

            // إنشاء tracking number فريد
            $trackingNumber = 'LEGAL-' . strtoupper(Str::random(12));

            // إنشاء PDF مترجم مع ختم وباركود
            $translatedRelative = 'legal-documents/translated/' . $trackingNumber . '.pdf';

            // التأكد من وجود المجلد
            Storage::disk('local')->makeDirectory('legal-documents/translated');

            // المسار الكامل للملف المترجم
            $translatedFull = Storage::disk('local')->path($translatedRelative);

            // إنشاء PDF مترجم مع الختم والباركود
            $pdfProcessor->createTranslatedPDF(
                $originalPath,
                $translatedFull,
                $translatedText,
                $trackingNumber,
                [
                    'document_type' => $sessionData['document_type'],
                    'source_language' => $sessionData['source_language'],
                    'target_language' => $sessionData['target_language'],
                    'translation_date' => now()->format('Y-m-d H:i:s'),
                    'translator' => 'Cultural Translate Platform',
                ]
            );

            // حفظ معلومات الترجمة
            session([
                'legal_doc_translated' => [
                    'translated_path' => $translatedRelative,
                    'translated_filename' => $trackingNumber . '.pdf',
                    'tracking_number' => $trackingNumber,
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document translated successfully',
                'tracking_number' => $trackingNumber,
                'download_url' => route('legal-documents.download', ['trackingNumber' => $trackingNumber]),
            ]);
        } catch (\Exception $e) {
            Log::error('Legal document translation error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Translation failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * تحميل الوثيقة المترجمة
     */
    public function download(string $trackingNumber)
    {
        $path = 'legal-documents/translated/' . $trackingNumber . '.pdf';

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'Translated document not found');
        }

        return Storage::disk('local')->download($path, $trackingNumber . '.pdf');
    }
}
