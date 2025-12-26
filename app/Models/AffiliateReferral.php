<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AffiliateReferral extends Model
{
    use HasFactory;

    protected $fillable = [
        'affiliate_id',
        'referral_code',
        'campaign_name',
        'description',
        'clicks_count',
        'conversions_count',
        'status',
    ];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function clicks()
    {
        return $this->hasMany(AffiliateClick::class, 'referral_id');
    }

    public function conversions()
    {
        return $this->hasMany(AffiliateConversion::class, 'referral_id');
    }

    public function commissions()
    {
        return $this->hasMany(AffiliateCommission::class, 'referral_id');
    }

    public function getFullLinkAttribute()
    {
        return url('/register?ref=' . $this->referral_code);
    }

    public function getConversionRateAttribute()
    {
        if ($this->clicks_count == 0) return 0;
        return round(($this->conversions_count / $this->clicks_count) * 100, 2);
    }
}
