<?php

namespace App\Http\Controllers\Api\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class PartnerApiController extends Controller
{
    /**
     * Return success response
     */
    protected function successResponse($data, string $message = null, int $status = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $data,
        ];

        if ($message) {
            $response['message'] = $message;
        }

        $response['meta'] = $this->getMeta();

        return response()->json($response, $status);
    }

    /**
     * Return error response
     */
    protected function errorResponse(string $code, string $message, int $status = 400, array $details = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => [
                'code' => $code,
                'message' => $message,
                'details' => $details,
            ],
            'meta' => $this->getMeta(),
        ], $status);
    }

    /**
     * Return paginated response
     */
    protected function paginatedResponse($paginator, string $message = null): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $paginator->items(),
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
            'meta' => $this->getMeta(),
        ]);
    }

    /**
     * Get meta information
     */
    private function getMeta(): array
    {
        $meta = [
            'timestamp' => now()->toIso8601String(),
        ];

        // Add rate limit info if available
        if (request()->has('partner_api_key')) {
            $apiKey = request()->get('partner_api_key');
            $rateLimitKey = "api_rate_limit:{$apiKey->id}";
            $requestCount = cache()->get($rateLimitKey, 0);

            $meta['rate_limit'] = [
                'limit' => $apiKey->rate_limit,
                'remaining' => max(0, $apiKey->rate_limit - $requestCount),
                'reset_at' => now()->addMinute()->toIso8601String(),
            ];
        }

        return $meta;
    }

    /**
     * Get authenticated partner
     */
    protected function getPartner()
    {
        return request()->get('partner');
    }

    /**
     * Get API key
     */
    protected function getApiKey()
    {
        return request()->get('partner_api_key');
    }

    /**
     * Validate partner subscription
     */
    protected function validateSubscription(string $feature = null): bool
    {
        $partner = $this->getPartner();
        
        if (!$partner->activeSubscription) {
            return false;
        }

        if ($feature) {
            return $partner->activeSubscription->{$feature . '_enabled'} ?? false;
        }

        return true;
    }
}
