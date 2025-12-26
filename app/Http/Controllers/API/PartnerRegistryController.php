<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerRegistryController extends Controller
{
    /**
     * Get public partner registry
     */
    public function index(Request $request)
    {
        $query = Partner::where('status', 'active')
            ->where('is_public', true);

        // Filter by certification level
        if ($request->has('certification')) {
            $query->where('certification_level', $request->certification);
        }

        // Filter by specialization
        if ($request->has('specialization')) {
            $query->where('specializations', 'like', '%' . $request->specialization . '%');
        }

        // Filter by language pair
        if ($request->has('source_language') && $request->has('target_language')) {
            $query->whereJsonContains('language_pairs', [
                'source' => $request->source_language,
                'target' => $request->target_language,
            ]);
        }

        $partners = $query->orderBy('overall_rating', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $partners->items(),
            'pagination' => [
                'current_page' => $partners->currentPage(),
                'per_page' => $partners->perPage(),
                'total' => $partners->total(),
                'last_page' => $partners->lastPage(),
            ]
        ]);
    }

    /**
     * Get partner details
     */
    public function show(Partner $partner)
    {
        if (!$partner->is_public || $partner->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Partner not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $partner->id,
                'name' => $partner->name,
                'description' => $partner->description,
                'logo' => $partner->logo,
                'certification_level' => $partner->certification_level,
                'certified_at' => $partner->certified_at,
                'specializations' => $partner->specializations,
                'language_pairs' => $partner->language_pairs,
                'rating' => [
                    'overall' => $partner->overall_rating,
                    'quality' => $partner->quality_rating,
                    'speed' => $partner->speed_rating,
                    'communication' => $partner->communication_rating,
                    'total_reviews' => $partner->total_reviews,
                ],
                'stats' => [
                    'total_projects' => $partner->total_projects,
                    'completed_projects' => $partner->completed_projects,
                    'success_rate' => $partner->success_rate,
                ],
                'verified' => $partner->is_verified,
                'member_since' => $partner->created_at,
            ]
        ]);
    }

    /**
     * Get certified partners
     */
    public function certified(Request $request)
    {
        $partners = Partner::where('status', 'active')
            ->where('is_public', true)
            ->whereNotNull('certification_level')
            ->orderBy('certification_level', 'desc')
            ->orderBy('overall_rating', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $partners->items(),
            'pagination' => [
                'current_page' => $partners->currentPage(),
                'per_page' => $partners->perPage(),
                'total' => $partners->total(),
                'last_page' => $partners->lastPage(),
            ]
        ]);
    }
}
