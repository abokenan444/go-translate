<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndustryTemplate extends Model
{
    protected $table = 'industry_templates';

    protected $fillable = [
        'key',
        'name',
        'description',
        'locale',
        'prompt_template',
    ];
}
