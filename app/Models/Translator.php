<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Translator extends Model
{
    use SoftDeletes;

    protected $table = 'translator_profiles';

    protected $fillable = [
        'user_id',
        'languages',
        'specializations',
        'certification_number',
        'years_of_experience',
        'status',
    ];

    protected $casts = [
        'languages' => 'array',
        'specializations' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
