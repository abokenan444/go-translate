<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApiProvider extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'api_key',
        'api_secret',
        'base_url',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function aiModels(): HasMany
    {
        return $this->hasMany(AiModel::class, 'api_provider_id');
    }
}
