<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentClassification extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'classification_level',
        'retention_years',
        'retention_expires_at',
        'auto_purge_enabled',
        'last_reviewed_at',
    ];

    protected $casts = [
        'retention_expires_at' => 'datetime',
        'last_reviewed_at' => 'datetime',
        'auto_purge_enabled' => 'boolean',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function isExpired(): bool
    {
        return $this->retention_expires_at && $this->retention_expires_at->isPast();
    }
}
