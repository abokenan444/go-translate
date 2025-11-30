<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Culture extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'region',
        'primary_language',
        'traits',
    ];

    protected $casts = [
        'traits' => 'array',
    ];

    public function translations()
    {
        return $this->hasMany(Translation::class);
    }
}
