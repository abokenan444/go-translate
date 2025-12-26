<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'official_document_id',
        'translated_file_path',
        'certified_file_path',
        'layout_data',
        'ai_engine_version',
        'quality_score',
        'reviewed_by_human',
    ];

    protected $casts = [
        'reviewed_by_human' => 'boolean',
        'quality_score' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the document that owns the translation.
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(OfficialDocument::class, 'official_document_id');
    }

    /**
     * Get layout data as array.
     */
    public function getLayoutDataArray(): ?array
    {
        if (empty($this->layout_data)) {
            return null;
        }

        return json_decode($this->layout_data, true);
    }

    /**
     * Set layout data from array.
     */
    public function setLayoutDataFromArray(array $data): void
    {
        $this->layout_data = json_encode($data);
    }

    /**
     * Check if translation has been reviewed by human.
     */
    public function isHumanReviewed(): bool
    {
        return $this->reviewed_by_human === true;
    }

    /**
     * Mark as human reviewed.
     */
    public function markAsReviewed(): void
    {
        $this->update(['reviewed_by_human' => true]);
    }
}
