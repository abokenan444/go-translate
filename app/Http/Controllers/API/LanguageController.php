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
            return response()->json($industries);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function languages()
    {
        try {
            $languages = CulturalProfile::orderBy('region')->orderBy('name')->get();
            return response()->json($languages);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
