<?php

namespace App\Http\Controllers;

use App\Models\OfficialDocument;
use App\Services\CertifiedDocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ShippingController extends Controller
{
    protected $certifiedDocService;

    public function __construct(CertifiedDocumentService $certifiedDocService)
    {
        $this->middleware('auth');
        $this->certifiedDocService = $certifiedDocService;
    }

    /**
     * Display shipping dashboard for admin
     */
    public function index()
    {
        $documents = OfficialDocument::with(['user', 'certifiedPartner'])
            ->where('physical_copy_requested', true)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'pending' => OfficialDocument::where('shipping_status', 'pending')->count(),
            'processing' => OfficialDocument::where('shipping_status', 'processing')->count(),
            'printed' => OfficialDocument::where('shipping_status', 'printed')->count(),
            'shipped' => OfficialDocument::where('shipping_status', 'shipped')->count(),
            'delivered' => OfficialDocument::where('shipping_status', 'delivered')->count(),
        ];

        return view('admin.shipping.index', compact('documents', 'stats'));
    }

    /**
     * Show shipping details for specific document
     */
    public function show(OfficialDocument $document)
    {
        $document->load(['user', 'certifiedPartner']);
        
        return view('admin.shipping.show', compact('document'));
    }

    /**
     * Update shipping status
     */
    public function updateStatus(Request $request, OfficialDocument $document)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,printed,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $document->update([
                'shipping_status' => $request->status,
                'tracking_number' => $request->tracking_number,
            ]);

            if ($request->status === 'shipped' && $request->tracking_number) {
                $this->certifiedDocService->processShipping($document, $request->tracking_number);
            }

            if ($request->status === 'delivered') {
                $this->certifiedDocService->markAsDelivered($document);
            }

            Log::info('Shipping status updated', [
                'document_id' => $document->id,
                'status' => $request->status,
                'updated_by' => Auth::id(),
            ]);

            return redirect()->back()->with('success', 'Shipping status updated successfully');

        } catch (\Exception $e) {
            Log::error('Failed to update shipping status', [
                'document_id' => $document->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Failed to update shipping status');
        }
    }

    /**
     * Mark document as printed
     */
    public function markPrinted(OfficialDocument $document)
    {
        try {
            $this->certifiedDocService->markAsPrinted($document);

            return redirect()->back()->with('success', 'Document marked as printed successfully');

        } catch (\Exception $e) {
            Log::error('Failed to mark document as printed', [
                'document_id' => $document->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Failed to mark document as printed');
        }
    }

    /**
     * Customer tracking page
     */
    public function track(Request $request)
    {
        $document = null;
        
        if ($request->has('certificate_id')) {
            $document = OfficialDocument::where('certificate_id', $request->certificate_id)
                ->where('physical_copy_requested', true)
                ->first();
        }

        return view('shipping.track', compact('document'));
    }

    /**
     * Get shipping timeline for document
     */
    public function timeline(OfficialDocument $document)
    {
        $timeline = [];

        if ($document->created_at) {
            $timeline[] = [
                'status' => 'ordered',
                'label' => 'Order Placed',
                'date' => $document->created_at,
                'completed' => true,
            ];
        }

        if ($document->paid_at) {
            $timeline[] = [
                'status' => 'paid',
                'label' => 'Payment Confirmed',
                'date' => $document->paid_at,
                'completed' => true,
            ];
        }

        if ($document->printed_at) {
            $timeline[] = [
                'status' => 'printed',
                'label' => 'Document Printed',
                'date' => $document->printed_at,
                'completed' => true,
            ];
        }

        if ($document->shipped_at) {
            $timeline[] = [
                'status' => 'shipped',
                'label' => 'Shipped',
                'date' => $document->shipped_at,
                'completed' => true,
            ];
        }

        if ($document->delivered_at) {
            $timeline[] = [
                'status' => 'delivered',
                'label' => 'Delivered',
                'date' => $document->delivered_at,
                'completed' => true,
            ];
        }

        return response()->json($timeline);
    }

    /**
     * Calculate shipping cost
     */
    public function calculateCost(Request $request)
    {
        $request->validate([
            'pages' => 'required|integer|min:1',
            'express' => 'boolean',
        ]);

        $cost = $this->certifiedDocService->calculatePhysicalCopyPrice(
            $request->pages,
            $request->boolean('express')
        );

        return response()->json([
            'cost' => $cost,
            'formatted' => '$' . number_format($cost, 2),
        ]);
    }

    /**
     * Request physical copy for existing document
     */
    public function requestPhysicalCopy(Request $request, OfficialDocument $document)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'express_shipping' => 'boolean',
        ]);

        try {
            $pages = $document->estimated_pages ?? 1;
            $physicalCopyPrice = $this->certifiedDocService->calculatePhysicalCopyPrice(
                $pages,
                $request->boolean('express_shipping')
            );

            $document->update([
                'physical_copy_requested' => true,
                'physical_copy_price' => $physicalCopyPrice,
                'shipping_address' => $request->shipping_address,
                'shipping_status' => 'pending',
            ]);

            Log::info('Physical copy requested', [
                'document_id' => $document->id,
                'user_id' => Auth::id(),
                'price' => $physicalCopyPrice,
            ]);

            return redirect()->back()->with('success', 'Physical copy requested successfully. Price: $' . number_format($physicalCopyPrice, 2));

        } catch (\Exception $e) {
            Log::error('Failed to request physical copy', [
                'document_id' => $document->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Failed to request physical copy');
        }
    }
}
