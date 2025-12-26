<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Government extends Model
{
    use SoftDeletes;

    protected $table = 'government_profiles';

    protected $fillable = [
        'user_id',
        'ministry_name',
        'department',
        'official_id',
        'contact_person',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
