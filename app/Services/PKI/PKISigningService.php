<?php

namespace App\Services\PKI;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * PKI Signing Service
 * 
 * Handles Public Key Infrastructure (PKI) operations for document signing,
 * certificate verification, and cryptographic operations.
 */
class PKISigningService
{
    protected string $privateKeyPath;
    protected string $publicKeyPath;
    protected string $passphrase;
    
    public function __construct()
    {
        $this->privateKeyPath = storage_path('keys/cts_private.pem');
        $this->publicKeyPath = storage_path('keys/cts_public.pem');
        $this->passphrase = config('app.key');
    }
    
    /**
     * Initialize PKI keys if they don't exist
     *
     * @return array Array with 'private' and 'public' key paths
     */
    public function initializeKeys(): array
    {
        // Ensure keys directory exists
        $keysDir = storage_path('keys');
        if (!is_dir($keysDir)) {
            mkdir($keysDir, 0700, true);
        }
        
        // Check if keys already exist
        if (file_exists($this->privateKeyPath) && file_exists($this->publicKeyPath)) {
            Log::info('PKI keys already exist');
            return [
                'private' => $this->privateKeyPath,
                'public' => $this->publicKeyPath,
                'status' => 'existing',
            ];
        }
        
        // Generate new key pair
        $config = [
            "digest_alg" => "sha512",
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];
        
        // Create private key
        $privateKey = openssl_pkey_new($config);
        
        if (!$privateKey) {
            throw new Exception('Failed to generate private key: ' . openssl_error_string());
        }
        
        // Export private key with passphrase
        openssl_pkey_export($privateKey, $privateKeyPEM, $this->passphrase);
        
        // Export public key
        $publicKeyDetails = openssl_pkey_get_details($privateKey);
        $publicKeyPEM = $publicKeyDetails['key'];
        
        // Save keys to files
        file_put_contents($this->privateKeyPath, $privateKeyPEM);
        file_put_contents($this->publicKeyPath, $publicKeyPEM);
        
        // Set restrictive permissions
        chmod($this->privateKeyPath, 0600);
        chmod($this->publicKeyPath, 0644);
        
        Log::info('PKI keys generated successfully', [
            'private_key' => $this->privateKeyPath,
            'public_key' => $this->publicKeyPath,
        ]);
        
        return [
            'private' => $this->privateKeyPath,
            'public' => $this->publicKeyPath,
            'status' => 'generated',
        ];
    }
    
    /**
     * Sign data with private key
     *
     * @param string $data Data to sign
     * @return string Base64-encoded signature
     */
    public function sign(string $data): string
    {
        if (!file_exists($this->privateKeyPath)) {
            $this->initializeKeys();
        }
        
        // Load private key
        $privateKeyPEM = file_get_contents($this->privateKeyPath);
        $privateKey = openssl_pkey_get_private($privateKeyPEM, $this->passphrase);
        
        if (!$privateKey) {
            throw new Exception('Failed to load private key: ' . openssl_error_string());
        }
        
        // Sign the data
        $signature = '';
        $success = openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA512);
        
        if (!$success) {
            throw new Exception('Failed to sign data: ' . openssl_error_string());
        }
        
        // Clean up
        openssl_free_key($privateKey);
        
        return base64_encode($signature);
    }
    
    /**
     * Verify signature with public key
     *
     * @param string $data Original data
     * @param string $signature Base64-encoded signature
     * @return bool True if signature is valid
     */
    public function verify(string $data, string $signature): bool
    {
        if (!file_exists($this->publicKeyPath)) {
            throw new Exception('Public key not found');
        }
        
        // Load public key
        $publicKeyPEM = file_get_contents($this->publicKeyPath);
        $publicKey = openssl_pkey_get_public($publicKeyPEM);
        
        if (!$publicKey) {
            throw new Exception('Failed to load public key: ' . openssl_error_string());
        }
        
        // Decode signature
        $signatureBinary = base64_decode($signature);
        
        // Verify signature
        $result = openssl_verify($data, $signatureBinary, $publicKey, OPENSSL_ALGO_SHA512);
        
        // Clean up
        openssl_free_key($publicKey);
        
        if ($result === 1) {
            return true;
        } elseif ($result === 0) {
            return false;
        } else {
            throw new Exception('Error verifying signature: ' . openssl_error_string());
        }
    }
    
    /**
     * Sign a document certificate
     *
     * @param array $certificateData Certificate data to sign
     * @return array Certificate data with signature
     */
    public function signCertificate(array $certificateData): array
    {
        // Create canonical representation
        $canonicalData = json_encode($certificateData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        
        // Sign the data
        $signature = $this->sign($canonicalData);
        
        // Add signature and timestamp
        $signedCertificate = array_merge($certificateData, [
            'signature' => $signature,
            'signature_algorithm' => 'RSA-SHA512',
            'signed_at' => now()->toIso8601String(),
            'signed_by' => 'Cultural Translate Platform',
        ]);
        
        Log::info('Certificate signed', [
            'certificate_id' => $certificateData['certificate_id'] ?? 'unknown',
        ]);
        
        return $signedCertificate;
    }
    
    /**
     * Verify a signed certificate
     *
     * @param array $signedCertificate Signed certificate data
     * @return bool True if signature is valid
     */
    public function verifyCertificate(array $signedCertificate): bool
    {
        if (!isset($signedCertificate['signature'])) {
            return false;
        }
        
        // Extract signature
        $signature = $signedCertificate['signature'];
        
        // Remove signature fields to reconstruct original data
        $certificateData = $signedCertificate;
        unset($certificateData['signature']);
        unset($certificateData['signature_algorithm']);
        unset($certificateData['signed_at']);
        unset($certificateData['signed_by']);
        
        // Create canonical representation
        $canonicalData = json_encode($certificateData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        
        // Verify signature
        return $this->verify($canonicalData, $signature);
    }
    
    /**
     * Get public key for external verification
     *
     * @return string Public key PEM
     */
    public function getPublicKey(): string
    {
        if (!file_exists($this->publicKeyPath)) {
            $this->initializeKeys();
        }
        
        return file_get_contents($this->publicKeyPath);
    }
    
    /**
     * Get public key fingerprint for identification
     *
     * @return string SHA-256 fingerprint of public key
     */
    public function getPublicKeyFingerprint(): string
    {
        $publicKey = $this->getPublicKey();
        return hash('sha256', $publicKey);
    }
    
    /**
     * Encrypt data with public key (for secure storage)
     *
     * @param string $data Data to encrypt
     * @return string Base64-encoded encrypted data
     */
    public function encrypt(string $data): string
    {
        if (!file_exists($this->publicKeyPath)) {
            $this->initializeKeys();
        }
        
        $publicKeyPEM = file_get_contents($this->publicKeyPath);
        $publicKey = openssl_pkey_get_public($publicKeyPEM);
        
        if (!$publicKey) {
            throw new Exception('Failed to load public key: ' . openssl_error_string());
        }
        
        $encrypted = '';
        $success = openssl_public_encrypt($data, $encrypted, $publicKey);
        
        openssl_free_key($publicKey);
        
        if (!$success) {
            throw new Exception('Failed to encrypt data: ' . openssl_error_string());
        }
        
        return base64_encode($encrypted);
    }
    
    /**
     * Decrypt data with private key
     *
     * @param string $encryptedData Base64-encoded encrypted data
     * @return string Decrypted data
     */
    public function decrypt(string $encryptedData): string
    {
        if (!file_exists($this->privateKeyPath)) {
            throw new Exception('Private key not found');
        }
        
        $privateKeyPEM = file_get_contents($this->privateKeyPath);
        $privateKey = openssl_pkey_get_private($privateKeyPEM, $this->passphrase);
        
        if (!$privateKey) {
            throw new Exception('Failed to load private key: ' . openssl_error_string());
        }
        
        $encryptedBinary = base64_decode($encryptedData);
        $decrypted = '';
        $success = openssl_private_decrypt($encryptedBinary, $decrypted, $privateKey);
        
        openssl_free_key($privateKey);
        
        if (!$success) {
            throw new Exception('Failed to decrypt data: ' . openssl_error_string());
        }
        
        return $decrypted;
    }
    
    /**
     * Generate a certificate signing request (CSR) for external CA
     *
     * @param array $distinguishedName DN information
     * @return array Array with 'csr' and 'private_key'
     */
    public function generateCSR(array $distinguishedName): array
    {
        $dn = array_merge([
            "countryName" => "US",
            "stateOrProvinceName" => "State",
            "localityName" => "City",
            "organizationName" => "Cultural Translate",
            "organizationalUnitName" => "Translation Services",
            "commonName" => "culturaltranslate.com",
            "emailAddress" => "admin@culturaltranslate.com"
        ], $distinguishedName);
        
        $config = [
            "digest_alg" => "sha512",
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];
        
        // Generate private key
        $privateKey = openssl_pkey_new($config);
        
        // Generate CSR
        $csr = openssl_csr_new($dn, $privateKey, $config);
        
        if (!$csr) {
            throw new Exception('Failed to generate CSR: ' . openssl_error_string());
        }
        
        // Export CSR
        openssl_csr_export($csr, $csrout);
        
        // Export private key
        openssl_pkey_export($privateKey, $privateKeyOut, $this->passphrase);
        
        return [
            'csr' => $csrout,
            'private_key' => $privateKeyOut,
        ];
    }
    
    /**
     * Check if keys are properly initialized
     *
     * @return bool
     */
    public function keysExist(): bool
    {
        return file_exists($this->privateKeyPath) && file_exists($this->publicKeyPath);
    }
}
