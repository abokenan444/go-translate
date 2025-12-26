<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanComparison extends Model
{
    protected $fillable = [
        'feature_name',
        'feature_description',
        'category',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
