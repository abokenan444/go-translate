<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'official_document_id', 'reviewer_id', 'status', 'notes',
        'changes_requested', 'quality_score', 'started_at', 'reviewed_at',
    ];

    protected $casts = [
        'changes_requested' => 'array',
        'quality_score' => 'integer',
        'started_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function officialDocument(): BelongsTo
    {
        return $this->belongsTo(OfficialDocument::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ReviewLog::class);
    }

    public function startReview(): void
    {
        $this->update([
            'status' => 'in_review',
            'started_at' => now(),
        ]);
        $this->logs()->create([
            'reviewer_id' => $this->reviewer_id,
            'action' => 'started_review',
            'created_at' => now(),
        ]);
    }

    public function approve(int $qualityScore): void
    {
        $this->update([
            'status' => 'approved',
            'quality_score' => $qualityScore,
            'reviewed_at' => now(),
        ]);
        $this->logs()->create([
            'reviewer_id' => $this->reviewer_id,
            'action' => 'approved',
            'details' => "Quality score: {$qualityScore}",
            'created_at' => now(),
        ]);
    }
}
