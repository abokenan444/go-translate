<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerOutreachLog extends Model
{
    protected $fillable = [
        'partner_lead_id',
        'channel',
        'status',
        'message',
    ];

    public function partnerLead(): BelongsTo
    {
        return $this->belongsTo(PartnerLead::class);
    }
}
