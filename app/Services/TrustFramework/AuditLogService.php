<?php

namespace App\Services\TrustFramework;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    /**
     * Log an action with blockchain-inspired integrity
     */
    public function log(array $data): int
    {
        // Get previous log entry for chaining
        $previousLog = DB::table('audit_logs')
            ->orderBy('id', 'desc')
            ->first();
        
        $previousHash = $previousLog->current_hash ?? null;
        
        // Prepare log data
        $logData = [
            'user_id' => $data['user_id'] ?? auth()->id(),
            'action' => $data['action'],
            'entity_type' => $data['entity_type'],
            'entity_id' => $data['entity_id'],
            'ip' => $data['ip'] ?? Request::ip(),
            'user_agent' => $data['user_agent'] ?? Request::userAgent(),
            'request_id' => $data['request_id'] ?? uniqid('req_', true),
            'old_values' => isset($data['old_values']) ? json_encode($data['old_values']) : null,
            'new_values' => isset($data['new_values']) ? json_encode($data['new_values']) : null,
            'metadata' => isset($data['metadata']) ? json_encode($data['metadata']) : null,
            'previous_hash' => $previousHash,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
        // Calculate current hash (blockchain-style)
        $currentHash = $this->calculateHash($logData);
        $logData['current_hash'] = $currentHash;
        
        // Calculate chain hash (cumulative)
        $chainHash = $this->calculateChainHash($previousHash, $currentHash);
        $logData['chain_hash'] = $chainHash;
        
        // Insert log entry
        $logId = DB::table('audit_logs')->insertGetId($logData);
        
        return $logId;
    }
    
    /**
     * Calculate SHA-256 hash of log data
     */
    protected function calculateHash(array $data): string
    {
        // Remove fields that shouldn't be in hash
        $hashData = array_diff_key($data, array_flip([
            'id',
            'current_hash',
            'chain_hash',
            'is_tampered',
            'verified_at',
            'created_at',
            'updated_at',
        ]));
        
        // Sort keys for consistency
        ksort($hashData);
        
        // Create hash string
        $hashString = json_encode($hashData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        return hash('sha256', $hashString);
    }
    
    /**
     * Calculate cumulative chain hash
     */
    protected function calculateChainHash(?string $previousHash, string $currentHash): string
    {
        if (!$previousHash) {
            return $currentHash; // First entry
        }
        
        return hash('sha256', $previousHash . $currentHash);
    }
    
    /**
     * Verify integrity of audit log chain
     */
    public function verifyIntegrity(int $fromId = null, int $toId = null): array
    {
        $query = DB::table('audit_logs')->orderBy('id');
        
        if ($fromId) {
            $query->where('id', '>=', $fromId);
        }
        if ($toId) {
            $query->where('id', '<=', $toId);
        }
        
        $logs = $query->get();
        
        $results = [
            'total' => $logs->count(),
            'verified' => 0,
            'tampered' => 0,
            'issues' => [],
        ];
        
        $previousHash = null;
        
        foreach ($logs as $log) {
            $isValid = true;
            $issues = [];
            
            // Verify current hash
            $logData = (array) $log;
            $calculatedHash = $this->calculateHash($logData);
            
            if ($calculatedHash !== $log->current_hash) {
                $isValid = false;
                $issues[] = 'Current hash mismatch';
            }
            
            // Verify chain
            if ($log->previous_hash !== $previousHash) {
                $isValid = false;
                $issues[] = 'Chain broken';
            }
            
            // Verify chain hash
            $calculatedChainHash = $this->calculateChainHash($previousHash, $log->current_hash ?? "");
            if ($calculatedChainHash !== $log->chain_hash) {
                $isValid = false;
                $issues[] = 'Chain hash mismatch';
            }
            
            if ($isValid) {
                $results['verified']++;
                
                // Mark as verified
                DB::table('audit_logs')
                    ->where('id', $log->id)
                    ->update([
                        'is_tampered' => false,
                        'verified_at' => now(),
                    ]);
            } else {
                $results['tampered']++;
                $results['issues'][] = [
                    'log_id' => $log->id,
                    'action' => $log->action,
                    'created_at' => $log->created_at,
                    'issues' => $issues,
                ];
                
                // Mark as tampered
                DB::table('audit_logs')
                    ->where('id', $log->id)
                    ->update([
                        'is_tampered' => true,
                        'verified_at' => now(),
                    ]);
            }
            
            $previousHash = $log->current_hash;
        }
        
        $results['integrity_rate'] = $results['total'] > 0 
            ? round(($results['verified'] / $results['total']) * 100, 2) 
            : 100;
        
        return $results;
    }
    
    /**
     * Get audit trail for an entity
     */
    public function getTrail(string $entityType, int $entityId): array
    {
        $logs = DB::table('audit_logs')
            ->where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return $logs->map(function ($log) {
            return [
                'id' => $log->id,
                'action' => $log->action,
                'user_id' => $log->user_id,
                'ip' => $log->ip,
                'user_agent' => $log->user_agent,
                'old_values' => $log->old_values ? json_decode($log->old_values, true) : null,
                'new_values' => $log->new_values ? json_decode($log->new_values, true) : null,
                'metadata' => $log->metadata ? json_decode($log->metadata, true) : null,
                'is_tampered' => (bool) $log->is_tampered,
                'created_at' => $log->created_at,
            ];
        })->toArray();
    }
    
    /**
     * Export audit log for compliance
     */
    public function exportForCompliance(int $fromId = null, int $toId = null): string
    {
        $query = DB::table('audit_logs')->orderBy('id');
        
        if ($fromId) {
            $query->where('id', '>=', $fromId);
        }
        if ($toId) {
            $query->where('id', '<=', $toId);
        }
        
        $logs = $query->get();
        
        $export = [
            'export_date' => now()->toIso8601String(),
            'total_entries' => $logs->count(),
            'integrity_verified' => true,
            'entries' => [],
        ];
        
        foreach ($logs as $log) {
            $export['entries'][] = [
                'id' => $log->id,
                'action' => $log->action,
                'entity_type' => $log->entity_type,
                'entity_id' => $log->entity_id,
                'user_id' => $log->user_id,
                'ip' => $log->ip,
                'timestamp' => $log->created_at,
                'current_hash' => $log->current_hash,
                'chain_hash' => $log->chain_hash,
                'is_tampered' => (bool) $log->is_tampered,
            ];
        }
        
        return json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
