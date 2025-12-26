<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformIntegration extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'icon',
        'is_active',
        'config'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'array'
    ];

    /**
     * Get user integrations for this platform
     */
    public function userIntegrations()
    {
        return $this->hasMany(UserIntegration::class, 'platform_integration_id');
    }
}
