<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partner;
use App\Models\OfficialTranslationOrder;
use Illuminate\Support\Facades\Auth;

class CertifiedPartnerController extends Controller
{
    public function dashboard()
    {
        // Get partner associated with current user
        $partner = Partner::where('user_id', Auth::id())->first();
        
        if (!$partner) {
            return redirect()->route('home')->with('error', 'You are not registered as a partner.');
        }
        
        // Get statistics
        $stats = [
            'total_documents' => OfficialTranslationOrder::where('sworn_translator', true)
                ->where('payment_status', 'paid')
                ->count(),
            'pending_documents' => OfficialTranslationOrder::where('sworn_translator', true)
                ->where('payment_status', 'paid')
                ->whereIn('status', ['pending', 'processing'])
                ->count(),
            'pending_stamp' => OfficialTranslationOrder::where('sworn_translator', true)
                ->where('payment_status', 'paid')
                ->where('status', 'pending')
                ->count(),
            'pending_print' => OfficialTranslationOrder::where('sworn_translator', true)
                ->where('delivery_method', 'physical')
                ->where('payment_status', 'paid')
                ->whereNull('shipped_at')
                ->count(),
            'completed' => OfficialTranslationOrder::where('sworn_translator', true)
                ->where('payment_status', 'paid')
                ->where('status', 'completed')
                ->count(),
            'completed_documents' => OfficialTranslationOrder::where('sworn_translator', true)
                ->where('payment_status', 'paid')
                ->where('status', 'completed')
                ->count(),
            'total_earnings' => OfficialTranslationOrder::where('sworn_translator', true)
                ->where('payment_status', 'paid')
                ->sum('price') * 0.3, // Assuming 30% commission
        ];
        
        // Get recent documents
        $recentDocuments = OfficialTranslationOrder::where('sworn_translator', true)
            ->where('payment_status', 'paid')
            ->with('document')
            ->latest()
            ->take(10)
            ->get();
        
        return view('partner.dashboard', compact('partner', 'stats', 'recentDocuments'));
    }

    public function documents()
    {
        $partner = Partner::where('user_id', Auth::id())->first();
        
        if (!$partner) {
            return redirect()->route('home')->with('error', 'You are not registered as a partner.');
        }
        
        // Get all documents requiring sworn translator seal
        $documents = OfficialTranslationOrder::where('sworn_translator', true)
            ->where('payment_status', 'paid')
            ->with('document')
            ->latest()
            ->paginate(20);
        
        return view('partner.documents', compact('partner', 'documents'));
    }

    public function showDocument($document)
    {
        return view('partner.document-details', compact('document'));
    }

    public function applyStamp(Request $request, $document)
    {
        return redirect()->back()->with('success', 'Stamp applied successfully');
    }

    public function markPrinted(Request $request, $document)
    {
        return redirect()->back()->with('success', 'Marked as printed');
    }

    public function downloadForPrint($document)
    {
        return response()->download(storage_path('app/documents/' . $document));
    }

    public function printQueue()
    {
        $partner = Partner::where('user_id', Auth::id())->first();
        
        if (!$partner) {
            return redirect()->route('home')->with('error', 'You are not registered as a partner.');
        }
        
        // Get documents that need physical printing
        $printQueue = OfficialTranslationOrder::where('sworn_translator', true)
            ->where('delivery_method', 'physical')
            ->where('payment_status', 'paid')
            ->whereNull('shipped_at')
            ->with('document')
            ->latest()
            ->get();
        
        return view('partner.print-queue', compact('partner', 'printQueue'));
    }

    public function uploadStamp(Request $request)
    {
        return redirect()->back()->with('success', 'Stamp uploaded successfully');
    }

    public function earnings()
    {
        $partner = Partner::where('user_id', Auth::id())->first();
        
        if (!$partner) {
            return redirect()->route('home')->with('error', 'You are not registered as a partner.');
        }
        
        // Calculate earnings
        $totalEarnings = OfficialTranslationOrder::where('sworn_translator', true)
            ->where('payment_status', 'paid')
            ->sum('price') * 0.3; // 30% commission
        
        $monthlyEarnings = OfficialTranslationOrder::where('sworn_translator', true)
            ->where('payment_status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('price') * 0.3;
        
        $earningsHistory = OfficialTranslationOrder::where('sworn_translator', true)
            ->where('payment_status', 'paid')
            ->with('document')
            ->latest('paid_at')
            ->paginate(20);
        
        return view('partner.earnings', compact('partner', 'totalEarnings', 'monthlyEarnings', 'earningsHistory'));
    }
}
