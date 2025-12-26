<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvidenceChain extends Model
{
    use HasFactory;

    protected $table = 'evidence_chain';

    protected $fillable = [
        'document_id',
        'event_type',
        'event_data',
        'actor_type',
        'actor_id',
        'ip_address',
        'user_agent',
        'metadata',
        'hash',
        'previous_hash',
    ];

    protected $casts = [
        'event_data' => 'array',
        'metadata' => 'array',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    /**
     * Calculate hash for this event
     */
    public function calculateHash(): string
    {
        $data = [
            $this->document_id,
            $this->event_type,
            json_encode($this->event_data),
            $this->actor_id,
            $this->created_at,
            $this->previous_hash,
        ];

        return hash('sha256', implode('|', $data));
    }
}
