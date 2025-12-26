<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\DocumentAssignment;
use App\Services\AssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function __construct(private AssignmentService $assignmentService)
    {
    }

    /**
     * Display partner's assignments inbox
     */
    public function index(Request $request)
    {
        $partner = $request->user(); // Assuming partner authentication

        // Get active assignments (offered + accepted)
        $assignments = DocumentAssignment::query()
            ->where('partner_id', $partner->id)
            ->whereIn('status', ['offered', 'accepted'])
            ->with(['document' => function ($q) {
                $q->select('id', 'document_type', 'source_lang', 'target_lang', 'jurisdiction_country', 'status');
            }])
            ->orderByRaw("FIELD(status, 'offered', 'accepted')")
            ->orderByDesc('created_at')
            ->paginate(20);

        // Get recent completed/rejected
        $recentHistory = DocumentAssignment::query()
            ->where('partner_id', $partner->id)
            ->whereIn('status', ['completed', 'rejected', 'timed_out', 'lost'])
            ->with(['document' => function ($q) {
                $q->select('id', 'document_type', 'status');
            }])
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get();

        return view('partner.assignments.index', compact('assignments', 'recentHistory'));
    }

    /**
     * Accept an assignment
     */
    public function accept(Request $request, int $assignmentId)
    {
        $partner = $request->user();

        try {
            $result = $this->assignmentService->acceptAssignment($assignmentId, $partner->id, $request);

            $status = $result['status'] ?? 'unknown';
            $message = $result['message'] ?? 'Unknown result';

            if ($status === 'accepted') {
                return back()->with('success', $message);
            } elseif ($status === 'lost') {
                return back()->with('warning', $message);
            } elseif ($status === 'expired') {
                return back()->with('info', $message);
            } else {
                return back()->with('info', $message);
            }
        } catch (\Exception $e) {
            \Log::error('Assignment accept error', [
                'assignment_id' => $assignmentId,
                'partner_id' => $partner->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to accept assignment. Please try again.');
        }
    }

    /**
     * Reject an assignment
     */
    public function reject(Request $request, int $assignmentId)
    {
        $partner = $request->user();

        $request->validate([
            'reason' => 'required|string|in:too_busy,not_my_specialization,conflict_of_interest,document_unreadable,other',
        ]);

        $reason = $request->input('reason', 'other');

        try {
            $result = $this->assignmentService->rejectAssignment($assignmentId, $partner->id, $reason, $request);

            $message = $result['message'] ?? 'Assignment rejected';

            return back()->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Assignment reject error', [
                'assignment_id' => $assignmentId,
                'partner_id' => $partner->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to reject assignment. Please try again.');
        }
    }

    /**
     * Mark assignment as completed (after review is done)
     */
    public function complete(Request $request, int $assignmentId)
    {
        $partner = $request->user();

        try {
            $result = $this->assignmentService->completeAssignment($assignmentId, $partner->id);

            $message = $result['message'] ?? 'Assignment completed';

            return back()->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Assignment complete error', [
                'assignment_id' => $assignmentId,
                'partner_id' => $partner->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to complete assignment. Please try again.');
        }
    }

    /**
     * Show assignment details
     */
    public function show(Request $request, int $assignmentId)
    {
        $partner = $request->user();

        $assignment = DocumentAssignment::query()
            ->where('id', $assignmentId)
            ->where('partner_id', $partner->id)
            ->with(['document', 'partner'])
            ->firstOrFail();

        return view('partner.assignments.show', compact('assignment'));
    }
}
