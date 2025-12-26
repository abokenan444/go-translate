<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class GovernmentVerification extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'entity_name',
        'entity_name_ar',
        'entity_type',
        'country_code',
        'country_name',
        'official_email',
        'official_phone',
        'official_website',
        'job_title',
        'department',
        'documents',
        'additional_info',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
        'rejection_reason',
        'legal_declaration_accepted',
        'declaration_ip',
        'declaration_timestamp',
        'verification_token',
        'token_expires_at',
        'audit_trail',
    ];

    protected $casts = [
        'documents' => 'array',
        'audit_trail' => 'array',
        'reviewed_at' => 'datetime',
        'declaration_timestamp' => 'datetime',
        'token_expires_at' => 'datetime',
        'legal_declaration_accepted' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->verification_token) {
                $model->verification_token = Str::random(64);
                $model->token_expires_at = now()->addDays(7);
            }
            
            // Initialize audit trail
            if (!$model->audit_trail) {
                $model->audit_trail = [];
            }
            
            $model->addAuditEntry('created', null, 'pending_verification', 'Government verification request submitted');
        });

        static::updating(function ($model) {
            if ($model->isDirty('status')) {
                $model->addAuditEntry(
                    'status_changed',
                    $model->getOriginal('status'),
                    $model->status,
                    'Status changed'
                );
            }
        });
    }

    /**
     * Add entry to audit trail
     */
    public function addAuditEntry(string $action, $fromStatus = null, $toStatus = null, string $notes = null)
    {
        $trail = $this->audit_trail ?? [];
        
        $trail[] = [
            'action' => $action,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'notes' => $notes,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'timestamp' => now()->toIso8601String(),
        ];
        
        $this->audit_trail = $trail;
    }

    /**
     * Get the user that owns the verification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reviewer
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get verification logs
     */
    public function logs(): HasMany
    {
        return $this->hasMany(GovernmentVerificationLog::class);
    }

    /**
     * Get documents
     */
    public function documents(): HasMany
    {
        return $this->hasMany(GovernmentDocument::class, 'verification_id');
    }

    /**
     * Get audit logs
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(GovernmentAuditLog::class, 'verification_id');
    }

    /**
     * Scope: Pending verifications
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending_verification');
    }

    /**
     * Scope: Under review
     */
    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }

    /**
     * Scope: Approved
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Check if email domain is valid government domain
     */
    public static function isValidGovernmentEmail(string $email): bool
    {
        $domain = Str::after($email, '@');
        
        return GovernmentEmailDomain::where('domain', $domain)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Approve verification
     */
    public function approve(string $notes = null, string $badgeLevel = 'verified', string $accessLevel = 'standard')
    {
        $this->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $notes,
        ]);

        // Update user
        $this->user->update([
            'is_government_verified' => true,
            'government_verified_at' => now(),
            'government_badge' => $badgeLevel,
            'government_access_level' => $accessLevel,
            'account_status' => 'active',
        ]);

        // Log the action
        $this->logs()->create([
            'user_id' => auth()->id(),
            'action' => 'approved',
            'from_status' => 'under_review',
            'to_status' => 'approved',
            'notes' => $notes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Send notification to user
        // $this->user->notify(new GovernmentVerificationApproved($this));
    }

    /**
     * Reject verification
     */
    public function reject(string $reason)
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'rejection_reason' => $reason,
        ]);

        // Update user
        $this->user->update([
            'account_status' => 'suspended',
        ]);

        // Log the action
        $this->logs()->create([
            'user_id' => auth()->id(),
            'action' => 'rejected',
            'from_status' => 'under_review',
            'to_status' => 'rejected',
            'notes' => $reason,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Send notification to user
        // $this->user->notify(new GovernmentVerificationRejected($this, $reason));
    }

    /**
     * Request more documents
     */
    public function requestDocuments(string $message)
    {
        $this->update([
            'status' => 'documents_requested',
            'review_notes' => $message,
        ]);

        // Log the action
        $this->logs()->create([
            'user_id' => auth()->id(),
            'action' => 'documents_requested',
            'notes' => $message,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Send notification to user
        // $this->user->notify(new GovernmentVerificationDocumentsRequested($this, $message));
    }
}
