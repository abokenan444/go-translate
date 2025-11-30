<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'account_status' => 'active',
            ]);

            // Assign 14-day free trial subscription
            $plan = SubscriptionPlan::where('slug', 'free')->first();
            if (!$plan) {
                $plan = SubscriptionPlan::where('is_active', true)->orderBy('sort_order')->first();
            }
            if ($plan) {
                $startsAt = now();
                $expiresAt = Carbon::now()->addDays(14);
                DB::table('user_subscriptions')->insert([
                    'user_id' => $user->id,
                    'subscription_plan_id' => $plan->id,
                    'status' => 'active',
                    'tokens_used' => 0,
                    'tokens_remaining' => (int)($plan->tokens_limit ?? 0),
                    'starts_at' => $startsAt,
                    'expires_at' => $expiresAt,
                    'last_token_reset_at' => $startsAt,
                    'auto_renew' => false,
                    'low_tokens_notified' => false,
                    'expiry_notified' => false,
                    'cancellation_reason' => null,
                    'metadata' => json_encode(['trial' => true, 'trial_days' => 14]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            Auth::login($user);
            return redirect()->route('dashboard')->with('success', 'تم إنشاء حسابك بنجاح! تم تفعيل تجربة مجانية لمدة 14 يومًا.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['register' => 'حدث خطأ أثناء التسجيل. حاول مرة أخرى.']);
        }
    }

    /**
     * Show the free trial registration form
     */
    public function showTrialForm()
    {
        return view('auth.trial');
    }

    /**
     * Handle free trial registration
     */
    public function registerTrial(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'company' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'account_status' => 'active',
            ]);

            $plan = SubscriptionPlan::where('slug', 'free')->first();
            if (!$plan) {
                $plan = SubscriptionPlan::where('is_active', true)->orderBy('sort_order')->first();
            }
            if ($plan) {
                $startsAt = now();
                $expiresAt = Carbon::now()->addDays(14);
                DB::table('user_subscriptions')->insert([
                    'user_id' => $user->id,
                    'subscription_plan_id' => $plan->id,
                    'status' => 'active',
                    'tokens_used' => 0,
                    'tokens_remaining' => (int)($plan->tokens_limit ?? 0),
                    'starts_at' => $startsAt,
                    'expires_at' => $expiresAt,
                    'last_token_reset_at' => $startsAt,
                    'auto_renew' => false,
                    'low_tokens_notified' => false,
                    'expiry_notified' => false,
                    'cancellation_reason' => null,
                    'metadata' => json_encode(['trial' => true, 'trial_days' => 14]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            Auth::login($user);
            return redirect()->route('dashboard')->with('success', 'مرحباً بك! تم تفعيل تجربتك المجانية لمدة 14 يومًا.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['register' => 'حدث خطأ أثناء التسجيل. حاول مرة أخرى.']);
        }
    }
}
