<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GovernmentVerificationLog extends Model
{
    protected $fillable = [
        'government_verification_id',
        'user_id',
        'action',
        'from_status',
        'to_status',
        'notes',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function verification(): BelongsTo
    {
        return $this->belongsTo(GovernmentVerification::class, 'government_verification_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
