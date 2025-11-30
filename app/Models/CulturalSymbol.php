<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CulturalSymbol extends Model
{
    protected $fillable = [
        'key',
        'cultural_profile_id',
        'replacement_text',
        'description',
    ];

    public function culturalProfile(): BelongsTo
    {
        return $this->belongsTo(CulturalProfile::class);
    }
}
