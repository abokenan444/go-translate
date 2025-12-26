<?php

namespace App\Services\PromptEngine;

use App\Services\PromptEngine\Repositories\PromptRepositoryInterface;

class PromptEngine
{
    public function __construct(
        protected PromptRepositoryInterface $repo,
        protected PromptClassifier $classifier,
        protected PromptComposer $composer
    ) {}

    /**
     * Build a final prompt string using smart selection (docType + domain + language)
     *
     * @param array $meta [
     *   'source_language' => 'nl',
     *   'target_language' => 'ar',
     *   'document_type'   => 'utility_bill',
     *   'domain'          => 'financial',
     *   'certified'       => true,
     *   'output_mode'     => 'translation_only', // or 'translation_with_notes'
     * ]
     */
    public function build(array $meta): string
    {
        $targetLang = strtolower($meta['target_language'] ?? 'en');
        $documentType = $meta['document_type'] ?? 'any';
        $domain = $meta['domain'] ?? null;

        // If domain not provided, infer from docType + filename hints
        if (!$domain) {
            $domain = $this->classifier->inferDomain($meta);
        }

        // Try: exact match docType+domain+lang
        $prompt = $this->repo->findBest($targetLang, $domain, $documentType);

        // Fallback: docType+lang (any domain)
        if (!$prompt) {
            $prompt = $this->repo->findBest($targetLang, 'general', $documentType);
        }

        // Fallback: domain+lang (general docType)
        if (!$prompt) {
            $prompt = $this->repo->findBest($targetLang, $domain, 'any');
        }

        // Final fallback: base/system prompt
        if (!$prompt) {
            $prompt = $this->repo->getBasePrompt($targetLang, $domain);
        }

        return $this->composer->compose($prompt, $meta);
    }

    /**
     * Run QA prompt selection for post-translation review
     */
    public function buildQA(string $targetLanguage, string $qaType = 'proofread'): string
    {
        $targetLang = strtolower($targetLanguage);
        $qaPrompt = $this->repo->findQA($targetLang, $qaType);

        return $qaPrompt ?: $this->repo->getDefaultQABase($targetLang);
    }
}
