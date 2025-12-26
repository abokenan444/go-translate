<?php
/**
 * CulturalTranslate PHP SDK
 * 
 * Official PHP SDK for CulturalTranslate API
 * 
 * @package CulturalTranslate
 * @version 1.0.0
 * @author CulturalTranslate
 * @license MIT
 */

namespace CulturalTranslate;

/**
 * Main Client Class
 */
class Client {
    
    /**
     * API Key
     * @var string
     */
    private $apiKey;
    
    /**
     * API Base URL
     * @var string
     */
    private $baseUrl;
    
    /**
     * Request timeout in seconds
     * @var int
     */
    private $timeout;
    
    /**
     * HTTP Client
     * @var HttpClient
     */
    private $httpClient;
    
    /**
     * Constructor
     * 
     * @param string $apiKey API Key
     * @param array $options Configuration options
     */
    public function __construct(string $apiKey, array $options = []) {
        $this->apiKey = $apiKey;
        $this->baseUrl = $options['base_url'] ?? 'https://api.culturaltranslate.com';
        $this->timeout = $options['timeout'] ?? 30;
        
        $this->httpClient = new HttpClient($this->baseUrl, $this->apiKey, $this->timeout);
    }
    
    /**
     * Translate text
     * 
     * @param string $text Text to translate
     * @param string $sourceLanguage Source language code
     * @param string $targetLanguage Target language code
     * @param array $options Additional options
     * @return array Translation result
     * @throws CulturalTranslateException
     */
    public function translate(
        string $text,
        string $sourceLanguage,
        string $targetLanguage,
        array $options = []
    ): array {
        $data = array_merge([
            'text' => $text,
            'source_language' => $sourceLanguage,
            'target_language' => $targetLanguage,
        ], $options);
        
        return $this->httpClient->post('v1/translate', $data);
    }
    
    /**
     * Batch translate multiple texts
     * 
     * @param array $translations Array of translation requests
     * @return array Translation results
     * @throws CulturalTranslateException
     */
    public function batchTranslate(array $translations): array {
        return $this->httpClient->post('v1/translate/batch', [
            'translations' => $translations
        ]);
    }
    
    /**
     * Analyze text context (7 layers)
     * 
     * @param string $text Text to analyze
     * @param string $sourceLanguage Source language code
     * @param string $targetLanguage Target language code
     * @return array Context analysis result
     * @throws CulturalTranslateException
     */
    public function analyzeContext(
        string $text,
        string $sourceLanguage,
        string $targetLanguage
    ): array {
        return $this->httpClient->post('v1/analyze-context', [
            'text' => $text,
            'source_language' => $sourceLanguage,
            'target_language' => $targetLanguage
        ]);
    }
    
    /**
     * Upload document for translation
     * 
     * @param string $filePath Path to file
     * @param string $sourceLanguage Source language code
     * @param array $targetLanguages Array of target language codes
     * @param string $documentType Document type
     * @param string $priority Priority level (normal, high, urgent)
     * @return array Upload result
     * @throws CulturalTranslateException
     */
    public function uploadDocument(
        string $filePath,
        string $sourceLanguage,
        array $targetLanguages,
        string $documentType,
        string $priority = 'normal'
    ): array {
        if (!file_exists($filePath)) {
            throw new CulturalTranslateException("File not found: {$filePath}");
        }
        
        return $this->httpClient->uploadFile('v1/documents', $filePath, [
            'source_language' => $sourceLanguage,
            'target_languages' => json_encode($targetLanguages),
            'document_type' => $documentType,
            'priority' => $priority
        ]);
    }
    
    /**
     * Get document status
     * 
     * @param int $documentId Document ID
     * @return array Document status
     * @throws CulturalTranslateException
     */
    public function getDocumentStatus(int $documentId): array {
        return $this->httpClient->get("v1/documents/{$documentId}");
    }
    
    /**
     * Verify certificate
     * 
     * @param string $certificateId Certificate ID
     * @return array Verification result
     * @throws CulturalTranslateException
     */
    public function verifyCertificate(string $certificateId): array {
        return $this->httpClient->get("v1/verify/{$certificateId}");
    }
    
    /**
     * Get brand voices
     * 
     * @return array Brand voices
     * @throws CulturalTranslateException
     */
    public function getBrandVoices(): array {
        return $this->httpClient->get('v1/brand-voices');
    }
    
    /**
     * Create brand voice
     * 
     * @param array $data Brand voice data
     * @return array Created brand voice
     * @throws CulturalTranslateException
     */
    public function createBrandVoice(array $data): array {
        return $this->httpClient->post('v1/brand-voices', $data);
    }
    
    /**
     * Get glossary terms
     * 
     * @param int $brandVoiceId Brand voice ID
     * @return array Glossary terms
     * @throws CulturalTranslateException
     */
    public function getGlossary(int $brandVoiceId): array {
        return $this->httpClient->get("v1/brand-voices/{$brandVoiceId}/glossary");
    }
    
    /**
     * Add glossary term
     * 
     * @param int $brandVoiceId Brand voice ID
     * @param array $data Glossary term data
     * @return array Created glossary term
     * @throws CulturalTranslateException
     */
    public function addGlossaryTerm(int $brandVoiceId, array $data): array {
        return $this->httpClient->post("v1/brand-voices/{$brandVoiceId}/glossary", $data);
    }
    
    /**
     * Get usage statistics
     * 
     * @param string|null $startDate Start date (Y-m-d)
     * @param string|null $endDate End date (Y-m-d)
     * @return array Usage statistics
     * @throws CulturalTranslateException
     */
    public function getUsageStats(?string $startDate = null, ?string $endDate = null): array {
        $params = [];
        if ($startDate) $params['start_date'] = $startDate;
        if ($endDate) $params['end_date'] = $endDate;
        
        return $this->httpClient->get('v1/usage-stats', $params);
    }
    
    /**
     * Configure webhook
     * 
     * @param string $url Webhook URL
     * @param array $events Events to subscribe to
     * @param string $secret Webhook secret
     * @return array Webhook configuration
     * @throws CulturalTranslateException
     */
    public function configureWebhook(string $url, array $events, string $secret): array {
        return $this->httpClient->post('v1/webhooks', [
            'url' => $url,
            'events' => $events,
            'secret' => $secret
        ]);
    }
    
    /**
     * Test API connection
     * 
     * @return bool True if connection successful
     */
    public function testConnection(): bool {
        try {
            $this->getUsageStats();
            return true;
        } catch (CulturalTranslateException $e) {
            return false;
        }
    }
}

/**
 * HTTP Client Class
 */
class HttpClient {
    
    private $baseUrl;
    private $apiKey;
    private $timeout;
    
    public function __construct(string $baseUrl, string $apiKey, int $timeout) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
        $this->timeout = $timeout;
    }
    
    /**
     * Make GET request
     */
    public function get(string $endpoint, array $params = []): array {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        return $this->request('GET', $url);
    }
    
    /**
     * Make POST request
     */
    public function post(string $endpoint, array $data = []): array {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        return $this->request('POST', $url, $data);
    }
    
    /**
     * Upload file
     */
    public function uploadFile(string $endpoint, string $filePath, array $data = []): array {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        
        $ch = curl_init($url);
        
        $postData = $data;
        $postData['file'] = new \CURLFile($filePath);
        
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiKey
            ]
        ]);
        
        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new CulturalTranslateException("cURL error: {$error}");
        }
        
        return $this->handleResponse($response, $statusCode);
    }
    
    /**
     * Make HTTP request
     */
    private function request(string $method, string $url, array $data = null): array {
        $ch = curl_init($url);
        
        $headers = [
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json',
            'Accept: application/json'
        ];
        
        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => $method
        ];
        
        if ($data !== null && $method !== 'GET') {
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        }
        
        curl_setopt_array($ch, $options);
        
        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new CulturalTranslateException("cURL error: {$error}");
        }
        
        return $this->handleResponse($response, $statusCode);
    }
    
    /**
     * Handle API response
     */
    private function handleResponse(string $response, int $statusCode): array {
        $data = json_decode($response, true);
        
        if ($statusCode >= 200 && $statusCode < 300) {
            return $data;
        }
        
        $message = $data['message'] ?? $data['error'] ?? 'API request failed';
        throw new CulturalTranslateException($message, $statusCode);
    }
}

/**
 * Custom Exception Class
 */
class CulturalTranslateException extends \Exception {
    
    public function __construct(string $message, int $code = 0, \Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * Helper function for quick translation
 * 
 * @param string $text Text to translate
 * @param string $sourceLanguage Source language
 * @param string $targetLanguage Target language
 * @param string $apiKey API key
 * @return string Translated text
 */
function translate(
    string $text,
    string $sourceLanguage,
    string $targetLanguage,
    string $apiKey
): string {
    $client = new Client($apiKey);
    $result = $client->translate($text, $sourceLanguage, $targetLanguage);
    return $result['translation'] ?? '';
}
