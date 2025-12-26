<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DocumentDispute;
use App\Models\EvidenceChain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisputeController extends Controller
{
    public function index()
    {
        $disputes = DocumentDispute::where('raised_by', Auth::id())
            ->with(['document', 'assignee'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('customer.disputes.index', compact('disputes'));
    }

    public function show($id)
    {
        $dispute = DocumentDispute::where('raised_by', Auth::id())
            ->with(['document', 'raiser', 'assignee'])
            ->findOrFail($id);

        $evidenceChain = EvidenceChain::where('document_id', $dispute->document_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.disputes.show', compact('dispute', 'evidenceChain'));
    }

    public function create(Request $request)
    {
        $documentId = $request->get('document_id');
        
        return view('customer.disputes.create', compact('documentId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_id' => 'required|exists:documents,id',
            'type' => 'required|in:quality,accuracy,formatting,cultural_sensitivity,deadline,other',
            'priority' => 'required|in:low,medium,high,critical',
            'description' => 'required|string|max:65535',
            'evidence_files' => 'nullable|array',
            'evidence_files.*' => 'file|max:10240', // 10MB max per file
        ]);

        $validated['raised_by'] = Auth::id();
        $validated['status'] = 'open';

        if ($request->hasFile('evidence_files')) {
            $files = [];
            foreach ($request->file('evidence_files') as $file) {
                $files[] = $file->store('disputes/evidence', 'private');
            }
            $validated['evidence_files'] = $files;
        }

        $dispute = DocumentDispute::create($validated);

        // Create evidence chain entry
        EvidenceChain::create([
            'document_id' => $validated['document_id'],
            'action_type' => 'dispute_opened',
            'performed_by' => Auth::id(),
            'details' => "Dispute opened: {$validated['type']}",
            'ip_address' => $request->ip(),
            'metadata' => [
                'dispute_id' => $dispute->id,
                'priority' => $validated['priority'],
            ],
        ]);

        return redirect()->route('customer.disputes.show', $dispute)
            ->with('success', __('Dispute submitted successfully. Our team will review it shortly.'));
    }
}
