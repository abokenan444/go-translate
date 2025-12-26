<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPosting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'requirements',
        'responsibilities',
        'location',
        'type',
        'department',
        'salary_min',
        'salary_max',
        'currency',
        'status',
        'application_deadline',
        'contact_email',
        'benefits',
    ];

    protected $casts = [
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'application_deadline' => 'datetime',
        'benefits' => 'array',
        'published_at' => 'datetime',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('application_deadline')
                    ->orWhere('application_deadline', '>=', now());
            });
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open')
            ->where(function ($q) {
                $q->whereNull('application_deadline')
                    ->orWhere('application_deadline', '>=', now());
            });
    }

    public function isOpen()
    {
        return $this->status === 'open' && 
               (is_null($this->application_deadline) || $this->application_deadline >= now());
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function incrementApplications()
    {
        $this->increment('applications_count');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
