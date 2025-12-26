<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerPayout extends Model
{
    protected $fillable = [
        'partner_id', 'amount', 'status', 'payment_method', 
        'payment_details', 'notes', 'processed_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'array',
        'processed_at' => 'datetime',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
