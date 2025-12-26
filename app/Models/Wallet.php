<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'minutes_balance',
        'payg_enabled',
        'payg_monthly_cap_minutes',
        'trusted_level'
    ];

    protected $casts = [
        'payg_enabled' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
