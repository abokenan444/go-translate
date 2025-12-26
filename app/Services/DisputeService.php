<?php

namespace App\Services;

use App\Models\DocumentDispute;
use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class DisputeService
{
    /**
     * Create a new dispute
     */
    public function createDispute($documentId, $raisedBy, $type, $description)
    {
        $dispute = DocumentDispute::create([
            'document_id' => $documentId,
            'raised_by' => $raisedBy,
            'dispute_type' => $type,
            'description' => $description,
            'status' => 'open',
        ]);

        // Assign to support team
        $this->autoAssignDispute($dispute);

        // Notify relevant parties
        $this->notifyDisputeCreated($dispute);

        Log::info('Dispute created', [
            'dispute_id' => $dispute->id,
            'document_id' => $documentId,
            'type' => $type,
        ]);

        return $dispute;
    }

    /**
     * Resolve a dispute
     */
    public function resolveDispute($disputeId, $resolution, $resolvedBy)
    {
        $dispute = DocumentDispute::findOrFail($disputeId);
        $dispute->resolve($resolution, $resolvedBy);

        // Notify parties
        $this->notifyDisputeResolved($dispute);

        Log::info('Dispute resolved', [
            'dispute_id' => $disputeId,
            'resolved_by' => $resolvedBy,
        ]);

        return $dispute;
    }

    /**
     * Escalate a dispute
     */
    public function escalateDispute($disputeId, $assignTo)
    {
        $dispute = DocumentDispute::findOrFail($disputeId);
        $dispute->escalate($assignTo);

        // Notify new assignee
        $this->notifyDisputeEscalated($dispute);

        Log::info('Dispute escalated', [
            'dispute_id' => $disputeId,
            'assigned_to' => $assignTo,
        ]);

        return $dispute;
    }

    /**
     * Auto-assign dispute to support team
     */
    private function autoAssignDispute(DocumentDispute $dispute)
    {
        // Find available support agent (implement your logic)
        $supportAgent = User::where('role', 'support')
            ->where('status', 'active')
            ->inRandomOrder()
            ->first();

        if ($supportAgent) {
            $dispute->update(['assigned_to' => $supportAgent->id]);
        }
    }

    /**
     * Get dispute statistics
     */
    public function getStatistics($period = 30)
    {
        $since = now()->subDays($period);

        return [
            'total' => DocumentDispute::where('created_at', '>=', $since)->count(),
            'open' => DocumentDispute::where('status', 'open')->count(),
            'resolved' => DocumentDispute::where('status', 'resolved')
                ->where('created_at', '>=', $since)
                ->count(),
            'escalated' => DocumentDispute::where('status', 'escalated')->count(),
            'avg_resolution_time' => $this->calculateAverageResolutionTime($since),
            'by_type' => DocumentDispute::where('created_at', '>=', $since)
                ->groupBy('dispute_type')
                ->selectRaw('dispute_type, count(*) as count')
                ->pluck('count', 'dispute_type'),
        ];
    }

    /**
     * Calculate average resolution time
     */
    private function calculateAverageResolutionTime($since)
    {
        $disputes = DocumentDispute::where('status', 'resolved')
            ->where('created_at', '>=', $since)
            ->whereNotNull('resolved_at')
            ->get();

        if ($disputes->isEmpty()) {
            return 0;
        }

        $totalHours = $disputes->sum(function($dispute) {
            return $dispute->created_at->diffInHours($dispute->resolved_at);
        });

        return round($totalHours / $disputes->count(), 2);
    }

    /**
     * Notify dispute created
     */
    private function notifyDisputeCreated(DocumentDispute $dispute)
    {
        // Implement notification logic
        // Notification::send($dispute->assignedTo, new DisputeCreatedNotification($dispute));
    }

    /**
     * Notify dispute resolved
     */
    private function notifyDisputeResolved(DocumentDispute $dispute)
    {
        // Implement notification logic
        // Notification::send($dispute->raisedBy, new DisputeResolvedNotification($dispute));
    }

    /**
     * Notify dispute escalated
     */
    private function notifyDisputeEscalated(DocumentDispute $dispute)
    {
        // Implement notification logic
        // Notification::send($dispute->assignedTo, new DisputeEscalatedNotification($dispute));
    }
}
