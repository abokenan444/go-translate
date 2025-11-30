<?php

namespace App\Http\Controllers;

use App\Services\CIE\CulturalIntelligenceEngine;
use App\Services\Governance\GovernanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CIEController extends Controller
{
    public function __construct(protected CulturalIntelligenceEngine $cie, protected GovernanceService $gov) {}

    public function analyze(Request $request)
    {
        $data = $request->validate(['culture' => 'required|string']);
        return response()->json(['success' => true, 'data' => $this->cie->analyzeCulture($data['culture'])]);
    }

    public function brandVoice(Request $request)
    {
        $payload = $request->validate([
            'name' => 'required|string',
            'tone' => 'nullable|string',
            'formality' => 'nullable|string',
            'vocabulary_use' => 'array',
            'vocabulary_avoid' => 'array',
            'rules' => 'array',
        ]);
        $profile = $this->cie->modelBrandVoice($payload);
        $id = DB::table('brand_voices')->insertGetId([
            'company_id' => null,
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'name' => $payload['name'],
            'tone' => $profile['tone'] ?? null,
            'formality' => $profile['formality'] ?? null,
            'rules' => json_encode($profile['rules'] ?? []),
            'vocabulary_use' => json_encode($profile['vocabulary_use'] ?? []),
            'vocabulary_avoid' => json_encode($profile['vocabulary_avoid'] ?? []),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->gov->log(\Illuminate\Support\Facades\Auth::id(), 'brand_voice_created', 'brand_voice', $id, [
            'name' => $payload['name'],
        ]);
        return response()->json(['success' => true, 'id' => $id, 'profile' => $profile]);
    }

    public function normalize(Request $request)
    {
        $data = $request->validate([
            'text' => 'required|string',
            'glossary' => 'array',
        ]);
        return response()->json(['success' => true, 'data' => $this->cie->normalizeTerminology($data['text'], $data['glossary'] ?? [])]);
    }

    public function emotionMap(Request $request)
    {
        $data = $request->validate([
            'text' => 'required|string',
            'language' => 'nullable|string',
        ]);
        return response()->json(['success' => true, 'data' => $this->cie->emotionMap($data['text'], $data['language'] ?? 'auto')]);
    }
}
