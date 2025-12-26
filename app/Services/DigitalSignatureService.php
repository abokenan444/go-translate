<?php

namespace App\Services;

use App\Models\DigitalSignature;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Digital Signature Service
 * 
 * Handles PKI-based digital signatures for certificates and translations
 * Provides legally binding cryptographic signatures
 */
class DigitalSignatureService
{
    protected string $privateKeyPath;
    protected string $publicKeyPath;
    protected string $certificatePath;

    public function __construct()
    {
        $this->privateKeyPath = storage_path('app/keys/platform_private.pem');
        $this->publicKeyPath = storage_path('app/keys/platform_public.pem');
        $this->certificatePath = storage_path('app/keys/platform_certificate.pem');

        $this->ensureKeysExist();
    }

    /**
     * Sign a document or certificate
     *
     * @param mixed $signable
     * @param int $signerId
     * @param string $signerRole
     * @return DigitalSignature
     */
    public function sign($signable, int $signerId, string $signerRole = 'platform'): DigitalSignature
    {
        $signableType = get_class($signable);
        $signableId = $signable->id;

        // Prepare data to sign
        $dataToSign = $this->prepareDataForSigning($signable);
        
        // Generate hash
        $hash = hash('sha256', $dataToSign);

        // Create signature
        $privateKey = openssl_pkey_get_private(
            file_get_contents($this->privateKeyPath),
            config('app.signature_passphrase', '')
        );

        openssl_sign($dataToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        openssl_free_key($privateKey);

        $signatureValue = base64_encode($signature);

        // Get public key and certificate chain
        $publicKey = file_get_contents($this->publicKeyPath);
        $certificateChain = $this->getCertificateChain();

        // Store signature
        $digitalSignature = DigitalSignature::create([
            'signable_type' => $signableType,
            'signable_id' => $signableId,
            'signature_value' => $signatureValue,
            'algorithm' => 'RSA-SHA256',
            'public_key' => $publicKey,
            'certificate_chain' => $certificateChain,
            'hash_algorithm' => 'SHA256',
            'signed_data_hash' => $hash,
            'signer_id' => $signerId,
            'signer_role' => $signerRole,
            'signed_at' => now(),
            'expires_at' => now()->addYears(5),
            'is_valid' => true,
            'metadata' => [
                'signing_ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now()->toIso8601String(),
            ]
        ]);

        // Update the signable model
        if (method_exists($signable, 'update')) {
            $signable->update([
                'digital_signature' => $signatureValue,
                'signature_algorithm' => 'RSA-SHA256',
                'signed_at' => now(),
                'signer_identity' => "User:{$signerId}|Role:{$signerRole}",
                'certificate_chain' => $certificateChain,
            ]);
        }

        Log::info("Digital signature created for {$signableType} ID {$signableId}");

        return $digitalSignature;
    }

    /**
     * Verify a digital signature
     *
     * @param mixed $signable
     * @return bool
     */
    public function verify($signable): bool
    {
        $signableType = get_class($signable);
        $signableId = $signable->id;

        $signature = DigitalSignature::where('signable_type', $signableType)
            ->where('signable_id', $signableId)
            ->where('is_valid', true)
            ->latest()
            ->first();

        if (!$signature) {
            return false;
        }

        // Check expiration
        if ($signature->expires_at && $signature->expires_at->isPast()) {
            return false;
        }

        // Prepare data
        $dataToSign = $this->prepareDataForSigning($signable);

        // Verify hash
        $currentHash = hash('sha256', $dataToSign);
        if ($currentHash !== $signature->signed_data_hash) {
            Log::warning("Signature verification failed: hash mismatch for {$signableType} ID {$signableId}");
            return false;
        }

        // Verify signature
        $publicKey = openssl_pkey_get_public($signature->public_key);
        $signatureData = base64_decode($signature->signature_value);

        $verified = openssl_verify($dataToSign, $signatureData, $publicKey, OPENSSL_ALGO_SHA256);
        openssl_free_key($publicKey);

        if ($verified === 1) {
            $signature->update(['verified_at' => now()]);
            return true;
        }

        Log::warning("Signature verification failed for {$signableType} ID {$signableId}");
        return false;
    }

    /**
     * Prepare data for signing
     *
     * @param mixed $signable
     * @return string
     */
    protected function prepareDataForSigning($signable): string
    {
        // Create canonical representation of data
        $data = [
            'type' => get_class($signable),
            'id' => $signable->id,
            'created_at' => $signable->created_at?->toIso8601String(),
        ];

        // Add specific fields based on type
        if (isset($signable->certificate_number)) {
            $data['certificate_number'] = $signable->certificate_number;
            $data['cts_level'] = $signable->cts_level;
            $data['issued_to'] = $signable->issued_to;
        }

        if (isset($signable->source_text)) {
            $data['source_text_hash'] = hash('sha256', $signable->source_text);
            $data['target_text_hash'] = hash('sha256', $signable->target_text ?? '');
        }

        // Sort keys for consistency
        ksort($data);

        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Get certificate chain
     *
     * @return string|null
     */
    protected function getCertificateChain(): ?string
    {
        if (file_exists($this->certificatePath)) {
            return file_get_contents($this->certificatePath);
        }

        return null;
    }

    /**
     * Ensure cryptographic keys exist
     */
    protected function ensureKeysExist(): void
    {
        $keyDir = dirname($this->privateKeyPath);

        if (!is_dir($keyDir)) {
            mkdir($keyDir, 0700, true);
        }

        // Generate keys if they don't exist
        if (!file_exists($this->privateKeyPath) || !file_exists($this->publicKeyPath)) {
            $this->generateKeys();
        }
    }

    /**
     * Generate RSA key pair
     */
    protected function generateKeys(): void
    {
        $config = [
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];

        $privateKey = openssl_pkey_new($config);

        // Export private key
        openssl_pkey_export_to_file(
            $privateKey,
            $this->privateKeyPath,
            config('app.signature_passphrase', '')
        );

        // Export public key
        $publicKeyDetails = openssl_pkey_get_details($privateKey);
        file_put_contents($this->publicKeyPath, $publicKeyDetails['key']);

        // Generate self-signed certificate
        $dn = [
            'countryName' => 'US',
            'stateOrProvinceName' => 'Global',
            'localityName' => 'Internet',
            'organizationName' => 'Cultural Translate',
            'organizationalUnitName' => 'Certification Authority',
            'commonName' => 'Cultural Translate Platform',
            'emailAddress' => 'certificates@culturaltranslate.com'
        ];

        $cert = openssl_csr_new($dn, $privateKey, ['digest_alg' => 'sha256']);
        $x509 = openssl_csr_sign($cert, null, $privateKey, 3650, ['digest_alg' => 'sha256']);

        openssl_x509_export_to_file($x509, $this->certificatePath);

        openssl_free_key($privateKey);

        chmod($this->privateKeyPath, 0600);
        chmod($this->publicKeyPath, 0644);
        chmod($this->certificatePath, 0644);

        Log::info('Digital signature keys generated successfully');
    }

    /**
     * Revoke a signature
     *
     * @param DigitalSignature $signature
     * @param string $reason
     * @return bool
     */
    public function revoke(DigitalSignature $signature, string $reason): bool
    {
        $signature->update([
            'is_valid' => false,
            'metadata' => array_merge($signature->metadata ?? [], [
                'revoked_at' => now()->toIso8601String(),
                'revocation_reason' => $reason,
            ])
        ]);

        Log::info("Digital signature {$signature->id} revoked: {$reason}");

        return true;
    }

    /**
     * Get signature info for display
     *
     * @param mixed $signable
     * @return array|null
     */
    public function getSignatureInfo($signable): ?array
    {
        $signableType = get_class($signable);
        $signableId = $signable->id;

        $signature = DigitalSignature::where('signable_type', $signableType)
            ->where('signable_id', $signableId)
            ->latest()
            ->first();

        if (!$signature) {
            return null;
        }

        return [
            'algorithm' => $signature->algorithm,
            'signed_at' => $signature->signed_at,
            'signer_role' => $signature->signer_role,
            'is_valid' => $signature->is_valid,
            'verified_at' => $signature->verified_at,
            'expires_at' => $signature->expires_at,
            'hash' => substr($signature->signed_data_hash, 0, 16) . '...',
        ];
    }
}
