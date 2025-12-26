<?php

namespace App\Services\PromptEngine\Repositories;

class JsonPromptRepository implements PromptRepositoryInterface
{
    public function __construct(protected string $jsonPath) {}

    protected array $cache = [];

    protected function load(): array
    {
        if ($this->cache) return $this->cache;

        $raw = file_get_contents($this->jsonPath);
        $data = json_decode($raw, true);

        return $this->cache = $data ?: [];
    }

    public function findBest(string $language, string $domain, string $documentType): ?string
    {
        $data = $this->load();
        $prompts = $data['prompts'] ?? [];

        $language = strtolower($language);
        $domain = strtolower($domain);
        $documentType = strtolower($documentType);

        // Exact match
        foreach ($prompts as $p) {
            if (($p['language'] ?? '') === $language
                && ($p['domain'] ?? '') === $domain
                && ($p['document_type'] ?? '') === $documentType) {
                return $p['prompt'] ?? null;
            }
        }

        // Fallback docType any-domain
        if ($domain !== 'general') {
            foreach ($prompts as $p) {
                if (($p['language'] ?? '') === $language
                    && ($p['document_type'] ?? '') === $documentType
                    && ($p['domain'] ?? '') === 'general') {
                    return $p['prompt'] ?? null;
                }
            }
        }

        // Fallback general any
        foreach ($prompts as $p) {
            if (($p['language'] ?? '') === $language
                && ($p['domain'] ?? '') === 'general'
                && ($p['document_type'] ?? '') === 'any') {
                return $p['prompt'] ?? null;
            }
        }

        return null;
    }

    public function findQA(string $language, string $qaType): ?string
    {
        $data = $this->load();
        $prompts = $data['prompts'] ?? [];

        $language = strtolower($language);
        $qaType = strtolower($qaType);

        foreach ($prompts as $p) {
            if (($p['language'] ?? '') === $language
                && ($p['domain'] ?? '') === 'qa'
                && str_contains(strtolower($p['name'] ?? ''), $qaType)) {
                return $p['prompt'] ?? null;
            }
        }

        return null;
    }

    public function getBasePrompt(string $language = 'en', string $domain = 'general'): string
    {
        $data = $this->load();

        $base = $data['base_prompt'] ?? 'You are a professional translator.';
        $langRules = ($data['language_rules'][strtolower($language)] ?? '');
        $domRules = ($data['domain_rules'][strtolower($domain)] ?? ($data['domain_rules']['general'] ?? ''));

        return trim($base . "\nLANGUAGE_RULES: " . $langRules . "\nDOMAIN_RULES: " . $domRules);
    }

    public function getDefaultQABase(string $language = 'en'): string
    {
        $data = $this->load();
        $langRules = ($data['language_rules'][strtolower($language)] ?? '');

        return trim("You are a senior linguistic reviewer.\nLANGUAGE_RULES: " . $langRules . "\nTASK: Proofread the translation and return corrected translation only.");
    }
}
