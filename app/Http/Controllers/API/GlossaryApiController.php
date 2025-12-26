<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Glossary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GlossaryApiController extends Controller
{
    /**
     * List glossary terms
     */
    public function index(Request $request)
    {
        $query = Glossary::query();

        if ($request->has('source_language')) {
            $query->where('source_language', $request->input('source_language'));
        }

        if ($request->has('target_language')) {
            $query->where('target_language', $request->input('target_language'));
        }

        if ($request->has('category')) {
            $query->where('category', $request->input('category'));
        }

        $glossaries = $query->where('is_active', true)
            ->orderBy('source_term')
            ->paginate(50);

        return response()->json([
            'success' => true,
            'data' => $glossaries->items(),
            'meta' => [
                'total' => $glossaries->total(),
                'per_page' => $glossaries->perPage(),
                'current_page' => $glossaries->currentPage(),
                'last_page' => $glossaries->lastPage(),
            ]
        ]);
    }

    /**
     * Add glossary term
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'source_language' => 'required|string|size:2',
            'target_language' => 'required|string|size:2',
            'source_term' => 'required|string|max:255',
            'target_term' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'case_sensitive' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $glossary = Glossary::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $glossary,
        ], 201);
    }

    /**
     * Update glossary term
     */
    public function update(Request $request, $id)
    {
        $glossary = Glossary::find($id);

        if (!$glossary) {
            return response()->json([
                'success' => false,
                'message' => 'Glossary term not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'source_term' => 'sometimes|required|string|max:255',
            'target_term' => 'sometimes|required|string|max:255',
            'category' => 'nullable|string|max:100',
            'case_sensitive' => 'nullable|boolean',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $glossary->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $glossary,
        ]);
    }

    /**
     * Delete glossary term
     */
    public function destroy($id)
    {
        $glossary = Glossary::find($id);

        if (!$glossary) {
            return response()->json([
                'success' => false,
                'message' => 'Glossary term not found'
            ], 404);
        }

        $glossary->delete();

        return response()->json([
            'success' => true,
            'message' => 'Glossary term deleted successfully',
        ]);
    }
}
