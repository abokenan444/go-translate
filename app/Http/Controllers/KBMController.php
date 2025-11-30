<?php

namespace App\Http\Controllers;

use App\Services\KBM\KnowledgeBase;
use App\Services\KBM\CulturalMemoryGraphService;
use App\Services\Governance\GovernanceService;
use Illuminate\Http\Request;

class KBMController extends Controller
{
    public function __construct(protected KnowledgeBase $kb, protected GovernanceService $gov, protected CulturalMemoryGraphService $graph) {}

    public function listMemories(Request $request)
    {
        $filters = $request->only(['target_language','target_culture']);
        $filters['user_id'] = \Illuminate\Support\Facades\Auth::id();
        return response()->json(['success' => true, 'data' => $this->kb->searchMemories($filters, 50)]);
    }

    public function review(Request $request)
    {
        $data = $request->validate([
            'memory_id' => 'required|integer',
            'quality_score' => 'nullable|integer|min:0|max:100',
            'comments' => 'nullable|string',
            'suggestions' => 'array',
        ]);
        $id = $this->kb->addReview($data['memory_id'], [
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'quality_score' => $data['quality_score'] ?? null,
            'comments' => $data['comments'] ?? null,
            'suggestions' => $data['suggestions'] ?? [],
        ]);
        $this->gov->log(\Illuminate\Support\Facades\Auth::id(), 'review_submitted', 'cultural_memory', $data['memory_id'], [
            'quality_score' => $data['quality_score'] ?? null,
        ]);
        return response()->json(['success' => true, 'id' => $id]);
    }

    public function graph(Request $request)
    {
        $uid = \Illuminate\Support\Facades\Auth::id();
        $data = $this->graph->buildGraph($uid);
        return response()->json(['success' => true, 'data' => $data]);
    }
}
