<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GovernmentDocument extends Model
{
    protected $table = 'government_documents';

    protected $fillable = [
        'verification_id',
        'document_type',
        'original_filename',
        'file_path',
        'file_size',
        'mime_type',
        'is_verified',
        'verified_by',
        'verified_at',
        'verification_notes',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function verification(): BelongsTo
    {
        return $this->belongsTo(GovernmentVerification::class, 'verification_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getTypeLabel(): string
    {
        return match ($this->document_type) {
            'official_id' => 'Official ID',
            'authorization_letter' => 'Authorization Letter',
            'business_card' => 'Business Card',
            'appointment_letter' => 'Appointment Letter',
            'official_website_proof' => 'Website Proof',
            'mou_agreement' => 'MoU/Agreement',
            default => 'Other Document',
        };
    }
}
