<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class GovernmentRegistration extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'password',
        'entity_name',
        'entity_type',
        'country_code',
        'country',
        'job_title',
        'department',
        'phone',
        'documents',
        'official_website_url',
        'additional_info',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
        'rejection_reason',
        'is_verified',
        'verification_badge',
        'verified_at',
        'legal_agreement_accepted',
        'ip_address',
        'legal_agreement_at',
        'audit_log',
        'verification_expiry_date',
        'requires_renewal',
    ];

    protected $casts = [
        'documents' => 'array',
        'audit_log' => 'array',
        'is_verified' => 'boolean',
        'legal_agreement_accepted' => 'boolean',
        'requires_renewal' => 'boolean',
        'reviewed_at' => 'datetime',
        'verified_at' => 'datetime',
        'legal_agreement_at' => 'datetime',
        'verification_expiry_date' => 'date',
    ];

    protected $hidden = [
        'password',
    ];

    // Entity Types
    public const ENTITY_TYPES = [
        'ministry' => 'وزارة / Ministry',
        'embassy' => 'سفارة / Embassy',
        'municipality' => 'بلدية / Municipality',
        'agency' => 'هيئة / Agency',
        'court' => 'محكمة / Court',
        'police' => 'شرطة / Police',
        'immigration' => 'هجرة / Immigration',
        'education' => 'تعليم / Education Ministry',
        'health' => 'صحة / Health Ministry',
        'other' => 'أخرى / Other',
    ];

    // Status Types
    public const STATUS_PENDING = 'pending_verification';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_MORE_INFO = 'more_info_required';
    public const STATUS_SUSPENDED = 'suspended';

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', self::STATUS_UNDER_REVIEW);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    // Helper Methods
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isUnderReview(): bool
    {
        return $this->status === self::STATUS_UNDER_REVIEW;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isVerified(): bool
    {
        return $this->is_verified === true;
    }

    public function requiresMoreInfo(): bool
    {
        return $this->status === self::STATUS_MORE_INFO;
    }

    // Status Change Methods with Audit Trail
    public function markUnderReview(User $reviewer): void
    {
        $this->addAuditLog('status_changed', 'under_review', $reviewer);
        $this->update([
            'status' => self::STATUS_UNDER_REVIEW,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
        ]);
    }

    public function approve(User $reviewer, ?string $notes = null): void
    {
        $this->addAuditLog('approved', $notes, $reviewer);
        $this->update([
            'status' => self::STATUS_APPROVED,
            'is_verified' => true,
            'verification_badge' => 'Verified Government Entity',
            'verified_at' => now(),
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'review_notes' => $notes,
            'verification_expiry_date' => now()->addYear(), // 1 year validity
        ]);
    }

    public function reject(User $reviewer, string $reason): void
    {
        $this->addAuditLog('rejected', $reason, $reviewer);
        $this->update([
            'status' => self::STATUS_REJECTED,
            'is_verified' => false,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    public function requestMoreInfo(User $reviewer, string $notes): void
    {
        $this->addAuditLog('more_info_requested', $notes, $reviewer);
        $this->update([
            'status' => self::STATUS_MORE_INFO,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'review_notes' => $notes,
        ]);
    }

    public function suspend(User $reviewer, string $reason): void
    {
        $this->addAuditLog('suspended', $reason, $reviewer);
        $this->update([
            'status' => self::STATUS_SUSPENDED,
            'is_verified' => false,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'review_notes' => $reason,
        ]);
    }

    // Audit Trail
    protected function addAuditLog(string $action, ?string $details, User $user): void
    {
        $logs = $this->audit_log ?? [];
        $logs[] = [
            'action' => $action,
            'details' => $details,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'ip_address' => request()->ip(),
            'timestamp' => now()->toIso8601String(),
        ];
        $this->audit_log = $logs;
    }

    // Document Management
    public function getDocumentUrls(): array
    {
        if (empty($this->documents)) {
            return [];
        }

        return array_map(function ($path) {
            return Storage::url($path);
        }, $this->documents);
    }

    public function addDocument(string $path): void
    {
        $documents = $this->documents ?? [];
        $documents[] = $path;
        $this->documents = $documents;
        $this->save();
    }

    // Status Badge Color
    public function getStatusBadgeColor(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_UNDER_REVIEW => 'info',
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_MORE_INFO => 'warning',
            self::STATUS_SUSPENDED => 'danger',
            default => 'secondary',
        };
    }

    // Status Badge Label
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'قيد الانتظار / Pending',
            self::STATUS_UNDER_REVIEW => 'قيد المراجعة / Under Review',
            self::STATUS_APPROVED => 'موافق / Approved',
            self::STATUS_REJECTED => 'مرفوض / Rejected',
            self::STATUS_MORE_INFO => 'معلومات إضافية / More Info Required',
            self::STATUS_SUSPENDED => 'معلق / Suspended',
            default => 'غير معروف / Unknown',
        };
    }
}
