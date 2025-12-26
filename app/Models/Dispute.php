<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    protected $fillable = [
        'document_id',
        'user_id',
        'partner_profile_id',
        'reason',
        'status',
        'details',
        'resolved_at',
        'resolved_by',
        'resolution_notes',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function partner()
    {
        return $this->belongsTo(PartnerProfile::class, 'partner_profile_id');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Check if dispute is open
     */
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    /**
     * Resolve the dispute
     */
    public function resolve(int $resolvedBy, ?string $notes = null): void
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolved_by' => $resolvedBy,
            'resolution_notes' => $notes,
        ]);
    }
}
