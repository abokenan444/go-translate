<?php
/**
 * CulturalTranslate API Client
 */

if (!defined('ABSPATH')) {
    exit;
}

class CulturalTranslate_API_Client {
    
    private $api_key;
    private $api_url;
    
    /**
     * Constructor
     */
    public function __construct($api_key = null, $api_url = null) {
        $this->api_key = $api_key ?: get_option('culturaltranslate_api_key', '');
        $this->api_url = $api_url ?: get_option('culturaltranslate_api_url', 'https://api.culturaltranslate.com');
    }
    
    /**
     * Make API request
     */
    private function request($endpoint, $method = 'GET', $data = array()) {
        $url = trailingslashit($this->api_url) . ltrim($endpoint, '/');
        
        $args = array(
            'method' => $method,
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->api_key,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ),
            'timeout' => 30,
        );
        
        if (!empty($data) && $method !== 'GET') {
            $args['body'] = json_encode($data);
        }
        
        $response = wp_remote_request($url, $args);
        
        if (is_wp_error($response)) {
            return array(
                'success' => false,
                'error' => $response->get_error_message()
            );
        }
        
        $body = wp_remote_retrieve_body($response);
        $status_code = wp_remote_retrieve_response_code($response);
        
        $result = json_decode($body, true);
        
        if ($status_code >= 200 && $status_code < 300) {
            return array_merge(array('success' => true), $result);
        } else {
            return array(
                'success' => false,
                'error' => $result['message'] ?? 'API request failed',
                'status_code' => $status_code
            );
        }
    }
    
    /**
     * Translate text
     */
    public function translate($text, $source_language, $target_language, $options = array()) {
        $data = array_merge(array(
            'text' => $text,
            'source_language' => $source_language,
            'target_language' => $target_language,
        ), $options);
        
        return $this->request('v1/translate', 'POST', $data);
    }
    
    /**
     * Batch translate
     */
    public function batch_translate($translations) {
        return $this->request('v1/translate/batch', 'POST', array(
            'translations' => $translations
        ));
    }
    
    /**
     * Analyze context
     */
    public function analyze_context($text, $source_language, $target_language) {
        return $this->request('v1/analyze-context', 'POST', array(
            'text' => $text,
            'source_language' => $source_language,
            'target_language' => $target_language
        ));
    }
    
    /**
     * Get brand voices
     */
    public function get_brand_voices() {
        return $this->request('v1/brand-voices', 'GET');
    }
    
    /**
     * Create brand voice
     */
    public function create_brand_voice($data) {
        return $this->request('v1/brand-voices', 'POST', $data);
    }
    
    /**
     * Get glossary terms
     */
    public function get_glossary($brand_voice_id) {
        return $this->request("v1/brand-voices/{$brand_voice_id}/glossary", 'GET');
    }
    
    /**
     * Add glossary term
     */
    public function add_glossary_term($brand_voice_id, $data) {
        return $this->request("v1/brand-voices/{$brand_voice_id}/glossary", 'POST', $data);
    }
    
    /**
     * Upload document
     */
    public function upload_document($file_path, $source_language, $target_languages, $document_type, $priority = 'normal') {
        // WordPress doesn't support multipart in wp_remote_request easily
        // We'll use cURL for file upload
        
        if (!function_exists('curl_init')) {
            return array(
                'success' => false,
                'error' => 'cURL is not available'
            );
        }
        
        $url = trailingslashit($this->api_url) . 'v1/documents';
        
        $ch = curl_init($url);
        
        $post_data = array(
            'file' => new CURLFile($file_path),
            'source_language' => $source_language,
            'target_languages' => json_encode($target_languages),
            'document_type' => $document_type,
            'priority' => $priority
        );
        
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $this->api_key
        ));
        
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $result = json_decode($response, true);
        
        if ($status_code >= 200 && $status_code < 300) {
            return array_merge(array('success' => true), $result);
        } else {
            return array(
                'success' => false,
                'error' => $result['message'] ?? 'Document upload failed'
            );
        }
    }
    
    /**
     * Get document status
     */
    public function get_document_status($document_id) {
        return $this->request("v1/documents/{$document_id}", 'GET');
    }
    
    /**
     * Verify certificate
     */
    public function verify_certificate($certificate_id) {
        return $this->request("v1/verify/{$certificate_id}", 'GET');
    }
    
    /**
     * Get usage stats
     */
    public function get_usage_stats($start_date = null, $end_date = null) {
        $params = array();
        if ($start_date) $params['start_date'] = $start_date;
        if ($end_date) $params['end_date'] = $end_date;
        
        $query_string = !empty($params) ? '?' . http_build_query($params) : '';
        
        return $this->request("v1/usage-stats{$query_string}", 'GET');
    }
    
    /**
     * Test API connection
     */
    public function test_connection() {
        return $this->get_usage_stats();
    }
}
