<?php

namespace App\Http\Controllers\Api\Partner;

use App\Models\Translation;
use App\Models\PartnerProject;
use App\Models\PartnerCommission;
use App\Jobs\ProcessTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TranslationApiController extends PartnerApiController
{
    /**
     * Create a new translation
     * 
     * POST /api/v1/partner/translations
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:50000',
            'source_language' => 'required|string|size:2',
            'target_language' => 'required|string|size:2',
            'project_id' => 'nullable|exists:partner_projects,id',
            'reference' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                'VALIDATION_ERROR',
                'Invalid input data',
                422,
                $validator->errors()->toArray()
            );
        }

        $partner = $this->getPartner();

        // Check subscription quota
        if (!$this->checkQuota($partner)) {
            return $this->errorResponse(
                'QUOTA_EXCEEDED',
                'Monthly translation quota exceeded. Please upgrade your subscription.',
                403
            );
        }

        // Calculate price with partner discount
        $characterCount = mb_strlen($request->text);
        $basePrice = $this->calculatePrice($characterCount);
        $discount = $partner->discount_rate ?? 0;
        $finalPrice = $basePrice * (1 - $discount / 100);

        // Create translation
        $translation = Translation::create([
            'user_id' => $partner->user_id,
            'text' => $request->text,
            'source_language' => $request->source_language,
            'target_language' => $request->target_language,
            'character_count' => $characterCount,
            'price' => $finalPrice,
            'status' => 'processing',
            'partner_id' => $partner->id,
            'partner_reference' => $request->reference,
        ]);

        // Update project if specified
        if ($request->project_id) {
            $project = PartnerProject::find($request->project_id);
            if ($project && $project->partner_id == $partner->id) {
                $project->increment('total_translations');
                $project->increment('total_revenue', $finalPrice);
            }
        }

        // Create commission record
        $commissionRate = $partner->commission_rate ?? 10;
        PartnerCommission::create([
            'partner_id' => $partner->id,
            'order_id' => $translation->id,
            'order_type' => 'translation',
            'order_amount' => $finalPrice,
            'commission_rate' => $commissionRate,
            'commission_amount' => $finalPrice * ($commissionRate / 100),
            'status' => 'pending',
        ]);

        // Dispatch translation job
        ProcessTranslation::dispatch($translation);

        return $this->successResponse([
            'id' => $translation->id,
            'status' => $translation->status,
            'character_count' => $characterCount,
            'price' => $finalPrice,
            'discount_applied' => $discount,
            'estimated_completion' => now()->addMinutes(2)->toIso8601String(),
        ], 'Translation created successfully', 201);
    }

    /**
     * Get translation status
     * 
     * GET /api/v1/partner/translations/{id}
     */
    public function show($id)
    {
        $partner = $this->getPartner();
        
        $translation = Translation::where('id', $id)
            ->where('partner_id', $partner->id)
            ->first();

        if (!$translation) {
            return $this->errorResponse(
                'NOT_FOUND',
                'Translation not found',
                404
            );
        }

        return $this->successResponse([
            'id' => $translation->id,
            'status' => $translation->status,
            'source_language' => $translation->source_language,
            'target_language' => $translation->target_language,
            'character_count' => $translation->character_count,
            'price' => $translation->price,
            'translated_text' => $translation->translated_text,
            'created_at' => $translation->created_at->toIso8601String(),
            'completed_at' => $translation->completed_at?->toIso8601String(),
        ]);
    }

    /**
     * List translations
     * 
     * GET /api/v1/partner/translations
     */
    public function index(Request $request)
    {
        $partner = $this->getPartner();
        
        $query = Translation::where('partner_id', $partner->id)
            ->orderBy('created_at', 'desc');

        // Filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $perPage = min($request->get('per_page', 20), 100);
        $translations = $query->paginate($perPage);

        return $this->paginatedResponse($translations);
    }

    /**
     * Bulk translate
     * 
     * POST /api/v1/partner/translations/bulk
     */
    public function bulk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'translations' => 'required|array|min:1|max:100',
            'translations.*.text' => 'required|string|max:50000',
            'translations.*.source_language' => 'required|string|size:2',
            'translations.*.target_language' => 'required|string|size:2',
            'translations.*.reference' => 'nullable|string|max:255',
            'project_id' => 'nullable|exists:partner_projects,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                'VALIDATION_ERROR',
                'Invalid input data',
                422,
                $validator->errors()->toArray()
            );
        }

        $partner = $this->getPartner();
        $results = [];

        foreach ($request->translations as $item) {
            // Create each translation
            $characterCount = mb_strlen($item['text']);
            $basePrice = $this->calculatePrice($characterCount);
            $discount = $partner->discount_rate ?? 0;
            $finalPrice = $basePrice * (1 - $discount / 100);

            $translation = Translation::create([
                'user_id' => $partner->user_id,
                'text' => $item['text'],
                'source_language' => $item['source_language'],
                'target_language' => $item['target_language'],
                'character_count' => $characterCount,
                'price' => $finalPrice,
                'status' => 'processing',
                'partner_id' => $partner->id,
                'partner_reference' => $item['reference'] ?? null,
            ]);

            ProcessTranslation::dispatch($translation);

            $results[] = [
                'id' => $translation->id,
                'reference' => $item['reference'] ?? null,
                'status' => 'queued',
            ];
        }

        return $this->successResponse([
            'total' => count($results),
            'translations' => $results,
        ], 'Bulk translation created successfully', 201);
    }

    /**
     * Check quota
     */
    private function checkQuota($partner): bool
    {
        $subscription = $partner->activeSubscription;
        
        if (!$subscription) {
            return false;
        }

        if ($subscription->subscription_tier === 'enterprise') {
            return true; // Unlimited
        }

        $currentMonth = now()->startOfMonth();
        $monthlyCount = Translation::where('partner_id', $partner->id)
            ->where('created_at', '>=', $currentMonth)
            ->count();

        return $monthlyCount < $subscription->monthly_quota;
    }

    /**
     * Calculate price
     */
    private function calculatePrice(int $characterCount): float
    {
        // Base price: $0.01 per character
        return $characterCount * 0.01;
    }
}
