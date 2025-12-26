<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CulturalProfile;
use App\Models\IndustryTemplate;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function industries()
    {
        try {
            $industries = IndustryTemplate::orderBy('name')->get();
            
            return response()->json([
                'success' => true,
                'data' => $industries->map(function($industry) {
                    return [
                        'key' => $industry->slug ?? $industry->key ?? $industry->name,
                        'name' => $industry->name,
                        'description' => $industry->description ?? '',
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function languages()
    {
        try {
            $languages = CulturalProfile::where('is_active', true)->orderBy('region')->orderBy('name')->get();
            
            // Group by region
            $grouped = $languages->groupBy('region')->map(function($items, $region) {
                return $items->map(function($lang) {
                    return [
                        'code' => $lang->code,
                        'name' => $lang->name,
                        'locale' => $lang->locale,
                    ];
                })->values();
            });
            
            return response()->json([
                'success' => true,
                'data' => $grouped
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
