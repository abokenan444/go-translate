<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerCommission extends Model
{
    protected $fillable = [
        'partner_id', 'partner_order_id', 'amount', 'type', 'description', 'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function order()
    {
        return $this->belongsTo(PartnerOrder::class, 'partner_order_id');
    }
}
