<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'category',
    ];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value): self
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
