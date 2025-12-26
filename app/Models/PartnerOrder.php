<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerOrder extends Model
{
    protected $fillable = [
        'partner_id', 'user_id', 'order_number', 'service_type',
        'source_language', 'target_language', 'source_text', 'translated_text',
        'word_count', 'price', 'commission', 'status', 'metadata'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'commission' => 'decimal:2',
        'metadata' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = 'PO-' . strtoupper(uniqid());
            }
        });
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
