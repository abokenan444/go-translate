<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerWhiteLabel extends Model
{
    protected $fillable = [
        'partner_id', 'domain', 'logo_url', 'primary_color', 
        'secondary_color', 'custom_css', 'is_active'
    ];

    protected $casts = [
        'custom_css' => 'array',
        'is_active' => 'boolean',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
