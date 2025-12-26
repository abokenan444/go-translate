<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class University extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'official_name',
        'country',
        'city',
        'license_number',
        'contact_email',
        'contact_phone',
        'address',
        'is_verified',
        'verified_at',
        'verified_by',
        'status',
        'suspended_at',
        'suspension_reason',
        'max_students',
        'current_students_count',
        'api_enabled',
        'discount_rate',
        'metadata',
        'notes',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'suspended_at' => 'datetime',
        'api_enabled' => 'boolean',
        'discount_rate' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'university_students')
            ->withPivot(['student_id', 'department', 'program', 'status', 'enrolled_at', 'expires_at', 'metadata'])
            ->withTimestamps();
    }

    public function activeStudents(): BelongsToMany
    {
        return $this->students()->wherePivot('status', 'active');
    }

    public function universityStudents(): HasMany
    {
        return $this->hasMany(UniversityStudent::class);
    }

    public function activeUniversityStudents(): HasMany
    {
        return $this->universityStudents()->where('status', 'active');
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function isVerified(): bool
    {
        return $this->is_verified === true;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function canAddMoreStudents(): bool
    {
        return $this->current_students_count < $this->max_students;
    }

    public function incrementStudentCount(): void
    {
        $this->increment('current_students_count');
    }

    public function decrementStudentCount(): void
    {
        $this->decrement('current_students_count');
    }
}
