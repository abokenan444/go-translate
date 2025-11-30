<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlossaryTerm extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id', 'user_id', 'language', 'term', 'preferred', 'forbidden', 'context', 'metadata',
    ];

    protected $casts = [
        'forbidden' => 'boolean',
        'metadata' => 'array',
    ];
}
