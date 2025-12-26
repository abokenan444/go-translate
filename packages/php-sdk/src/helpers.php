<?php
/**
 * Helper functions for CulturalTranslate PHP SDK
 */

if (!function_exists('ct_translate')) {
    /**
     * Quick translation helper
     * 
     * @param string $text Text to translate
     * @param string $sourceLanguage Source language
     * @param string $targetLanguage Target language
     * @param string|null $apiKey API key (uses CT_API_KEY env var if not provided)
     * @return string Translated text
     */
    function ct_translate(
        string $text,
        string $sourceLanguage,
        string $targetLanguage,
        ?string $apiKey = null
    ): string {
        $apiKey = $apiKey ?? getenv('CT_API_KEY') ?? '';
        
        if (empty($apiKey)) {
            throw new \Exception('API key not provided');
        }
        
        return \CulturalTranslate\translate($text, $sourceLanguage, $targetLanguage, $apiKey);
    }
}

if (!function_exists('ct_verify')) {
    /**
     * Verify certificate helper
     * 
     * @param string $certificateId Certificate ID
     * @param string|null $apiKey API key
     * @return array Verification result
     */
    function ct_verify(string $certificateId, ?string $apiKey = null): array {
        $apiKey = $apiKey ?? getenv('CT_API_KEY') ?? '';
        
        if (empty($apiKey)) {
            throw new \Exception('API key not provided');
        }
        
        $client = new \CulturalTranslate\Client($apiKey);
        return $client->verifyCertificate($certificateId);
    }
}

if (!function_exists('ct_analyze')) {
    /**
     * Analyze context helper
     * 
     * @param string $text Text to analyze
     * @param string $sourceLanguage Source language
     * @param string $targetLanguage Target language
     * @param string|null $apiKey API key
     * @return array Context analysis
     */
    function ct_analyze(
        string $text,
        string $sourceLanguage,
        string $targetLanguage,
        ?string $apiKey = null
    ): array {
        $apiKey = $apiKey ?? getenv('CT_API_KEY') ?? '';
        
        if (empty($apiKey)) {
            throw new \Exception('API key not provided');
        }
        
        $client = new \CulturalTranslate\Client($apiKey);
        return $client->analyzeContext($text, $sourceLanguage, $targetLanguage);
    }
}
