<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TranslatorPerformance extends Model
{
    use HasFactory;

    protected $table = 'translator_performance';

    protected $fillable = [
        'translator_id',
        'overall_score',
        'quality_score',
        'speed_score',
        'reliability_score',
        'communication_score',
        'level',
    ];

    protected $casts = [
        'overall_score' => 'decimal:2',
        'quality_score' => 'decimal:2',
        'speed_score' => 'decimal:2',
        'reliability_score' => 'decimal:2',
        'communication_score' => 'decimal:2',
    ];

    public function translator()
    {
        return $this->belongsTo(User::class, 'translator_id');
    }
}
