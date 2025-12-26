<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GovContact extends Model
{
    protected $table = 'gov_contacts';

    protected $fillable = [
        'gov_entity_id',
        'user_id',
        'full_name',
        'title',
        'email',
        'phone',
        'department',
        'role',
        'is_primary',
        'can_approve_uploads',
        'can_view_compliance',
        'status',
        'activated_at'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'can_approve_uploads' => 'boolean',
        'can_view_compliance' => 'boolean',
        'activated_at' => 'datetime'
    ];

    public function entity(): BelongsTo
    {
        return $this->belongsTo(GovEntity::class, 'gov_entity_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function canApprove(): bool
    {
        return $this->isActive() && $this->can_approve_uploads;
    }
}
