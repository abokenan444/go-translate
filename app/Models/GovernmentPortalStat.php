<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GovernmentPortalStat extends Model
{
    protected $fillable = [
        'portal_id',
        'date',
        'page_views',
        'unique_visitors',
        'documents_submitted',
        'documents_completed',
        'revenue',
    ];

    protected $casts = [
        'date' => 'date',
        'revenue' => 'decimal:2',
    ];

    public function portal(): BelongsTo
    {
        return $this->belongsTo(GovernmentPortal::class, 'portal_id');
    }
}
