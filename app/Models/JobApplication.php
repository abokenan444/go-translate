<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class JobApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'job_posting_id',
        'full_name',
        'email',
        'phone',
        'linkedin_url',
        'portfolio_url',
        'cover_letter',
        'resume_path',
        'resume_original_name',
        'additional_documents',
        'years_of_experience',
        'current_position',
        'current_company',
        'expected_salary',
        'notice_period',
        'additional_info',
        'status',
        'admin_notes',
        'rating',
        'ip_address',
        'user_agent',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'additional_documents' => 'array',
        'reviewed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($application) {
            // Delete resume file when application is deleted
            if ($application->resume_path && Storage::exists($application->resume_path)) {
                Storage::delete($application->resume_path);
            }
            
            // Delete additional documents
            if ($application->additional_documents) {
                foreach ($application->additional_documents as $doc) {
                    if (isset($doc['path']) && Storage::exists($doc['path'])) {
                        Storage::delete($doc['path']);
                    }
                }
            }
        });
    }

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function markAsReviewed(User $user): void
    {
        $this->update([
            'reviewed_at' => now(),
            'reviewed_by' => $user->id,
        ]);
    }

    public function getResumeUrlAttribute(): ?string
    {
        if (!$this->resume_path) {
            return null;
        }
        
        return Storage::url($this->resume_path);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReviewing($query)
    {
        return $query->where('status', 'reviewing');
    }

    public function scopeShortlisted($query)
    {
        return $query->where('status', 'shortlisted');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
