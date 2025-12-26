<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/**
 * Partner Workflow Management Service
 * Manages the complete partner workflow: Assignment → Review → Stamp → Ship → Track
 */
class PartnerWorkflowService
{
    /**
     * Assign document to partner
     *
     * @param int $documentId
     * @param int $partnerId
     * @param array $options
     * @return array
     */
    public function assignDocumentToPartner(int $documentId, int $partnerId, array $options = []): array
    {
        try {
            // Verify partner exists and is active
            $partner = DB::table('partners')->where('id', $partnerId)->where('status', 'active')->first();
            
            if (!$partner) {
                return [
                    'success' => false,
                    'error' => 'Partner not found or inactive'
                ];
            }
            
            // Create assignment
            $assignmentId = DB::table('partner_assignments')->insertGetId([
                'document_id' => $documentId,
                'partner_id' => $partnerId,
                'status' => 'assigned',
                'assigned_at' => now(),
                'deadline' => $options['deadline'] ?? now()->addDays(3),
                'priority' => $options['priority'] ?? 'normal',
                'instructions' => $options['instructions'] ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Send notification to partner
            $this->notifyPartner($partnerId, 'new_assignment', [
                'assignment_id' => $assignmentId,
                'document_id' => $documentId
            ]);
            
            // Log assignment
            $this->logWorkflowAction($assignmentId, 'assigned', [
                'partner_id' => $partnerId,
                'document_id' => $documentId
            ]);
            
            return [
                'success' => true,
                'assignment_id' => $assignmentId,
                'partner' => [
                    'id' => $partner->id,
                    'name' => $partner->name,
                    'email' => $partner->email
                ],
                'deadline' => $options['deadline'] ?? now()->addDays(3)
            ];
            
        } catch (\Exception $e) {
            Log::error('Partner assignment failed', [
                'document_id' => $documentId,
                'partner_id' => $partnerId,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Partner reviews and accepts/rejects assignment
     *
     * @param int $assignmentId
     * @param string $action
     * @param array $reviewData
     * @return array
     */
    public function reviewAssignment(int $assignmentId, string $action, array $reviewData = []): array
    {
        try {
            $assignment = DB::table('partner_assignments')->where('id', $assignmentId)->first();
            
            if (!$assignment) {
                return ['success' => false, 'error' => 'Assignment not found'];
            }
            
            if ($action === 'accept') {
                DB::table('partner_assignments')->where('id', $assignmentId)->update([
                    'status' => 'in_review',
                    'accepted_at' => now(),
                    'updated_at' => now()
                ]);
                
                $this->logWorkflowAction($assignmentId, 'accepted');
                
                return [
                    'success' => true,
                    'status' => 'in_review',
                    'message' => 'Assignment accepted'
                ];
                
            } elseif ($action === 'reject') {
                DB::table('partner_assignments')->where('id', $assignmentId)->update([
                    'status' => 'rejected',
                    'rejected_at' => now(),
                    'rejection_reason' => $reviewData['reason'] ?? 'Not specified',
                    'updated_at' => now()
                ]);
                
                $this->logWorkflowAction($assignmentId, 'rejected', [
                    'reason' => $reviewData['reason'] ?? 'Not specified'
                ]);
                
                return [
                    'success' => true,
                    'status' => 'rejected',
                    'message' => 'Assignment rejected'
                ];
                
            } elseif ($action === 'complete_review') {
                // Partner has reviewed and approved translation
                DB::table('partner_assignments')->where('id', $assignmentId)->update([
                    'status' => 'review_completed',
                    'review_completed_at' => now(),
                    'review_notes' => $reviewData['notes'] ?? null,
                    'updated_at' => now()
                ]);
                
                $this->logWorkflowAction($assignmentId, 'review_completed');
                
                return [
                    'success' => true,
                    'status' => 'review_completed',
                    'message' => 'Review completed successfully',
                    'next_step' => 'apply_stamp'
                ];
            }
            
            return ['success' => false, 'error' => 'Invalid action'];
            
        } catch (\Exception $e) {
            Log::error('Review assignment failed', [
                'assignment_id' => $assignmentId,
                'error' => $e->getMessage()
            ]);
            
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Partner applies physical stamp
     *
     * @param int $assignmentId
     * @param array $stampData
     * @return array
     */
    public function applyStamp(int $assignmentId, array $stampData): array
    {
        try {
            $assignment = DB::table('partner_assignments')->where('id', $assignmentId)->first();
            
            if (!$assignment || $assignment->status !== 'review_completed') {
                return [
                    'success' => false,
                    'error' => 'Assignment not ready for stamping'
                ];
            }
            
            // Upload stamp image if provided
            $stampImagePath = null;
            if (isset($stampData['stamp_image'])) {
                $stampImagePath = $this->uploadStampImage($assignmentId, $stampData['stamp_image']);
            }
            
            // Update assignment
            DB::table('partner_assignments')->where('id', $assignmentId)->update([
                'status' => 'stamped',
                'stamped_at' => now(),
                'stamp_image_path' => $stampImagePath,
                'stamp_location' => $stampData['location'] ?? 'last_page',
                'updated_at' => now()
            ]);
            
            $this->logWorkflowAction($assignmentId, 'stamped', [
                'stamp_location' => $stampData['location'] ?? 'last_page'
            ]);
            
            return [
                'success' => true,
                'status' => 'stamped',
                'message' => 'Stamp applied successfully',
                'next_step' => 'prepare_for_shipping'
            ];
            
        } catch (\Exception $e) {
            Log::error('Apply stamp failed', [
                'assignment_id' => $assignmentId,
                'error' => $e->getMessage()
            ]);
            
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Prepare document for shipping
     *
     * @param int $assignmentId
     * @param array $shippingData
     * @return array
     */
    public function prepareShipping(int $assignmentId, array $shippingData): array
    {
        try {
            $assignment = DB::table('partner_assignments')->where('id', $assignmentId)->first();
            
            if (!$assignment || $assignment->status !== 'stamped') {
                return ['success' => false, 'error' => 'Assignment not ready for shipping'];
            }
            
            // Create shipping record
            $shippingId = DB::table('partner_shipments')->insertGetId([
                'assignment_id' => $assignmentId,
                'shipping_method' => $shippingData['method'] ?? 'standard',
                'tracking_number' => $shippingData['tracking_number'] ?? null,
                'carrier' => $shippingData['carrier'] ?? null,
                'recipient_name' => $shippingData['recipient_name'],
                'recipient_address' => $shippingData['recipient_address'],
                'recipient_phone' => $shippingData['recipient_phone'] ?? null,
                'status' => 'preparing',
                'estimated_delivery' => $shippingData['estimated_delivery'] ?? now()->addDays(5),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Update assignment
            DB::table('partner_assignments')->where('id', $assignmentId)->update([
                'status' => 'ready_to_ship',
                'shipping_prepared_at' => now(),
                'updated_at' => now()
            ]);
            
            $this->logWorkflowAction($assignmentId, 'shipping_prepared', [
                'shipping_id' => $shippingId
            ]);
            
            return [
                'success' => true,
                'shipping_id' => $shippingId,
                'status' => 'ready_to_ship',
                'message' => 'Shipping prepared'
            ];
            
        } catch (\Exception $e) {
            Log::error('Prepare shipping failed', [
                'assignment_id' => $assignmentId,
                'error' => $e->getMessage()
            ]);
            
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Mark document as shipped
     *
     * @param int $shippingId
     * @param array $shipmentData
     * @return array
     */
    public function markAsShipped(int $shippingId, array $shipmentData): array
    {
        try {
            $shipping = DB::table('partner_shipments')->where('id', $shippingId)->first();
            
            if (!$shipping) {
                return ['success' => false, 'error' => 'Shipping record not found'];
            }
            
            // Update shipping record
            DB::table('partner_shipments')->where('id', $shippingId)->update([
                'status' => 'shipped',
                'tracking_number' => $shipmentData['tracking_number'],
                'carrier' => $shipmentData['carrier'],
                'shipped_at' => now(),
                'updated_at' => now()
            ]);
            
            // Update assignment
            DB::table('partner_assignments')->where('id', $shipping->assignment_id)->update([
                'status' => 'shipped',
                'shipped_at' => now(),
                'updated_at' => now()
            ]);
            
            $this->logWorkflowAction($shipping->assignment_id, 'shipped', [
                'tracking_number' => $shipmentData['tracking_number'],
                'carrier' => $shipmentData['carrier']
            ]);
            
            // Notify customer
            $this->notifyCustomer($shipping->assignment_id, 'document_shipped', [
                'tracking_number' => $shipmentData['tracking_number'],
                'carrier' => $shipmentData['carrier']
            ]);
            
            return [
                'success' => true,
                'status' => 'shipped',
                'tracking_number' => $shipmentData['tracking_number'],
                'carrier' => $shipmentData['carrier']
            ];
            
        } catch (\Exception $e) {
            Log::error('Mark as shipped failed', [
                'shipping_id' => $shippingId,
                'error' => $e->getMessage()
            ]);
            
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Update shipping tracking status
     *
     * @param int $shippingId
     * @param string $status
     * @param array $data
     * @return array
     */
    public function updateTrackingStatus(int $shippingId, string $status, array $data = []): array
    {
        try {
            $validStatuses = ['in_transit', 'out_for_delivery', 'delivered', 'failed_delivery', 'returned'];
            
            if (!in_array($status, $validStatuses)) {
                return ['success' => false, 'error' => 'Invalid status'];
            }
            
            DB::table('partner_shipments')->where('id', $shippingId)->update([
                'status' => $status,
                'tracking_updates' => DB::raw("JSON_ARRAY_APPEND(COALESCE(tracking_updates, '[]'), '$', '" . json_encode([
                    'status' => $status,
                    'timestamp' => now()->toIso8601String(),
                    'location' => $data['location'] ?? null,
                    'notes' => $data['notes'] ?? null
                ]) . "')"),
                'updated_at' => now()
            ]);
            
            // If delivered, mark assignment as completed
            if ($status === 'delivered') {
                $shipping = DB::table('partner_shipments')->where('id', $shippingId)->first();
                
                DB::table('partner_assignments')->where('id', $shipping->assignment_id)->update([
                    'status' => 'delivered',
                    'delivered_at' => now(),
                    'updated_at' => now()
                ]);
                
                $this->logWorkflowAction($shipping->assignment_id, 'delivered');
                
                // Notify customer
                $this->notifyCustomer($shipping->assignment_id, 'document_delivered');
            }
            
            return [
                'success' => true,
                'status' => $status,
                'message' => 'Tracking updated'
            ];
            
        } catch (\Exception $e) {
            Log::error('Update tracking failed', [
                'shipping_id' => $shippingId,
                'error' => $e->getMessage()
            ]);
            
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Get workflow status for assignment
     *
     * @param int $assignmentId
     * @return array
     */
    public function getWorkflowStatus(int $assignmentId): array
    {
        $assignment = DB::table('partner_assignments')->where('id', $assignmentId)->first();
        
        if (!$assignment) {
            return ['success' => false, 'error' => 'Assignment not found'];
        }
        
        $shipping = DB::table('partner_shipments')->where('assignment_id', $assignmentId)->first();
        
        $workflow = [
            'assignment_id' => $assignmentId,
            'current_status' => $assignment->status,
            'steps' => [
                'assigned' => [
                    'completed' => !is_null($assignment->assigned_at),
                    'date' => $assignment->assigned_at
                ],
                'accepted' => [
                    'completed' => !is_null($assignment->accepted_at),
                    'date' => $assignment->accepted_at
                ],
                'review_completed' => [
                    'completed' => !is_null($assignment->review_completed_at),
                    'date' => $assignment->review_completed_at
                ],
                'stamped' => [
                    'completed' => !is_null($assignment->stamped_at),
                    'date' => $assignment->stamped_at
                ],
                'shipped' => [
                    'completed' => !is_null($assignment->shipped_at),
                    'date' => $assignment->shipped_at
                ],
                'delivered' => [
                    'completed' => !is_null($assignment->delivered_at),
                    'date' => $assignment->delivered_at
                ]
            ]
        ];
        
        if ($shipping) {
            $workflow['shipping'] = [
                'tracking_number' => $shipping->tracking_number,
                'carrier' => $shipping->carrier,
                'status' => $shipping->status,
                'estimated_delivery' => $shipping->estimated_delivery
            ];
        }
        
        return ['success' => true, 'workflow' => $workflow];
    }
    
    /**
     * Upload stamp image
     *
     * @param int $assignmentId
     * @param mixed $image
     * @return string
     */
    private function uploadStampImage(int $assignmentId, $image): string
    {
        $filename = "stamps/assignment_{$assignmentId}_" . time() . ".jpg";
        Storage::disk('public')->put($filename, $image);
        return $filename;
    }
    
    /**
     * Log workflow action
     *
     * @param int $assignmentId
     * @param string $action
     * @param array $data
     * @return void
     */
    private function logWorkflowAction(int $assignmentId, string $action, array $data = []): void
    {
        DB::table('partner_workflow_logs')->insert([
            'assignment_id' => $assignmentId,
            'action' => $action,
            'data' => json_encode($data),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
    
    /**
     * Notify partner
     *
     * @param int $partnerId
     * @param string $type
     * @param array $data
     * @return void
     */
    private function notifyPartner(int $partnerId, string $type, array $data): void
    {
        // This would send email/SMS notification
        Log::info('Partner notification sent', [
            'partner_id' => $partnerId,
            'type' => $type,
            'data' => $data
        ]);
    }
    
    /**
     * Notify customer
     *
     * @param int $assignmentId
     * @param string $type
     * @param array $data
     * @return void
     */
    private function notifyCustomer(int $assignmentId, string $type, array $data = []): void
    {
        // This would send email/SMS notification to customer
        Log::info('Customer notification sent', [
            'assignment_id' => $assignmentId,
            'type' => $type,
            'data' => $data
        ]);
    }
}
