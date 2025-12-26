<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'affiliate_id', 'conversion_id', 'rate', 'amount', 'currency', 'status', 'eligible_at', 'paid_at', 'metadata',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'amount' => 'decimal:2',
        'eligible_at' => 'datetime',
        'paid_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function conversion()
    {
        return $this->belongsTo(Conversion::class);
    }

    public function scopeEligible($query)
    {
        return $query->where('status', 'eligible');
    }
}
