<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TwoFactorAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    protected TwoFactorAuthService $twoFactorService;

    public function __construct(TwoFactorAuthService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    /**
     * Enable 2FA for user
     */
    public function enable(Request $request)
    {
        $user = Auth::user();

        if ($user->two_factor_secret) {
            return response()->json([
                'success' => false,
                'message' => '2FA is already enabled'
            ], 400);
        }

        $secret = $this->twoFactorService->generateSecret();
        $qrCode = $this->twoFactorService->generateQrCode($user->email, $secret);
        $recoveryCodes = $this->twoFactorService->generateRecoveryCodes();

        // Store temporarily (will be confirmed after verification)
        session([
            'two_factor_secret' => $secret,
            'two_factor_recovery_codes' => $recoveryCodes,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'qr_code' => $qrCode,
                'secret' => $secret,
                'recovery_codes' => $recoveryCodes,
            ],
        ]);
    }

    /**
     * Confirm 2FA setup
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $secret = session('two_factor_secret');
        $recoveryCodes = session('two_factor_recovery_codes');

        if (!$secret) {
            return response()->json([
                'success' => false,
                'message' => 'No 2FA setup in progress'
            ], 400);
        }

        if (!$this->twoFactorService->verify($secret, $request->code)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code'
            ], 422);
        }

        $user = Auth::user();
        $user->two_factor_secret = encrypt($secret);
        $user->two_factor_recovery_codes = encrypt(json_encode($recoveryCodes));
        $user->two_factor_confirmed_at = now();
        $user->save();

        session()->forget(['two_factor_secret', 'two_factor_recovery_codes']);

        return response()->json([
            'success' => true,
            'message' => '2FA enabled successfully',
        ]);
    }

    /**
     * Disable 2FA
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = Auth::user();
        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->two_factor_confirmed_at = null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => '2FA disabled successfully',
        ]);
    }

    /**
     * Verify 2FA code during login
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $user = $request->user() ?? session('two_factor_login_user');

        if (!$user || !$user->two_factor_secret) {
            return response()->json([
                'success' => false,
                'message' => '2FA not enabled'
            ], 400);
        }

        $secret = decrypt($user->two_factor_secret);
        $isValid = $this->twoFactorService->verify($secret, $request->code);

        if (!$isValid) {
            // Try recovery code
            $isValid = $this->twoFactorService->verifyRecoveryCode($user, $request->code);
        }

        if (!$isValid) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code'
            ], 422);
        }

        // Complete login
        Auth::login($user);
        session()->forget('two_factor_login_user');

        return response()->json([
            'success' => true,
            'message' => 'Verified successfully',
        ]);
    }

    /**
     * Get 2FA status
     */
    public function status()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => [
                'enabled' => !is_null($user->two_factor_confirmed_at),
                'confirmed_at' => $user->two_factor_confirmed_at,
            ],
        ]);
    }

    /**
     * Regenerate recovery codes
     */
    public function regenerateRecoveryCodes(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = Auth::user();

        if (!$user->two_factor_secret) {
            return response()->json([
                'success' => false,
                'message' => '2FA is not enabled'
            ], 400);
        }

        $recoveryCodes = $this->twoFactorService->generateRecoveryCodes();
        $user->two_factor_recovery_codes = encrypt(json_encode($recoveryCodes));
        $user->save();

        return response()->json([
            'success' => true,
            'data' => [
                'recovery_codes' => $recoveryCodes,
            ],
        ]);
    }
}
