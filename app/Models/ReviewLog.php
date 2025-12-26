<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewLog extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'document_review_id', 'reviewer_id', 'action', 'details', 'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function documentReview(): BelongsTo
    {
        return $this->belongsTo(DocumentReview::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
