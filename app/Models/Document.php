<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'original_content',
        'translated_content',
        'source_language',
        'target_language',
        'user_id',
        'partner_id',
        'translator_id',
        'status',
        'document_type',
        'file_path',
        'file_size',
        'file_type',
        'file_hash',
        'metadata',
        'translation_quality_score',
        'cultural_adaptation_score',
        'completion_time',
        'word_count',
        'character_count',
        'classification',
        'retention_category',
        'retention_until',
        'is_certified',
        'certification_date',
        'certification_number',
        'notes'
    ];

    protected $casts = [
        'metadata' => 'array',
        'translation_quality_score' => 'decimal:2',
        'cultural_adaptation_score' => 'decimal:2',
        'completion_time' => 'integer',
        'word_count' => 'integer',
        'character_count' => 'integer',
        'file_size' => 'integer',
        'is_certified' => 'boolean',
        'certification_date' => 'datetime',
        'retention_until' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function translator()
    {
        return $this->belongsTo(User::class, 'translator_id');
    }

    public function disputes()
    {
        return $this->hasMany(DocumentDispute::class);
    }

    public function classifications()
    {
        return $this->hasMany(DocumentClassification::class);
    }

    public function evidenceChain()
    {
        return $this->hasMany(EvidenceChain::class)->orderBy('created_at');
    }

    public function governmentVerifications()
    {
        return $this->hasMany(GovernmentVerification::class);
    }

    public function reviews()
    {
        return $this->hasMany(DocumentReview::class);
    }

    public function certificates()
    {
        return $this->hasMany(DocumentCertificate::class);
    }

    // Scopes

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCertified($query)
    {
        return $query->where('is_certified', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    public function scopeByLanguagePair($query, $sourceLanguage, $targetLanguage)
    {
        return $query->where('source_language', $sourceLanguage)
                     ->where('target_language', $targetLanguage);
    }

    // Helper Methods

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isCertified()
    {
        return $this->is_certified === true;
    }

    public function hasDispute()
    {
        return $this->disputes()->where('status', '!=', 'resolved')->exists();
    }

    public function getQualityScoreAttribute()
    {
        return $this->attributes['translation_quality_score'] ?? 0;
    }

    public function getCulturalScoreAttribute()
    {
        return $this->attributes['cultural_adaptation_score'] ?? 0;
    }

    public function getAverageScoreAttribute()
    {
        return ($this->quality_score + $this->cultural_score) / 2;
    }

    public function markAsCompleted()
    {
        $this->status = 'completed';
        $this->save();
    }

    public function markAsInProgress()
    {
        $this->status = 'in_progress';
        $this->save();
    }

    public function assignToTranslator($translatorId)
    {
        $this->translator_id = $translatorId;
        $this->status = 'assigned';
        $this->save();
    }

    public function calculateWordCount()
    {
        if (!$this->content) {
            return 0;
        }
        
        return str_word_count(strip_tags($this->content));
    }

    public function calculateCharacterCount()
    {
        if (!$this->content) {
            return 0;
        }
        
        return mb_strlen(strip_tags($this->content));
    }

    public function shouldBeRetained()
    {
        if (!$this->retention_until) {
            return true;
        }
        
        return now()->lt($this->retention_until);
    }

    public function isExpired()
    {
        return !$this->shouldBeRetained();
    }

    public function verifyFileIntegrity()
    {
        if (!$this->file_path || !$this->file_hash) {
            return false;
        }

        if (!file_exists(storage_path('app/' . $this->file_path))) {
            return false;
        }

        $currentHash = hash_file('sha256', storage_path('app/' . $this->file_path));
        return $currentHash === $this->file_hash;
    }
}
