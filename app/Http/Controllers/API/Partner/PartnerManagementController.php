<?php

namespace App\Http\Controllers\Api\Partner;

use App\Models\PartnerApiKey;
use App\Models\PartnerProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PartnerManagementController extends PartnerApiController
{
    public function getAccount()
    {
        $partner = $this->getPartner();
        
        return $this->successResponse([
            'id' => $partner->id,
            'company_name' => $partner->company_name,
            'partner_type' => $partner->partner_type,
            'status' => $partner->status,
            'commission_rate' => $partner->commission_rate,
            'discount_rate' => $partner->discount_rate,
            'white_label_enabled' => $partner->canUseWhiteLabel(),
            'custom_domain_enabled' => $partner->canUseCustomDomain(),
        ]);
    }

    public function getSubscription()
    {
        $partner = $this->getPartner();
        $subscription = $partner->activeSubscription;

        if (!$subscription) {
            return $this->errorResponse('NO_SUBSCRIPTION', 'No active subscription found', 404);
        }

        return $this->successResponse([
            'tier' => $subscription->subscription_tier,
            'monthly_quota' => $subscription->monthly_quota,
            'api_calls_limit' => $subscription->api_calls_limit,
            'price' => $subscription->price,
            'billing_cycle' => $subscription->billing_cycle,
            'starts_at' => $subscription->starts_at->toIso8601String(),
            'ends_at' => $subscription->ends_at?->toIso8601String(),
        ]);
    }

    public function getUsage()
    {
        $partner = $this->getPartner();
        $currentMonth = now()->startOfMonth();

        $stats = $partner->usageStats()
            ->where('date', '>=', $currentMonth)
            ->selectRaw('
                SUM(api_calls) as total_api_calls,
                SUM(translations_count) as total_translations,
                SUM(characters_translated) as total_characters,
                SUM(revenue_generated) as total_revenue
            ')
            ->first();

        return $this->successResponse([
            'current_month' => [
                'api_calls' => $stats->total_api_calls ?? 0,
                'translations' => $stats->total_translations ?? 0,
                'characters' => $stats->total_characters ?? 0,
                'revenue' => $stats->total_revenue ?? 0,
            ],
            'quota' => [
                'monthly_quota' => $partner->activeSubscription?->monthly_quota ?? 0,
                'remaining' => max(0, ($partner->activeSubscription?->monthly_quota ?? 0) - ($stats->total_translations ?? 0)),
            ],
        ]);
    }

    public function getEarnings()
    {
        $partner = $this->getPartner();

        $total = $partner->getTotalEarnings();
        $pending = $partner->getPendingEarnings();

        $recentCommissions = $partner->commissions()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($commission) {
                return [
                    'id' => $commission->id,
                    'amount' => $commission->commission_amount,
                    'status' => $commission->status,
                    'order_type' => $commission->order_type,
                    'created_at' => $commission->created_at->toIso8601String(),
                ];
            });

        return $this->successResponse([
            'total_earned' => $total,
            'pending_earnings' => $pending,
            'recent_commissions' => $recentCommissions,
        ]);
    }

    public function listApiKeys()
    {
        $partner = $this->getPartner();
        
        $keys = $partner->apiKeys()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($key) {
                return [
                    'id' => $key->id,
                    'name' => $key->key_name,
                    'api_key' => $key->api_key,
                    'rate_limit' => $key->rate_limit,
                    'is_active' => $key->is_active,
                    'last_used_at' => $key->last_used_at?->toIso8601String(),
                    'created_at' => $key->created_at->toIso8601String(),
                ];
            });

        return $this->successResponse($keys);
    }

    public function createApiKey(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'rate_limit' => 'nullable|integer|min:10|max:10000',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('VALIDATION_ERROR', 'Invalid input', 422, $validator->errors()->toArray());
        }

        $partner = $this->getPartner();
        $rateLimit = $request->rate_limit ?? $partner->activeSubscription?->api_calls_limit ?? 100;

        $apiKey = PartnerApiKey::generate($partner->id, $request->name, $rateLimit);

        return $this->successResponse([
            'id' => $apiKey->id,
            'name' => $apiKey->key_name,
            'api_key' => $apiKey->api_key,
            'api_secret' => decrypt($apiKey->api_secret), // Show only once
            'rate_limit' => $apiKey->rate_limit,
        ], 'API key created successfully. Save the secret securely, it will not be shown again.', 201);
    }

    public function revokeApiKey($id)
    {
        $partner = $this->getPartner();
        
        $apiKey = PartnerApiKey::where('id', $id)
            ->where('partner_id', $partner->id)
            ->first();

        if (!$apiKey) {
            return $this->errorResponse('NOT_FOUND', 'API key not found', 404);
        }

        $apiKey->revoke();

        return $this->successResponse(null, 'API key revoked successfully');
    }

    public function projects(Request $request)
    {
        $partner = $this->getPartner();

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('VALIDATION_ERROR', 'Invalid input', 422, $validator->errors()->toArray());
            }

            $project = PartnerProject::create([
                'partner_id' => $partner->id,
                'name' => $request->name,
                'description' => $request->description,
                'status' => 'active',
            ]);

            return $this->successResponse($project, 'Project created successfully', 201);
        }

        $projects = $partner->projects()->orderBy('created_at', 'desc')->get();
        return $this->successResponse($projects);
    }
}
