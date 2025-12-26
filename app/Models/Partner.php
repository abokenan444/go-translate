<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'legal_name',
        'partner_type',
        'country_code',
        'jurisdiction',
        'company_name',
        'business_type',
        'tax_id',
        'commission_rate',
        'status',
        'rating',
        'total_reviews',
        'acceptance_rate',
        'on_time_rate',
        'max_concurrent_jobs',
        'current_active_jobs',
        'is_verified',
        'verified_at',
        'verified_by',
        'is_suspended',
        'suspended_at',
        'suspension_reason',
        'is_public',
        'public_profile_url',
        'notify_email',
        'notify_sms',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'rating' => 'decimal:2',
        'acceptance_rate' => 'decimal:2',
        'on_time_rate' => 'decimal:2',
        'is_verified' => 'boolean',
        'is_suspended' => 'boolean',
        'is_public' => 'boolean',
        'notify_email' => 'boolean',
        'notify_sms' => 'boolean',
        'verified_at' => 'datetime',
        'suspended_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get partner credentials
     */
    public function credentials(): HasMany
    {
        return $this->hasMany(\App\Models\PartnerCredential::class, 'partner_id');
    }

    /**
     * Get partner language pairs
     */
    public function languages(): HasMany
    {
        return $this->hasMany(\App\Models\PartnerLanguage::class, 'partner_id');
    }

    /**
     * Get document assignments for this partner
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(\App\Models\DocumentAssignment::class, 'partner_id');
    }

    /**
     * Get active assignments
     */
    public function activeAssignments(): HasMany
    {
        return $this->hasMany(\App\Models\DocumentAssignment::class, 'partner_id')
            ->where('status', 'accepted');
    }

    /**
     * Scope for verified partners
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true)
                    ->whereIn('status', ['verified', 'active', 'approved']);
    }

    /**
     * Scope for active (not suspended) partners
     */
    public function scopeActive($query)
    {
        return $query->where('is_suspended', false);
    }

    /**
     * Check if partner is available for new assignments
     */
    public function isAvailable(): bool
    {
        $maxJobs = $this->max_concurrent_jobs ?? 5;
        $currentJobs = $this->current_active_jobs ?? 0;
        
        return !$this->is_suspended 
            && $this->is_verified 
            && $currentJobs < $maxJobs;
    }

    /**
     * Check if partner has valid credentials
     */
    public function hasValidCredentials(): bool
    {
        return $this->credentials()
            ->where('verification_status', 'approved')
            ->where(function($q) {
                $q->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', now());
            })
            ->exists();
    }
}
