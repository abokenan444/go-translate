<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiDevChange extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'status',
        'target_path',
        'original_content',
        'proposed_content',
        'diff',
        'command',
        'sql',
        'meta',
        'user_id',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
