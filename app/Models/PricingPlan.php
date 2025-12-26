<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'currency',
        'billing_period',
        'features',
        'is_active',
        'is_featured',
        'sort_order',
        'max_projects',
        'max_pages',
        'max_translations',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'subscription_plan_id');
    }

    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'subscription_plan_id');
    }
}
