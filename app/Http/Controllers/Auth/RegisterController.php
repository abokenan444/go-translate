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
            'account_type' => 'required|in:customer,affiliate,government,partner,translator,university',
            'company' => 'nullable|string|max:255',
            'terms' => 'accepted',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'status' => 'active',
                'account_status' => 'active',
                'account_type' => $request->account_type,
                'company_name' => $request->company,
            ]);

            // Create Partner record if account type is partner
            if ($request->account_type === 'partner') {
                \App\Models\Partner::create([
                    'user_id' => $user->id,
                    'company_name' => $request->company ?? $request->name,
                    'partner_type' => 'certified_translator', // Default type
                    'status' => 'pending',
                    'is_verified' => false,
                    'commission_rate' => 20.00,
                ]);
                
                // Notify user that application is pending review
                $user->notify(new \App\Notifications\PartnerNotification('application_pending', [
                    'message' => 'Your partner application has been received and is under review.'
                ]));
            }
            
            // Create University record if account type is university
            if ($request->account_type === 'university') {
                \App\Models\University::create([
                    'user_id' => $user->id,
                    'name' => $request->company ?? $request->name,
                    'official_name' => $request->company ?? $request->name,
                    'country' => 'Not specified',
                    'status' => 'pending',
                    'is_verified' => false,
                    'max_students' => 100,
                ]);
                
                // Notify user that application is pending review
                $user->notify(new \App\Notifications\UniversityNotification('application_pending', [
                    'message' => 'Your university registration has been received and is under review.'
                ]));
            }

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
                'status' => 'active',
                'account_status' => 'active',
                'account_type' => 'customer',
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
