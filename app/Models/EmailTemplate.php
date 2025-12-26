<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'subject_en',
        'subject_ar',
        'body_en',
        'body_ar',
        'variables',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'variables' => 'array',
    ];

    /**
     * Scope for active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get template by code.
     */
    public static function findByCode(string $code): ?self
    {
        return static::where('code', $code)->first();
    }

    /**
     * Get subject based on locale.
     */
    public function getSubject(string $locale = 'en'): string
    {
        return $locale === 'ar' ? $this->subject_ar : $this->subject_en;
    }

    /**
     * Get body based on locale.
     */
    public function getBody(string $locale = 'en'): string
    {
        return $locale === 'ar' ? $this->body_ar : $this->body_en;
    }

    /**
     * Render template with variables.
     */
    public function render(array $data, string $locale = 'en'): array
    {
        $subject = $this->getSubject($locale);
        $body = $this->getBody($locale);

        foreach ($data as $key => $value) {
            $subject = str_replace('{{' . $key . '}}', $value, $subject);
            $body = str_replace('{{' . $key . '}}', $value, $body);
        }

        return [
            'subject' => $subject,
            'body' => $body,
        ];
    }
}
