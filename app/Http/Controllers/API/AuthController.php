<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MinutesWallet;
use App\Models\MobileInvite;
use App\Models\MobileNotification;
use App\Models\MobileWalletTransaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'referral_code' => 'nullable|string|max:20',
            'invite_code' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $referralCode = strtoupper(trim((string) $request->input('referral_code', '')));
        $inviteCode = strtoupper(trim((string) $request->input('invite_code', '')));

        $rewardMinutes = (int) config('livecall.referral_reward_minutes', 5);
        $rewardMinutes = max(0, $rewardMinutes);

        $created = DB::transaction(function () use ($request, $referralCode, $inviteCode, $rewardMinutes) {
            $referrer = null;
            $invite = null;

            if ($inviteCode !== '') {
                $invite = MobileInvite::where('invite_code', $inviteCode)
                    ->where('status', 'pending')
                    ->first();

                if ($invite) {
                    $referrer = User::find($invite->inviter_id);
                }
            }

            if (!$referrer && $referralCode !== '') {
                $referrer = User::where('referral_code', $referralCode)->first();
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'account_type' => 'customer', // Default account type
                'referred_by' => $referrer?->id,
                'referral_code' => $this->generateUniqueReferralCode(),
            ]);

            if ($invite && $referrer) {
                $invite->markAsRegistered($user, (float) $rewardMinutes);
            }

            if ($referrer && $rewardMinutes > 0) {
                $minutesWallet = MinutesWallet::firstOrCreate(
                    ['user_id' => $referrer->id],
                    ['balance_seconds' => 0]
                );
                $minutesWallet->balance_seconds += $rewardMinutes * 60;
                $minutesWallet->save();

                $legacyWallet = Wallet::firstOrCreate(
                    ['user_id' => $referrer->id],
                    ['minutes_balance' => 0]
                );
                $legacyWallet->minutes_balance = ((float) $legacyWallet->minutes_balance) + (float) $rewardMinutes;
                $legacyWallet->save();

                MobileWalletTransaction::create([
                    'user_id' => $referrer->id,
                    'type' => 'referral',
                    'amount' => (float) $rewardMinutes,
                    'balance_after' => (float) floor(((int) $minutesWallet->balance_seconds) / 60),
                    'description' => 'Referral reward',
                    'metadata' => [
                        'referred_user_id' => $user->id,
                        'referred_user_email' => $user->email,
                        'invite_code' => $inviteCode !== '' ? $inviteCode : null,
                        'referral_code' => $referralCode !== '' ? $referralCode : null,
                    ],
                ]);

                MobileNotification::create([
                    'user_id' => $referrer->id,
                    'type' => 'referral_reward',
                    'title' => 'تمت إضافة دقائق هدية',
                    'body' => "حصلت على {$rewardMinutes} دقائق هدية بسبب دعوة صديق للاشتراك.",
                    'data' => [
                        'reward_minutes' => $rewardMinutes,
                        'referred_user_id' => $user->id,
                        'referred_user_name' => $user->name,
                    ],
                ]);

                if ($invite) {
                    $invite->markAsRewarded();
                }
            }

            return $user;
        });

        $user = $created;

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'account_type' => $user->account_type,
                ],
                'token' => $token
            ]
        ], 201);
    }

    private function generateUniqueReferralCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'account_type' => $user->account_type,
                ],
                'token' => $token
            ]
        ], 200);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ], 200);
    }

    /**
     * Get authenticated user profile
     */
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'account_type' => $user->account_type,
                'created_at' => $user->created_at,
            ]
        ], 200);
    }
}
