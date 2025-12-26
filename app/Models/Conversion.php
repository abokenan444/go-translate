<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{
    use HasFactory;

    protected $fillable = [
        'affiliate_id', 'referral_link_id', 'type', 'user_id', 'order_id', 'amount', 'currency', 'converted_at', 'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'converted_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function link()
    {
        return $this->belongsTo(ReferralLink::class, 'referral_link_id');
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }
}
