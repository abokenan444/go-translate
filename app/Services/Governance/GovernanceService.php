<?php

namespace App\Services\Governance;

use Illuminate\Support\Facades\DB;

class GovernanceService
{
    public function log(int $userId = null, string $action = '', string $entityType = null, int $entityId = null, array $meta = []): void
    {
        DB::table('audit_logs')->insert([
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'ip' => request()->ip(),
            'metadata' => empty($meta) ? null : json_encode($meta),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function recent(int $limit = 50)
    {
        return DB::table('audit_logs')->orderByDesc('id')->limit($limit)->get();
    }

    public static function logStatic(int $userId = null, string $action = '', string $entityType = null, int $entityId = null, array $meta = []): void
    {
        DB::table('audit_logs')->insert([
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'ip' => request()->ip(),
            'metadata' => empty($meta) ? null : json_encode($meta),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
