<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerDiscount extends Model
{
    protected $fillable = [
        'partner_id', 'discount_type', 'discount_value',
        'applies_to', 'service_types', 'min_order_value',
        'max_discount_amount', 'starts_at', 'ends_at', 'is_active',
    ];

    protected $casts = [
        'service_types' => 'array',
        'discount_value' => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        
        if ($this->starts_at > $now) {
            return false;
        }

        if ($this->ends_at && $this->ends_at < $now) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $amount, string $serviceType = null): float
    {
        if (!$this->isValid()) {
            return 0;
        }

        if ($this->min_order_value && $amount < $this->min_order_value) {
            return 0;
        }

        if ($this->applies_to === 'specific_services') {
            if (!$serviceType || !in_array($serviceType, $this->service_types ?? [])) {
                return 0;
            }
        }

        $discount = $this->discount_type === 'percentage'
            ? $amount * ($this->discount_value / 100)
            : $this->discount_value;

        if ($this->max_discount_amount) {
            $discount = min($discount, $this->max_discount_amount);
        }

        return round($discount, 2);
    }
}
