<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerLanguage extends Model
{
    protected $fillable = [
        'partner_id',
        'source_lang',
        'target_lang',
        'specialization',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
