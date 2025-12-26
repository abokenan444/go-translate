<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PartnerApplication extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'company_name',
        'website',
        'country',
        'company_size',
        'contact_name',
        'job_title',
        'email',
        'phone',
        'partnership_type',
        'monthly_volume',
        'message',
        'status',
        'ip_address',
        'user_agent',
        'recaptcha_score',
    ];

    protected $casts = [
        'recaptcha_score' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'pending',
        'recaptcha_score' => 0.0,
    ];

    /**
     * Scope for pending applications
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved applications
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected applications
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Get the status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'contacted' => 'info',
            default => 'secondary',
        };
    }

    /**
     * Get the partnership type label
     */
    public function getPartnershipTypeLabelAttribute(): string
    {
        return match($this->partnership_type) {
            'reseller' => 'Reseller',
            'referral' => 'Referral',
            'integration' => 'Integration',
            'white_label' => 'White Label',
            default => ucfirst($this->partnership_type),
        };
    }
}
