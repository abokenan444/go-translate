<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerLead extends Model
{
    protected $fillable = [
        'type',
        'name',
        'email',
        'phone',
        'country_code',
        'city',
        'languages',
        'specialties',
        'source',
        'stage',
        'notes',
        'last_contacted_at',
    ];

    protected $casts = [
        'languages' => 'array',
        'specialties' => 'array',
        'last_contacted_at' => 'datetime',
    ];

    public function outreachLogs()
    {
        return $this->hasMany(PartnerOutreachLog::class);
    }
}

class PartnerOutreachLog extends Model
{
    protected $fillable = [
        'partner_lead_id',
        'channel',
        'status',
        'message',
    ];

    public function lead()
    {
        return $this->belongsTo(PartnerLead::class, 'partner_lead_id');
    }
}
