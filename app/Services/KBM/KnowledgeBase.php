<?php

namespace App\Services\KBM;

use Illuminate\Support\Facades\DB;

class KnowledgeBase
{
    public function storeMemory(array $data): int
    {
        return DB::table('cultural_memories')->insertGetId([
            'user_id' => $data['user_id'] ?? null,
            'source_language' => $data['source_language'] ?? null,
            'target_language' => $data['target_language'],
            'target_culture' => $data['target_culture'] ?? null,
            'source_text' => $data['source_text'],
            'translated_text' => $data['translated_text'],
            'brand_voice' => $data['brand_voice'] ?? null,
            'emotion' => $data['emotion'] ?? null,
            'tone' => $data['tone'] ?? null,
            'metadata' => isset($data['metadata']) ? json_encode($data['metadata']) : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function addReview(int $memoryId, array $review): int
    {
        return DB::table('translation_reviews')->insertGetId([
            'user_id' => $review['user_id'] ?? null,
            'memory_id' => $memoryId,
            'quality_score' => $review['quality_score'] ?? null,
            'comments' => $review['comments'] ?? null,
            'suggestions' => isset($review['suggestions']) ? json_encode($review['suggestions']) : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function searchMemories(array $filters = [], int $limit = 20)
    {
        $q = DB::table('cultural_memories')->orderByDesc('id');
        if (!empty($filters['target_language'])) $q->where('target_language', $filters['target_language']);
        if (!empty($filters['target_culture'])) $q->where('target_culture', $filters['target_culture']);
        if (!empty($filters['user_id'])) $q->where('user_id', $filters['user_id']);
        return $q->limit($limit)->get();
    }
}
