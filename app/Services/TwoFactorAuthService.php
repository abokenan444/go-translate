<?php

namespace App\Services;

use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorAuthService
{
    protected Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Generate a new 2FA secret
     */
    public function generateSecret(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    /**
     * Generate QR code for 2FA setup
     */
    public function generateQrCode(string $email, string $secret): string
    {
        $appName = config('app.name', 'CulturalTranslate');
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            $appName,
            $email,
            $secret
        );

        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);
        return $writer->writeString($qrCodeUrl);
    }

    /**
     * Verify 2FA code
     */
    public function verify(string $secret, string $code): bool
    {
        return $this->google2fa->verifyKey($secret, $code);
    }

    /**
     * Generate recovery codes
     */
    public function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = strtoupper(bin2hex(random_bytes(4))) . '-' . strtoupper(bin2hex(random_bytes(4)));
        }
        return $codes;
    }

    /**
     * Verify recovery code
     */
    public function verifyRecoveryCode($user, string $code): bool
    {
        $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);
        
        if (in_array($code, $recoveryCodes)) {
            // Remove used code
            $recoveryCodes = array_diff($recoveryCodes, [$code]);
            $user->two_factor_recovery_codes = encrypt(json_encode(array_values($recoveryCodes)));
            $user->save();
            
            return true;
        }

        return false;
    }
}
