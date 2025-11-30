<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompanyIntegration;
use Illuminate\Http\Request;

class CompanyWebhookController extends Controller
{
    public function handle(Request $request, int $company, string $provider)
    {
        $integration = CompanyIntegration::where('company_id', $company)
            ->where('provider', $provider)
            ->where('status', 'active')
            ->first();
        if (!$integration) {
            return response()->json(['success'=>false,'error'=>'Integration not found'], 404);
        }
        // Basic signature check if api_secret provided
        $signature = $request->header('X-Integration-Signature');
        if ($integration->api_secret) {
            $computed = hash_hmac('sha256', $request->getContent(), $integration->api_secret);
            if (!$signature || !hash_equals($computed, $signature)) {
                return response()->json(['success'=>false,'error'=>'Invalid signature'], 401);
            }
        }
        // TODO: persist event and dispatch job per provider/event type
        return response()->json(['success'=>true]);
    }
}
