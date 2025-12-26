<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentGateway extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'display_name',
        'description',
        'logo',
        'supported_currencies',
        'supported_countries',
        'credentials',
        'settings',
        'is_active',
        'is_default',
        'supports_recurring',
        'supports_refunds',
        'min_amount',
        'max_amount',
        'fee_percentage',
        'fee_fixed',
        'environment',
        'webhook_url',
        'webhook_secret',
        'sort_order',
    ];

    protected $casts = [
        'supported_currencies' => 'array',
        'supported_countries' => 'array',
        'credentials' => 'encrypted:array',
        'settings' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'supports_recurring' => 'boolean',
        'supports_refunds' => 'boolean',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'fee_percentage' => 'decimal:2',
        'fee_fixed' => 'decimal:2',
    ];

    protected $hidden = [
        'credentials',
        'webhook_secret',
    ];
}
