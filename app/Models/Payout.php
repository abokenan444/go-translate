<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'affiliate_id', 'amount', 'currency', 'period', 'status', 'details', 'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'details' => 'array',
        'paid_at' => 'datetime',
    ];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }
}
