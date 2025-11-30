<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrandVoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'brand_voices';

    protected $fillable = [
        'company_id', 'user_id', 'name', 'tone', 'formality',
        'rules', 'vocabulary_use', 'vocabulary_avoid',
    ];

    protected $casts = [
        'rules' => 'array',
        'vocabulary_use' => 'array',
        'vocabulary_avoid' => 'array',
    ];
}
