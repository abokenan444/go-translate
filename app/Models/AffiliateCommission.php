<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AffiliateCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'affiliate_id',
        'referral_id',
        'user_id',
        'amount',
        'commission_rate',
        'subscription_plan',
        'status',
        'payout_id',
        'earned_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'earned_at' => 'datetime',
    ];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function referral()
    {
        return $this->belongsTo(AffiliateReferral::class, 'referral_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
