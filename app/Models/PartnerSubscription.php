<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'subscription_tier',
        'monthly_quota',
        'api_calls_limit',
        'white_label_enabled',
        'custom_domain_enabled',
        'price',
        'billing_cycle',
        'status',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'white_label_enabled' => 'boolean',
        'custom_domain_enabled' => 'boolean',
        'price' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' 
            && $this->starts_at <= now() 
            && (!$this->ends_at || $this->ends_at >= now());
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where(function($q) {
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>=', now());
            });
    }
}
