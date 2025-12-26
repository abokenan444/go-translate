<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PurchaseConversionService;

class TestPurchaseController extends Controller
{
    public function success(Request $request)
    {
        $conversion = app(PurchaseConversionService::class)->recordPurchase([
            'request' => $request,
            'user_id' => optional($request->user())->id,
            'order_id' => 'TEST-' . now()->format('His'),
            'amount' => 49.00,
            'currency' => 'USD',
        ]);

        return response()->json([
            'ok' => true,
            'conversion_id' => optional($conversion)->id,
        ]);
    }
}
