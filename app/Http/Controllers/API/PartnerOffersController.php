<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DocumentAssignment;
use App\Models\PartnerMetric;
use App\Services\Notifications\Push\FCMPushService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartnerOffersController extends Controller
{
    public function __construct(protected FCMPushService $fcm)
    {
    }

    /**
     * List pending offers for authenticated partner
     */
    public function index(Request $request)
    {
        $partner = $request->user()->partnerProfile;
        
        if (!$partner) {
            return response()->json(['message' => 'Not authorized as partner'], 403);
        }

        $offers = DocumentAssignment::with('officialDocument')
            ->where('partner_profile_id', $partner->id)
            ->where('status', 'pending')
            ->where('accept_deadline', '>', now())
            ->orderBy('offered_at')
            ->get()
            ->map(fn($a) => [
                'id' => $a->id,
                'document_id' => $a->official_document_id,
                'document_type' => $a->officialDocument->document_type ?? null,
                'expires_at' => $a->accept_deadline?->toIso8601String(),
                'offered_at' => $a->offered_at?->toIso8601String(),
                'remaining_seconds' => $a->accept_deadline ? now()->diffInSeconds($a->accept_deadline, false) : 0,
            ]);

        return response()->json(['offers' => $offers]);
    }

    /**
     * Accept an offer (atomic with race condition handling)
     */
    public function accept(Request $request, DocumentAssignment $assignment)
    {
        $partner = $request->user()->partnerProfile;
        
        if (!$partner || $assignment->partner_profile_id !== $partner->id) {
            return response()->json(['message' => 'Not authorized'], 403);
        }

        return DB::transaction(function () use ($assignment, $partner) {
            // Lock row
            $assignment = DocumentAssignment::where('id', $assignment->id)
                ->lockForUpdate()
                ->first();

            if (!$assignment) {
                return response()->json(['message' => 'Offer not found'], 404);
            }

            if ($assignment->status !== 'pending') {
                return response()->json([
                    'message' => 'Offer already ' . $assignment->status,
                    'status' => $assignment->status,
                ], 409);
            }

            if ($assignment->accept_deadline && $assignment->accept_deadline->isPast()) {
                $assignment->update(['status' => 'expired']);
                return response()->json(['message' => 'Offer expired'], 410);
            }

            // Accept
            $assignment->update([
                'status' => 'accepted',
                'accepted_at' => now(),
            ]);

            // Cancel other pending offers for same document
            DocumentAssignment::where('official_document_id', $assignment->official_document_id)
                ->where('id', '!=', $assignment->id)
                ->where('status', 'pending')
                ->update(['status' => 'cancelled']);

            // Update metrics
            $metric = PartnerMetric::firstOrCreate(
                ['partner_profile_id' => $partner->id],
                ['quality_score' => 5.0, 'sla_score' => 5.0]
            );

            $acceptMinutes = $assignment->offered_at ? $assignment->offered_at->diffInMinutes(now()) : 0;
            
            if ($metric->avg_accept_minutes) {
                $metric->avg_accept_minutes = ($metric->avg_accept_minutes + $acceptMinutes) / 2;
            } else {
                $metric->avg_accept_minutes = $acceptMinutes;
            }
            
            $metric->save();

            return response()->json([
                'message' => 'Offer accepted',
                'assignment_id' => $assignment->id,
                'accepted_at' => $assignment->accepted_at->toIso8601String(),
            ]);
        });
    }

    /**
     * Decline an offer
     */
    public function decline(Request $request, DocumentAssignment $assignment)
    {
        $partner = $request->user()->partnerProfile;
        
        if (!$partner || $assignment->partner_profile_id !== $partner->id) {
            return response()->json(['message' => 'Not authorized'], 403);
        }

        return DB::transaction(function () use ($assignment, $partner, $request) {
            $assignment = DocumentAssignment::where('id', $assignment->id)
                ->lockForUpdate()
                ->first();

            if (!$assignment || $assignment->status !== 'pending') {
                return response()->json(['message' => 'Offer not available'], 410);
            }

            $assignment->update([
                'status' => 'declined',
                'declined_at' => now(),
                'decline_reason' => $request->input('reason'),
            ]);

            // Update metrics
            $metric = PartnerMetric::firstOrCreate(
                ['partner_profile_id' => $partner->id],
                ['quality_score' => 5.0, 'sla_score' => 5.0]
            );
            $metric->increment('jobs_rejected');

            // Re-offer document
            app(\App\Services\Assignments\AssignmentEngineService::class)
                ->offer($assignment->officialDocument);

            return response()->json(['message' => 'Offer declined']);
        });
    }
}
