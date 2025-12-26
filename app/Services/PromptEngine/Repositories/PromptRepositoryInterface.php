<?php

namespace App\Services\PromptEngine\Repositories;

interface PromptRepositoryInterface
{
    public function findBest(string $language, string $domain, string $documentType): ?string;

    public function findQA(string $language, string $qaType): ?string;

    public function getBasePrompt(string $language = 'en', string $domain = 'general'): string;

    public function getDefaultQABase(string $language = 'en'): string;
}
