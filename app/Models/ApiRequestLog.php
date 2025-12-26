<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiRequestLog extends Model
{
    protected $fillable = [
        'api_key_id',
        'endpoint',
        'method',
        'request_data',
        'response_data',
        'status_code',
        'response_time',
        'ip_address',
    ];

    protected $casts = [
        'request_data' => 'array',
        'response_data' => 'array',
        'status_code' => 'integer',
        'response_time' => 'integer',
    ];

    public function apiKey(): BelongsTo
    {
        return $this->belongsTo(ApiKey::class);
    }

    public function scopeSuccessful($query)
    {
        return $query->whereBetween('status_code', [200, 299]);
    }

    public function scopeFailed($query)
    {
        return $query->where('status_code', '>=', 400);
    }
}
