<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerEarning extends Model
{
    protected $fillable = [
        'partner_profile_id',
        'document_id',
        'currency',
        'amount_cents',
        'status',
        'approved_at',
        'paid_at',
    ];

    protected $casts = [
        'amount_cents' => 'integer',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function partnerProfile(): BelongsTo
    {
        return $this->belongsTo(PartnerProfile::class);
    }

    public function getAmountAttribute(): float
    {
        return $this->amount_cents / 100;
    }
}
