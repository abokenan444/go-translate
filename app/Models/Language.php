<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Language extends Model
{
    protected $fillable = ['code', 'name', 'native_name', 'flag_emoji', 'direction', 'is_active', 'is_default', 'order'];
    protected $casts = ['is_active' => 'boolean', 'is_default' => 'boolean', 'order' => 'integer'];
    public static function getActive() { return static::where('is_active', true)->orderBy('order')->get(); }
    public static function getDefault() { return static::where('is_default', true)->first(); }
}
