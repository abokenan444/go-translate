<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'affiliate_id', 'slug', 'destination_url', 'utm_source', 'utm_medium', 'utm_campaign', 'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function clicks()
    {
        return $this->hasMany(Click::class);
    }

    public function conversions()
    {
        return $this->hasMany(Conversion::class);
    }
}
