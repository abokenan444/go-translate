<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class PKISigningService
{
    protected string $privateKeyPath;
    protected string $publicKeyPath;

    public function __construct()
    {
        $this->privateKeyPath = storage_path('keys/cts_private.pem');
        $this->publicKeyPath = storage_path('keys/cts_public.pem');

        // Generate keys if they don't exist
        if (!file_exists($this->privateKeyPath)) {
            $this->generateKeys();
        }
    }

    /**
     * Sign data with private key
     */
    public function sign(string $data): string
    {
        if (!file_exists($this->privateKeyPath)) {
            throw new \Exception('Private key not found');
        }

        $privateKey = openssl_pkey_get_private(file_get_contents($this->privateKeyPath));
        
        if (!$privateKey) {
            throw new \Exception('Failed to load private key');
        }

        openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        
        return base64_encode($signature);
    }

    /**
     * Verify signature with public key
     */
    public function verify(string $data, string $signature): bool
    {
        if (!file_exists($this->publicKeyPath)) {
            throw new \Exception('Public key not found');
        }

        $publicKey = openssl_pkey_get_public(file_get_contents($this->publicKeyPath));
        
        if (!$publicKey) {
            throw new \Exception('Failed to load public key');
        }

        $result = openssl_verify($data, base64_decode($signature), $publicKey, OPENSSL_ALGO_SHA256);
        
        return $result === 1;
    }

    /**
     * Generate RSA key pair
     */
    protected function generateKeys(): void
    {
        $keysDir = storage_path('keys');
        
        if (!is_dir($keysDir)) {
            mkdir($keysDir, 0700, true);
        }

        $config = [
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];

        $res = openssl_pkey_new($config);
        
        // Extract private key
        openssl_pkey_export($res, $privateKey);
        file_put_contents($this->privateKeyPath, $privateKey);
        chmod($this->privateKeyPath, 0600);

        // Extract public key
        $publicKey = openssl_pkey_get_details($res);
        file_put_contents($this->publicKeyPath, $publicKey['key']);
        chmod($this->publicKeyPath, 0644);
    }

    /**
     * Get public key content
     */
    public function getPublicKey(): string
    {
        if (!file_exists($this->publicKeyPath)) {
            throw new \Exception('Public key not found');
        }

        return file_get_contents($this->publicKeyPath);
    }
}
