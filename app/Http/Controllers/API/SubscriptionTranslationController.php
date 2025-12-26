<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\CulturalPromptEngine;
use App\Models\TranslationCache;
use App\Models\UserSubscription;
use App\Models\UsageRecord;

class SubscriptionTranslationController extends Controller
{
    protected $culturalEngine;

    public function __construct(CulturalPromptEngine $culturalEngine)
    {
        $this->culturalEngine = $culturalEngine;
    }

    /**
     * Translate with subscription-based billing
     */
    public function translate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:100000',
            'source_lang' => 'nullable|string|max:10',
            'target_lang' => 'required|string|max:10',
            'content_type' => 'nullable|string|in:business,creative,technical,casual,formal',
            'industry' => 'nullable|string',
            'tone' => 'nullable|string',
            'culture_code' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();
        $subscription = $request->attributes->get('subscription');
        $plan = $subscription->pricingPlan;

        $sourceText = $request->input('text');
        $sourceLang = $request->input('source_lang', 'auto');
        $targetLang = $request->input('target_lang');
        $contentType = $request->input('content_type');
        
        // Check character limit
        $charCount = mb_strlen($sourceText);
        if ($charCount > $plan->max_chars_per_translation) {
            return response()->json([
                'success' => false,
                'message' => 'Text exceeds plan limit',
                'char_count' => $charCount,
                'max_allowed' => $plan->max_chars_per_translation,
                'action' => 'upgrade',
            ], 413);
        }

        // Check if language is allowed
        if (!$plan->allowsLanguage($targetLang)) {
            return response()->json([
                'success' => false,
                'message' => 'Language not allowed in current plan',
                'target_lang' => $targetLang,
                'action' => 'upgrade',
            ], 403);
        }

        $startTime = microtime(true);
        $fromCache = false;
        $translatedText = null;

        // Try cache first
        $cachedTranslation = TranslationCache::findTranslation(
            $sourceText,
            $sourceLang,
            $targetLang,
            $contentType
        );

        if ($cachedTranslation) {
            $translatedText = $cachedTranslation;
            $fromCache = true;
        } else {
            // Perform actual translation
            try {
                $translatedText = $this->culturalEngine->translateWithContext(
                    $sourceText,
                    $sourceLang,
                    $targetLang,
                    $request->input('content_type'),
                    $request->input('industry'),
                    $request->input('tone'),
                    $request->input('culture_code')
                );

                // Store in cache
                TranslationCache::store(
                    $sourceText,
                    $translatedText,
                    $sourceLang,
                    $targetLang,
                    $contentType
                );
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Translation failed',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }

        $responseTime = round((microtime(true) - $startTime) * 1000, 2);
        $wordCount = str_word_count($sourceText);

        // Calculate cost based on plan
        $cost = $fromCache ? 0 : $plan->calculateCost($charCount, $wordCount);

        // Record usage and deduct balance
        $usageRecord = $subscription->recordUsage($cost, [
            'service_type' => 'api_translation',
            'source_lang' => $sourceLang,
            'target_lang' => $targetLang,
            'character_count' => $charCount,
            'word_count' => $wordCount,
            'from_cache' => $fromCache,
            'unit_price' => $fromCache ? 0 : ($cost / max($charCount, 1)),
            'pricing_model' => $plan->type === 'pay_per_use' ? 'pay_per_use' : 'subscription',
            'ip_address' => $request->ip(),
            'metadata' => [
                'content_type' => $contentType,
                'response_time_ms' => $responseTime,
                'plan_name' => $plan->name,
            ],
        ]);

        // Refresh subscription to get updated values
        $subscription->refresh();

        return response()->json([
            'success' => true,
            'data' => [
                'translated_text' => $translatedText,
                'source_lang' => $sourceLang,
                'target_lang' => $targetLang,
                'from_cache' => $fromCache,
                'response_time_ms' => $responseTime,
            ],
            'usage' => [
                'character_count' => $charCount,
                'word_count' => $wordCount,
                'cost' => round($cost, 4),
                'monthly_usage' => $subscription->monthly_usage_count,
                'monthly_limit' => $plan->monthly_translation_limit,
                'remaining' => $plan->monthly_translation_limit 
                    ? $plan->monthly_translation_limit - $subscription->monthly_usage_count 
                    : null,
            ],
            'billing' => [
                'credit_balance' => round($subscription->credit_balance, 2),
                'current_balance' => round($subscription->current_balance, 2),
                'plan_type' => $plan->type,
                'plan_name' => $plan->name,
            ],
        ]);
    }

    /**
     * Get subscription status and usage
     */
    public function getStatus(Request $request)
    {
        $user = $request->user();
        $subscription = UserSubscription::getCurrentPlan($user->id);

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscription',
                'has_subscription' => false,
            ]);
        }

        $plan = $subscription->pricingPlan;

        return response()->json([
            'success' => true,
            'subscription' => [
                'status' => $subscription->status,
                'plan_name' => $plan->name,
                'plan_type' => $plan->type,
                'starts_at' => $subscription->starts_at->toDateTimeString(),
                'expires_at' => $subscription->expires_at?->toDateTimeString(),
                'monthly_usage' => $subscription->monthly_usage_count,
                'total_usage' => $subscription->total_usage_count,
                'monthly_limit' => $plan->monthly_translation_limit,
                'remaining' => $plan->monthly_translation_limit 
                    ? $plan->monthly_translation_limit - $subscription->monthly_usage_count 
                    : null,
            ],
            'billing' => [
                'credit_balance' => round($subscription->credit_balance, 2),
                'current_balance' => round($subscription->current_balance, 2),
                'auto_recharge' => $subscription->auto_recharge,
            ],
            'limits' => [
                'max_chars_per_translation' => $plan->max_chars_per_translation,
                'daily_limit' => $plan->daily_translation_limit,
                'monthly_limit' => $plan->monthly_translation_limit,
            ],
            'features' => [
                'api_access' => $plan->has_api_access,
                'bulk_translation' => $plan->has_bulk_translation,
                'advanced_features' => $plan->has_advanced_features,
                'priority_support' => $plan->has_priority_support,
            ],
        ]);
    }

    /**
     * Get usage statistics
     */
    public function getUsageStats(Request $request)
    {
        $user = $request->user();
        $period = $request->input('period', 'month'); // today, week, month, year

        $stats = UsageRecord::getStatistics($user->id, $period);

        return response()->json([
            'success' => true,
            'period' => $period,
            'statistics' => $stats,
        ]);
    }

    /**
     * Add credits to account
     */
    public function addCredits(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1|max:10000',
            'payment_method' => 'required|string',
            'transaction_id' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();
        $subscription = UserSubscription::getCurrentPlan($user->id);

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscription found',
            ], 404);
        }

        // TODO: Process payment with payment gateway
        // For now, just add credits (integrate with Stripe/PayPal later)

        $amount = $request->input('amount');
        $subscription->addCredits($amount);

        return response()->json([
            'success' => true,
            'message' => 'Credits added successfully',
            'credit_balance' => round($subscription->credit_balance, 2),
            'amount_added' => $amount,
        ]);
    }
}
