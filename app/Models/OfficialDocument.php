<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficialDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'document_type',
        'source_language',
        'target_language',
        'original_file_path',
        'original_hash',
        'status',
        'order_id',
        'original_filename',
        'stored_filename',
        'file_path',
        'file_size',
        'estimated_pages',
        'estimated_words',
        'estimated_cost',
        'amount',
        'certificate_id',
        'stripe_session_id',
        'payment_status',
        'paid_at',
        'translated_path',
        'certified_path',
        'qr_code_path',
        'certified_partner_id',
        'partner_stamp_applied',
        'partner_stamp_date',
        'physical_copy_requested',
        'physical_copy_price',
        'shipping_address',
        'shipping_status',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'printed_by_partner',
        'printed_at',
        // Auto-assignment fields
        'certification_type',
        'country_selected_by_user',
        'country_from_portal',
        'jurisdiction_country',
        'source_lang',
        'target_lang',
        'reviewer_partner_id',
        'locked_assignment_id',
        'assignment_attempts',
        'assignment_status',
        'priority_level',
        'deadline_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'paid_at' => 'datetime',
        'partner_stamp_date' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'printed_at' => 'datetime',
        'deadline_at' => 'datetime',
        'partner_stamp_applied' => 'boolean',
        'physical_copy_requested' => 'boolean',
        'printed_by_partner' => 'boolean',
        'estimated_pages' => 'integer',
        'estimated_words' => 'integer',
        'file_size' => 'integer',
        'assignment_attempts' => 'integer',
        'estimated_cost' => 'decimal:2',
        'amount' => 'decimal:2',
        'physical_copy_price' => 'decimal:2',
    ];

    /**
     * Get the user that owns the document.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the certified partner assigned to this document.
     */
    public function certifiedPartner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'certified_partner_id');
    }

    /**
     * Get the order associated with the document.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(OfficialTranslationOrder::class, 'order_id');
    }

    /**
     * Get the translation for the document.
     */
    public function translation(): HasOne
    {
        return $this->hasOne(DocumentTranslation::class, 'official_document_id');
    }

    /**
     * Get the certificate for the document.
     */
    public function certificate(): HasOne
    {
        return $this->hasOne(DocumentCertificate::class, 'document_id');
    }

    /**
     * Get the reviews for the document.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(DocumentReview::class, 'document_id');
    }

    /**
     * Get the latest review.
     */
    public function latestReview(): HasOne
    {
        return $this->hasOne(DocumentReview::class, 'document_id')->latestOfMany();
    }

    /**
     * Check if document is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if document is paid.
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if document has partner stamp applied.
     */
    public function hasPartnerStamp(): bool
    {
        return $this->partner_stamp_applied === true;
    }

    /**
     * Check if physical copy is requested.
     */
    public function needsPhysicalCopy(): bool
    {
        return $this->physical_copy_requested === true;
    }

    /**
     * Check if document is ready for shipping.
     */
    public function isReadyForShipping(): bool
    {
        return $this->physical_copy_requested 
            && $this->partner_stamp_applied 
            && $this->status === 'completed'
            && $this->shipping_status === 'pending';
    }

    /**
     * Check if document has been shipped.
     */
    public function isShipped(): bool
    {
        return in_array($this->shipping_status, ['shipped', 'delivered']);
    }

    /**
     * Get total price including physical copy if requested.
     */
    public function getTotalPriceAttribute(): float
    {
        $total = (float) $this->amount;
        if ($this->physical_copy_requested) {
            $total += (float) $this->physical_copy_price;
        }
        return $total;
    }

    /**
     * Get shipping status label in Arabic.
     */
    public function getShippingStatusLabelAttribute(): string
    {
        return match($this->shipping_status) {
            'not_requested' => 'غير مطلوب',
            'pending' => 'قيد الانتظار',
            'processing' => 'قيد التجهيز',
            'printed' => 'تم الطباعة',
            'shipped' => 'تم الشحن',
            'delivered' => 'تم التسليم',
            'cancelled' => 'ملغي',
            default => 'غير معروف',
        };
    }

    /**
     * Get human-readable document type.
     */
    public function getDocumentTypeNameAttribute(): string
    {
        $types = [
            'passport' => 'جواز سفر',
            'id_card' => 'بطاقة هوية',
            'birth_certificate' => 'شهادة ميلاد',
            'marriage_certificate' => 'شهادة زواج',
            'divorce_certificate' => 'شهادة طلاق',
            'death_certificate' => 'شهادة وفاة',
            'academic_certificate' => 'شهادة أكاديمية',
            'transcript' => 'كشف درجات',
            'diploma' => 'دبلوم',
            'degree' => 'شهادة جامعية',
            'contract' => 'عقد',
            'power_of_attorney' => 'توكيل',
            'court_order' => 'حكم محكمة',
            'medical_report' => 'تقرير طبي',
            'police_clearance' => 'شهادة حسن سيرة وسلوك',
            'other' => 'أخرى',
        ];

        return $types[$this->document_type] ?? $this->document_type;
    }

    /**
     * Get the reviewer partner assigned to this document.
     */
    public function reviewerPartner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'reviewer_partner_id');
    }

    /**
     * Get all assignments for this document.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(DocumentAssignment::class, 'document_id');
    }

    /**
     * Get the locked/winning assignment.
     */
    public function lockedAssignment(): BelongsTo
    {
        return $this->belongsTo(DocumentAssignment::class, 'locked_assignment_id');
    }

    /**
     * Get current active assignment.
     */
    public function activeAssignment(): HasOne
    {
        return $this->hasOne(DocumentAssignment::class, 'document_id')
            ->where('status', 'accepted')
            ->latestOfMany();
    }

    /**
     * Scope for documents needing partner assignment.
     */
    public function scopeNeedingPartnerAssignment($query)
    {
        return $query->where('status', 'paid')
                    ->whereNull('certified_partner_id');
    }

    /**
     * Scope for documents awaiting reviewer assignment.
     */
    public function scopeAwaitingReviewer($query)
    {
        return $query->whereIn('assignment_status', ['pending', 'awaiting_reviewer'])
                    ->whereNull('locked_assignment_id');
    }

    /**
     * Scope for documents in review.
     */
    public function scopeInReview($query)
    {
        return $query->where('assignment_status', 'in_review')
                    ->whereNotNull('reviewer_partner_id');
    }

    /**
     * Scope for escalated documents (exceeded max attempts).
     */
    public function scopeEscalated($query)
    {
        return $query->where('assignment_status', 'escalated')
                    ->where('assignment_attempts', '>=', config('ct.max_assignment_attempts', 7));
    }

    /**
     * Scope for documents ready for partner stamp.
     */
    public function scopeReadyForPartnerStamp($query)
    {
        return $query->where('status', 'translated')
                    ->whereNotNull('certified_partner_id')
                    ->where('partner_stamp_applied', false);
    }

    /**
     * Scope for documents ready for shipping.
     */
    public function scopeReadyForShipping($query)
    {
        return $query->where('physical_copy_requested', true)
                    ->where('partner_stamp_applied', true)
                    ->where('status', 'completed')
                    ->where('shipping_status', 'pending');
    }

    /**
     * Check if document needs assignment.
     */
    public function needsAssignment(): bool
    {
        return in_array($this->assignment_status, ['pending', 'awaiting_reviewer', null])
            && is_null($this->locked_assignment_id);
    }

    /**
     * Check if document is assigned to a reviewer.
     */
    public function isAssigned(): bool
    {
        return !is_null($this->reviewer_partner_id) && $this->assignment_status === 'in_review';
    }

    /**
     * Get assignment status label.
     */
    public function getAssignmentStatusLabelAttribute(): string
    {
        return match($this->assignment_status) {
            'pending' => 'Pending Assignment',
            'awaiting_reviewer' => 'Awaiting Reviewer',
            'assigned' => 'Offer Sent',
            'in_review' => 'Under Review',
            'approved' => 'Review Approved',
            'certified' => 'Certified',
            'escalated' => 'Escalated to Admin',
            'failed' => 'Assignment Failed',
            default => 'Unknown',
        };
    }
}
