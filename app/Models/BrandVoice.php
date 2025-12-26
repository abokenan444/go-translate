<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrandVoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'name',
        'tone',
        'values',
        'examples',
        'guidelines',
        'is_active',
    ];

    protected $casts = [
        'values' => 'array',
        'examples' => 'array',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function getToneOptions(): array
    {
        return [
            'formal' => 'Formal',
            'friendly' => 'Friendly',
            'bold' => 'Bold',
            'corporate' => 'Corporate',
            'professional' => 'Professional',
            'casual' => 'Casual',
            'enthusiastic' => 'Enthusiastic',
        ];
    }
}
