<?php

namespace App\Services\Security;

class MemoryEncryptionService
{
    protected string $cipher;
    protected array $keys;
    protected string $activeId;

    public function __construct()
    {
        $cfg = config('memory_encryption');
        $this->cipher = $cfg['cipher'];
        $this->keys = $cfg['keys'];
        $this->activeId = $cfg['active_key_id'];
    }

    protected function resolveKey(?string $id): ?string
    {
        $id = $id ?: $this->activeId;
        $key = $this->keys[$id] ?? null;
        if (!$key) return null;
        // Normalize key length (derive via hash if shorter)
        return hash('sha256', $key, true); // 32 bytes
    }

    public function encrypt(?string $plain, ?string &$keyIdRef): ?string
    {
        if ($plain === null) return null;
        $keyIdRef = $keyIdRef ?: $this->activeId;
        $key = $this->resolveKey($keyIdRef);
        if (!$key) return $plain; // fallback no encryption if missing key
        $iv = random_bytes(12);
        $tag = '';
        $ciphertext = openssl_encrypt($plain, $this->cipher, $key, OPENSSL_RAW_DATA, $iv, $tag);
        if ($ciphertext === false) return $plain;
        return base64_encode($keyIdRef.'::'.base64_encode($iv).'::'.base64_encode($tag).'::'.base64_encode($ciphertext));
    }

    public function decrypt(?string $payload, ?string $keyId): ?string
    {
        if ($payload === null) return null;
        $decoded = base64_decode($payload, true);
        if ($decoded === false) return $payload;
        $parts = explode('::', $decoded);
        if (count($parts) !== 4) return $payload;
        [$storedId, $b64Iv, $b64Tag, $b64Cipher] = $parts;
        $key = $this->resolveKey($storedId);
        if (!$key) return $payload; // unknown key
        $iv = base64_decode($b64Iv);
        $tag = base64_decode($b64Tag);
        $ciphertext = base64_decode($b64Cipher);
        $plain = openssl_decrypt($ciphertext, $this->cipher, $key, OPENSSL_RAW_DATA, $iv, $tag);
        return $plain === false ? $payload : $plain;
    }

    public function rotate(string $fromId, string $toId): array
    {
        $fromKey = $this->resolveKey($fromId);
        $toKey = $this->resolveKey($toId);
        if (!$fromKey || !$toKey) return ['success'=>false,'error'=>'Missing keys'];
        // Rotation handled by artisan command iterating records.
        return ['success'=>true];
    }
}
