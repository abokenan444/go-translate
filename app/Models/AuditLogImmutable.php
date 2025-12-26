<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Immutable Audit Log Model
 * 
 * WARNING: This model represents immutable data
 * Updates and deletes are prevented at database level
 */
class AuditLogImmutable extends Model
{
    protected $table = 'audit_logs_immutable';

    // Only created_at, no updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'event_type',
        'auditable_type',
        'auditable_id',
        'user_id',
        'user_name',
        'user_email',
        'user_role',
        'ip_address',
        'user_agent',
        'request_method',
        'request_url',
        'old_values',
        'new_values',
        'metadata',
        'action_hash',
        'previous_hash',
        'chain_hash',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Prevent updates
     */
    public function update(array $attributes = [], array $options = [])
    {
        throw new \Exception('Audit logs are immutable and cannot be updated');
    }

    /**
     * Prevent deletes
     */
    public function delete()
    {
        throw new \Exception('Audit logs are immutable and cannot be deleted');
    }

    /**
     * Get the user who performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the auditable model
     */
    public function auditable()
    {
        return $this->morphTo();
    }
}
