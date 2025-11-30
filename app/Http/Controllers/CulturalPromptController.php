<?php

namespace App\Http\Controllers;

use App\Services\CulturalPromptEngine;
use Illuminate\Http\Request;

class CulturalPromptController extends Controller
{
    public function preview(Request $request, CulturalPromptEngine $engine)
    {
        $data = $request->validate([
            'source_text'        => 'required|string',
            'source_locale'      => 'required|string',
            'target_culture'     => 'required|string',
            'task_key'           => 'required|string',
            'industry_key'       => 'nullable|string',
            'emotional_tone_key' => 'nullable|string',
        ]);

        $payload = $engine->buildPrompt(
            $data['source_text'],
            $data['source_locale'],
            $data['target_culture'],
            $data['task_key'],
            $data['industry_key'] ?? null,
            $data['emotional_tone_key'] ?? null,
        );

        return response()->json($payload);
    }
}
