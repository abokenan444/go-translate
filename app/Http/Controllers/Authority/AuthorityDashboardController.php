<?php

namespace App\Http\Controllers\Authority;

use App\Http\Controllers\Controller;
use App\Services\DecisionLedgerService;
use App\Services\GovernmentAccessService;
use App\Models\OfficialDocument;
use App\Models\DocumentCertificate;
use App\Models\CertificateRevocation;
use App\Models\Dispute;
use App\Models\AuditSample;
use App\Models\GovEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Authority Console Controller
 * For government authority officers (audit, freeze, revoke)
 */
class AuthorityDashboardController extends Controller
{
    protected DecisionLedgerService $ledgerService;
    protected GovernmentAccessService $accessService;

    public function __construct(
        DecisionLedgerService $ledgerService,
        GovernmentAccessService $accessService
    ) {
        $this->ledgerService = $ledgerService;
        $this->accessService = $accessService;
    }

    /**
     * Authority Dashboard
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Verify authority access
        $access = $this->accessService->verifyAccess($request, $user);
        if (!$access['allowed']) {
            abort(403, 'Authority access denied');
        }

        // Determine scope (entity-specific or global)
        $authorityScope = $this->accessService->getAuthorityScope($user);
        
        // Get statistics with entity scoping
        $stats = [
            'pending_audits' => $this->scopeQuery(AuditSample::query(), $authorityScope)
                ->where('status', 'pending')->count(),
            'active_disputes' => $this->scopeQuery(Dispute::query(), $authorityScope)
                ->whereIn('status', ['open', 'investigating'])->count(),
            'frozen_certificates' => $this->scopeQuery(CertificateRevocation::query(), $authorityScope)
                ->where('action', 'frozen')
                ->whereNull('restored_at')->count(),
            'revoked_certificates' => $this->scopeQuery(CertificateRevocation::query(), $authorityScope)
                ->where('action', 'revoked')->count(),
            'gov_entities_active' => GovEntity::where('status', 'active')->count(),
            'scope' => $authorityScope ? 'entity' : 'global'
        ];

        // Recent activity
        $recentAudits = AuditSample::with(['document', 'auditor'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recentDisputes = Dispute::with(['document', 'certificate'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('authority.dashboard', compact('stats', 'recentAudits', 'recentDisputes'));
    }

    /**
     * Audit Sampling - Random certificate checks
     */
    public function createAuditSample(Request $request)
    {
        $request->validate([
            'gov_entity_id' => 'required|exists:gov_entities,id',
            'sample_percentage' => 'required|numeric|min:1|max:100',
            'sample_type' => 'required|in:random,risk_based,complaint_triggered'
        ]);

        $entity = GovEntity::findOrFail($request->gov_entity_id);

        // Get eligible documents query
        $baseQuery = OfficialDocument::whereHas('user.governmentProfile', function ($q) use ($entity) {
            $q->where('entity_code', $entity->entity_code);
        })
        ->where('status', 'completed')
        ->whereHas('certificate');

        // Calculate correct sample size
        $totalEligible = $baseQuery->count();
        if ($totalEligible === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No eligible documents found for sampling'
            ], 400);
        }

        // Calculate percentage with bounds (min: 5, max: 500)
        $sampleSize = max(5, min(500, (int) ceil($totalEligible * ($request->sample_percentage / 100))));
        
        // Get random documents
        $documents = $baseQuery->inRandomOrder()
            ->limit($sampleSize)
            ->get();

        $samples = [];
        foreach ($documents as $document) {
            $sample = AuditSample::create([
                'gov_entity_id' => $entity->id,
                'document_id' => $document->id,
                'sample_type' => $request->sample_type,
                'sample_percentage' => $request->sample_percentage,
                'auditor_id' => auth()->id(),
                'status' => 'pending'
            ]);

            // Record in ledger
            $this->ledgerService->record(
                'audit_sample_created',
                auth()->id(),
                'gov_authority_officer',
                [
                    'sample_id' => $sample->id,
                    'document_id' => $document->id,
                    'sample_type' => $request->sample_type,
                    'percentage' => $request->sample_percentage
                ],
                $document->id,
                null,
                $entity->id
            );

            $samples[] = $sample;
        }

        return response()->json([
            'success' => true,
            'message' => 'Audit samples created',
            'count' => count($samples),
            'samples' => $samples
        ]);
    }

    /**
     * Freeze Certificate (temporary suspension)
     */
    public function freezeCertificate(Request $request, int $certificateId)
    {
        $request->validate([
            'reason' => 'required|string|min:20',
            'legal_reference' => 'nullable|string'
        ]);

        if (!$this->accessService->canPerform(auth()->user(), 'freeze_certificate')) {
            abort(403, 'Insufficient permissions to freeze certificates');
        }

        $certificate = DocumentCertificate::findOrFail($certificateId);

        DB::transaction(function () use ($certificate, $request) {
            // Create ledger event first
            $ledgerEvent = $this->ledgerService->record(
                'certificate_frozen',
                auth()->id(),
                'gov_authority_officer',
                [
                    'reason' => $request->reason,
                    'legal_reference' => $request->legal_reference,
                    'certificate_number' => $certificate->certificate_number
                ],
                $certificate->document_id,
                $certificate->id
            );

            // Create revocation record
            $revocation = CertificateRevocation::create([
                'certificate_id' => $certificate->id,
                'ledger_event_id' => $ledgerEvent->id,
                'action' => 'frozen',
                'reason' => $request->reason,
                'legal_reference' => $request->legal_reference,
                'requested_by' => auth()->id(),
                'effective_from' => now()
            ]);

            // Update certificate status
            $certificate->update(['status' => 'frozen']);
        });

        return response()->json([
            'success' => true,
            'message' => 'Certificate frozen successfully',
            'certificate_id' => $certificateId
        ]);
    }

    /**
     * Request Certificate Revocation (officer creates request)
     */
    public function requestRevocation(Request $request, int $certificateId)
    {
        $request->validate([
            'reason' => 'required|string|min:20',
            'legal_reference' => 'required|string',
            'jurisdiction_country' => 'nullable|string',
            'jurisdiction_purpose' => 'nullable|string',
            'legal_basis_code' => 'nullable|string'
        ]);

        if (!$this->accessService->canPerform(auth()->user(), 'request_revocation')) {
            abort(403, 'Insufficient permissions to request revocation');
        }

        $certificate = DocumentCertificate::findOrFail($certificateId);

        $revocationRequest = CertificateRevocationRequest::create([
            'certificate_id' => $certificateId,
            'action' => 'revoke',
            'requested_by' => auth()->id(),
            'requested_by_role' => 'gov_authority_officer',
            'requested_at' => now(),
            'reason' => $request->reason,
            'legal_reference' => $request->legal_reference,
            'jurisdiction_country' => $request->jurisdiction_country,
            'jurisdiction_purpose' => $request->jurisdiction_purpose,
            'legal_basis_code' => $request->legal_basis_code,
            'status' => 'pending'
        ]);

        // Record in ledger
        $this->ledgerService->record(
            'revocation_requested',
            auth()->id(),
            'gov_authority_officer',
            [
                'request_id' => $revocationRequest->id,
                'certificate_number' => $certificate->certificate_number,
                'reason' => $request->reason
            ],
            $certificate->document_id,
            $certificate->id
        );

        return response()->json([
            'success' => true,
            'message' => 'Revocation request created. Awaiting supervisor approval.',
            'request_id' => $revocationRequest->id
        ]);
    }

    /**
     * Approve Revocation Request (supervisor only - two-man rule)
     */
    public function approveRevocation(Request $request, int $requestId)
    {
        $revocationRequest = CertificateRevocationRequest::with('certificate')
            ->findOrFail($requestId);

        // Verify can approve
        if (!$revocationRequest->canBeApprovedBy(auth()->user())) {
            abort(403, 'Cannot approve this request. Either not pending, wrong role, or self-approval.');
        }

        DB::transaction(function () use ($revocationRequest, $request) {
            $certificate = $revocationRequest->certificate;

            // Create ledger event
            $ledgerEvent = $this->ledgerService->record(
                'certificate_revoked',
                auth()->id(),
                'gov_authority_supervisor',
                [
                    'reason' => $revocationRequest->reason,
                    'legal_reference' => $revocationRequest->legal_reference,
                    'certificate_number' => $certificate->certificate_number,
                    'requested_by' => $revocationRequest->requested_by,
                    'approved_by' => auth()->id(),
                    'two_man_rule' => true
                ],
                $certificate->document_id,
                $certificate->id
            );

            // Create revocation record
            $revocation = CertificateRevocation::create([
                'certificate_id' => $certificate->id,
                'ledger_event_id' => $ledgerEvent->id,
                'action' => 'revoked',
                'reason' => $revocationRequest->reason,
                'legal_reference' => $revocationRequest->legal_reference,
                'requested_by' => $revocationRequest->requested_by,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'effective_from' => now(),
                'jurisdiction_country' => $revocationRequest->jurisdiction_country,
                'jurisdiction_purpose' => $revocationRequest->jurisdiction_purpose,
                'legal_basis_code' => $revocationRequest->legal_basis_code
            ]);

            // Update certificate status
            $certificate->update(['status' => 'revoked']);

            // Update request status
            $revocationRequest->update([
                'status' => 'executed',
                'approved_by' => auth()->id(),
                'approved_by_role' => 'gov_authority_supervisor',
                'approved_at' => now(),
                'revocation_id' => $revocation->id
            ]);

            // Generate revocation receipt PDF
            app(RevocationReceiptService::class)->generate($certificate->id, $revocation->id);
        });

        return response()->json([
            'success' => true,
            'message' => 'Revocation approved and executed successfully',
            'request_id' => $requestId
        ]);
    }

    /**
     * Restore Frozen Certificate
     */
    public function restoreCertificate(Request $request, int $certificateId)
    {
        $request->validate([
            'resolution_notes' => 'required|string|min:20'
        ]);

        $certificate = DocumentCertificate::findOrFail($certificateId);
        $revocation = CertificateRevocation::where('certificate_id', $certificateId)
            ->where('action', 'frozen')
            ->whereNull('restored_at')
            ->firstOrFail();

        DB::transaction(function () use ($certificate, $revocation, $request) {
            // Record in ledger
            $this->ledgerService->record(
                'certificate_restored',
                auth()->id(),
                'gov_authority_officer',
                [
                    'resolution_notes' => $request->resolution_notes,
                    'certificate_number' => $certificate->certificate_number
                ],
                $certificate->document_id,
                $certificate->id
            );

            // Update revocation
            $revocation->update(['restored_at' => now()]);

            // Update certificate status
            $certificate->update(['status' => 'valid']);
        });

        return response()->json([
            'success' => true,
            'message' => 'Certificate restored successfully'
        ]);
    }

    /**
     * Open Dispute
     */
    public function openDispute(Request $request)
    {
        $request->validate([
            'document_id' => 'nullable|exists:official_documents,id',
            'certificate_id' => 'nullable|exists:document_certificates,id',
            'dispute_type' => 'required|string',
            'description' => 'required|string|min:50',
            'priority' => 'required|in:low,medium,high,critical',
            'evidence_files' => 'nullable|array'
        ]);

        if (!$this->accessService->canPerform(auth()->user(), 'open_dispute')) {
            abort(403, 'Insufficient permissions to open disputes');
        }

        $dispute = DB::transaction(function () use ($request) {
            $dispute = Dispute::create([
                'document_id' => $request->document_id,
                'certificate_id' => $request->certificate_id,
                'dispute_type' => $request->dispute_type,
                'description' => $request->description,
                'raised_by' => auth()->id(),
                'raised_by_role' => 'gov_authority_officer',
                'status' => 'open',
                'priority' => $request->priority,
                'evidence_files' => $request->evidence_files ?? []
            ]);

            // Record in ledger
            $this->ledgerService->record(
                'dispute_opened',
                auth()->id(),
                'gov_authority_officer',
                [
                    'dispute_id' => $dispute->id,
                    'dispute_type' => $request->dispute_type,
                    'priority' => $request->priority
                ],
                $request->document_id,
                $request->certificate_id,
                null,
                $dispute->id
            );

            return $dispute;
        });

        return response()->json([
            'success' => true,
            'message' => 'Dispute opened successfully',
            'dispute' => $dispute
        ]);
    }

    /**
     * Verify Chain Integrity (rate-limited)
     */
    public function verifyChainIntegrity(Request $request)
    {
        // Rate limit: authority only, 10 requests per minute
        $fromId = $request->input('from_id');
        $toId = $request->input('to_id');

        $result = $this->ledgerService->verifyChainIntegrity($fromId, $toId);

        return response()->json($result);
    }

    /**
     * Apply entity scope to query (officers see only their entity)
     */
    protected function scopeQuery($query, ?int $entityId)
    {
        if ($entityId !== null) {
            // Officer: scope to entity
            $query->where('gov_entity_id', $entityId);
        }
        // Supervisor: see all (no scope)
        
        return $query;
    }
}
