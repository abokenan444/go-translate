<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use App\Models\MinutesWallet;
use App\Models\MobileInvite;
use App\Models\MobileNotification;
use App\Models\MobileWalletTransaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'device_name' => 'nullable|string|max:255',
            'referral_code' => 'nullable|string|max:20',
            'invite_code' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $referralCode = strtoupper(trim((string) $request->input('referral_code', '')));
        $inviteCode = strtoupper(trim((string) $request->input('invite_code', '')));

        $rewardMinutes = (int) config('livecall.referral_reward_minutes', 5);
        $rewardMinutes = max(0, $rewardMinutes);

        $user = DB::transaction(function () use ($request, $referralCode, $inviteCode, $rewardMinutes) {
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
                'name' => $request->string('name'),
                'email' => $request->string('email'),
                'password' => Hash::make($request->string('password')),
                'account_type' => 'customer',
                'status' => 'active',
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

        $tokenName = $request->string('device_name')->toString() ?: 'mobile';
        $token = $user->createToken($tokenName)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registered',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'account_type' => $user->account_type,
            ],
        ], 201);
    }

    private function generateUniqueReferralCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->string('email'))->first();

        if (!$user || !Hash::check($request->string('password'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $tokenName = $request->string('device_name')->toString() ?: 'mobile';
        $token = $user->createToken($tokenName)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Logged in',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'account_type' => $user->account_type,
            ],
        ], 200);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'account_type' => $user->account_type,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->user()?->currentAccessToken();
        if ($token) {
            $token->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out',
        ]);
    }
}
