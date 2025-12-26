<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Webhook extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'url',
        'events',
        'secret',
        'is_active',
        'last_triggered_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'events' => 'array',
        'last_triggered_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
