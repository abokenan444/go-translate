<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TrainingData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrainingDataController extends Controller
{
    /**
     * Rate a translation
     */
    public function rateTranslation(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $trainingData = TrainingData::find($id);

        if (!$trainingData) {
            return response()->json([
                'success' => false,
                'message' => 'Translation not found'
            ], 404);
        }

        // Update rating and feedback
        $trainingData->user_rating = $request->rating;
        $trainingData->user_feedback = $request->feedback;

        // Auto-approve high-rated translations
        if ($request->rating >= 4) {
            $trainingData->is_approved = true;
            $trainingData->data_quality = $request->rating == 5 ? 'excellent' : 'good';
        } else {
            $trainingData->data_quality = 'poor';
            $trainingData->is_suitable_for_training = false;
        }

        $trainingData->save();

        return response()->json([
            'success' => true,
            'message' => 'Rating saved successfully',
            'data' => $trainingData
        ]);
    }

    /**
     * Get training data statistics
     */
    public function getStatistics()
    {
        $stats = TrainingData::getStatistics();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Export training data
     */
    public function exportTrainingData(Request $request)
    {
        $languagePair = null;

        if ($request->has('source_language') && $request->has('target_language')) {
            $languagePair = [
                'source' => $request->source_language,
                'target' => $request->target_language
            ];
        }

        $data = TrainingData::exportForTraining($languagePair);

        $filename = 'training_data_' . date('Y-m-d_H-i-s') . '.jsonl';
        $content = $data->map(function ($item) {
            return json_encode($item);
        })->implode("\n");

        return response($content)
            ->header('Content-Type', 'application/jsonl')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Get user's recent translations for rating
     */
    public function getRecentTranslations(Request $request)
    {
        $translations = TrainingData::where('user_id', $request->user()->id)
            ->whereNull('user_rating')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $translations
        ]);
    }

    /**
     * Bulk approve translations
     */
    public function bulkApprove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:training_data,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        TrainingData::whereIn('id', $request->ids)->update([
            'is_approved' => true,
            'data_quality' => 'good'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Translations approved successfully'
        ]);
    }
}
