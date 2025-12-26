<?php

namespace App\Http\Controllers;

use App\Models\EnterpriseSubscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnterpriseSubscriptionCreated;
use Carbon\Carbon;

class EnterpriseSubscriptionController extends Controller
{
    /**
     * Display enterprise pricing page
     */
    public function pricing()
    {
        return view('enterprise.pricing');
    }

    /**
     * Show enterprise subscription request form
     */
    public function requestForm()
    {
        return view('enterprise.request-form');
    }

    /**
     * Handle enterprise subscription request
     */
    public function submitRequest(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email',
            'company_phone' => 'nullable|string|max:50',
            'company_address' => 'nullable|string',
            'tax_id' => 'nullable|string|max:100',
            'billing_contact_name' => 'required|string|max:255',
            'billing_contact_email' => 'required|email',
            'estimated_monthly_words' => 'nullable|integer|min:0',
            'preferred_plan_type' => 'required|in:pay_as_you_go,committed_volume,hybrid',
            'special_requirements' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Create pending subscription
            $subscription = EnterpriseSubscription::create([
                'user_id' => auth()->user()?->id,
                'company_name' => $validated['company_name'],
                'company_email' => $validated['company_email'],
                'company_phone' => $validated['company_phone'] ?? null,
                'company_address' => $validated['company_address'] ?? null,
                'tax_id' => $validated['tax_id'] ?? null,
                'billing_contact_name' => $validated['billing_contact_name'],
                'billing_contact_email' => $validated['billing_contact_email'],
                'plan_type' => $validated['preferred_plan_type'],
                'status' => 'pending_activation',
                'committed_words_monthly' => $validated['estimated_monthly_words'] ?? null,
                'special_terms' => $validated['special_requirements'] ?? null,
                'current_month_start' => now(),
                'contract_start_date' => now(),
            ]);

            // Send notification to admin
            Mail::to(config('mail.from.address', 'info@culturaltranslate.com'))
                ->send(new EnterpriseSubscriptionCreated($subscription));

            // Send confirmation to customer
            Mail::to($validated['billing_contact_email'])
                ->send(new \App\Mail\EnterpriseSubscriptionConfirmation($subscription));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال طلبك بنجاح. سيتواصل معك فريق المبيعات خلال 24 ساعة.',
                'subscription_code' => $subscription->subscription_code,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Enterprise subscription request failed', [
                'error' => $e->getMessage(),
                'data' => $validated,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء معالجة طلبك. يرجى المحاولة مرة أخرى.',
            ], 500);
        }
    }

    /**
     * Display user's enterprise subscription dashboard
     */
    public function dashboard()
    {
        $subscription = EnterpriseSubscription::where('user_id', auth()->user()?->id)
            ->where('status', 'active')
            ->first();

        if (!$subscription) {
            return redirect()->route('enterprise.request-form')
                ->with('info', 'ليس لديك اشتراك مؤسسي نشط حالياً');
        }

        // Calculate usage statistics
        $usageStats = [
            'words' => [
                'current' => $subscription->current_month_words,
                'committed' => $subscription->committed_words_monthly,
                'percentage' => $subscription->committed_words_monthly > 0 
                    ? round(($subscription->current_month_words / $subscription->committed_words_monthly) * 100, 2)
                    : 0,
            ],
            'cost' => [
                'current' => $subscription->current_month_cost,
                'limit' => $subscription->spending_limit_monthly,
                'percentage' => $subscription->spending_limit_monthly > 0
                    ? round(($subscription->current_month_cost / $subscription->spending_limit_monthly) * 100, 2)
                    : 0,
            ],
            'api_calls' => $subscription->current_month_api_calls,
            'voice_seconds' => $subscription->current_month_voice_seconds,
        ];

        // Get recent usage history
        $usageHistory = DB::table('translation_usage_logs')
            ->where('user_id', auth()->user()?->id)
            ->where('created_at', '>=', $subscription->current_month_start)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('enterprise.dashboard', compact('subscription', 'usageStats', 'usageHistory'));
    }

    /**
     * Get real-time usage API endpoint
     */
    public function getUsage()
    {
        $subscription = EnterpriseSubscription::where('user_id', auth()->user()?->id)
            ->where('status', 'active')
            ->first();

        if (!$subscription) {
            return response()->json(['error' => 'No active subscription'], 404);
        }

        return response()->json([
            'subscription_code' => $subscription->subscription_code,
            'status' => $subscription->status,
            'plan_type' => $subscription->plan_type,
            'current_month' => [
                'words' => $subscription->current_month_words,
                'characters' => $subscription->current_month_characters,
                'api_calls' => $subscription->current_month_api_calls,
                'voice_seconds' => $subscription->current_month_voice_seconds,
                'cost' => $subscription->current_month_cost,
                'start_date' => $subscription->current_month_start->format('Y-m-d'),
            ],
            'limits' => [
                'committed_words' => $subscription->committed_words_monthly,
                'spending_limit' => $subscription->spending_limit_monthly,
            ],
            'pricing' => [
                'per_word' => $subscription->price_per_word,
                'per_character' => $subscription->price_per_character,
                'per_api_call' => $subscription->price_per_api_call,
                'per_voice_second' => $subscription->price_per_voice_second,
                'discount' => $subscription->discount_percentage,
            ],
            'contract' => [
                'start_date' => $subscription->contract_start_date?->format('Y-m-d'),
                'end_date' => $subscription->contract_end_date?->format('Y-m-d'),
                'auto_renew' => $subscription->auto_renew,
            ],
        ]);
    }

    /**
     * Download usage report
     */
    public function downloadReport(Request $request)
    {
        $subscription = EnterpriseSubscription::where('user_id', auth()->user()?->id)
            ->where('status', 'active')
            ->first();

        if (!$subscription) {
            abort(404, 'No active subscription found');
        }

        $format = $request->input('format', 'csv');
        $startDate = $request->input('start_date', $subscription->current_month_start);
        $endDate = $request->input('end_date', now());

        $usageData = DB::table('translation_usage_logs')
            ->where('user_id', auth()->user()?->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        if ($format === 'csv') {
            return $this->generateCSVReport($subscription, $usageData, $startDate, $endDate);
        } elseif ($format === 'json') {
            return $this->generateJSONReport($subscription, $usageData, $startDate, $endDate);
        }

        abort(400, 'Invalid format');
    }

    private function generateCSVReport($subscription, $usageData, $startDate, $endDate)
    {
        $filename = "enterprise-usage-report-{$subscription->subscription_code}-" . now()->format('Y-m-d') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($subscription, $usageData, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['Enterprise Usage Report']);
            fputcsv($file, ['Company', $subscription->company_name]);
            fputcsv($file, ['Subscription Code', $subscription->subscription_code]);
            fputcsv($file, ['Report Period', "$startDate to $endDate"]);
            fputcsv($file, []);
            
            // Column headers
            fputcsv($file, ['Date', 'Type', 'Words', 'Characters', 'API Calls', 'Voice Seconds', 'Cost']);
            
            // Data rows
            foreach ($usageData as $usage) {
                fputcsv($file, [
                    $usage->created_at,
                    $usage->type ?? 'translation',
                    $usage->word_count ?? 0,
                    $usage->character_count ?? 0,
                    $usage->api_call_count ?? 0,
                    $usage->voice_seconds ?? 0,
                    $usage->cost ?? 0,
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function generateJSONReport($subscription, $usageData, $startDate, $endDate)
    {
        $report = [
            'subscription' => [
                'code' => $subscription->subscription_code,
                'company' => $subscription->company_name,
                'plan_type' => $subscription->plan_type,
            ],
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
            'summary' => [
                'total_words' => $usageData->sum('word_count'),
                'total_characters' => $usageData->sum('character_count'),
                'total_api_calls' => $usageData->sum('api_call_count'),
                'total_voice_seconds' => $usageData->sum('voice_seconds'),
                'total_cost' => $usageData->sum('cost'),
            ],
            'usage_details' => $usageData,
        ];

        return response()->json($report);
    }

    /**
     * Update payment method
     */
    public function updatePaymentMethod(Request $request)
    {
        $subscription = EnterpriseSubscription::where('user_id', auth()->user()?->id)
            ->where('status', 'active')
            ->first();

        if (!$subscription) {
            return response()->json(['error' => 'No active subscription'], 404);
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:invoice,credit_card,bank_transfer,stripe',
            'stripe_payment_method_id' => 'required_if:payment_method,stripe',
        ]);

        try {
            $subscription->update([
                'payment_method' => $validated['payment_method'],
            ]);

            if ($validated['payment_method'] === 'stripe' && isset($validated['stripe_payment_method_id'])) {
                // Handle Stripe payment method setup
                // This would integrate with Stripe API
            }

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث طريقة الدفع بنجاح',
            ]);

        } catch (\Exception $e) {
            Log::error('Payment method update failed', [
                'error' => $e->getMessage(),
                'subscription_id' => $subscription->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل تحديث طريقة الدفع',
            ], 500);
        }
    }
}
