<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiModel extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'api_provider_id',
        'model_identifier',
        'description',
        'is_active',
        'capabilities',
        'provider_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capabilities' => 'array',
    ];

    public function apiProvider(): BelongsTo
    {
        return $this->belongsTo(ApiProvider::class, 'api_provider_id');
    }
}
