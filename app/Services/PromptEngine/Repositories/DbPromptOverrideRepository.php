<?php

namespace App\Services\PromptEngine\Repositories;

use Illuminate\Support\Facades\DB;

class DbPromptOverrideRepository
{
    public function findOverride(string $language, string $domain, string $documentType): ?string
    {
        $row = DB::table('prompt_overrides')
            ->where('is_active', true)
            ->where('language', strtolower($language))
            ->where('domain', strtolower($domain))
            ->where('document_type', strtolower($documentType))
            ->orderByDesc('id')
            ->first();

        return $row?->prompt;
    }
}
