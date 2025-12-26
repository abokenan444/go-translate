<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update translation_requests table if exists
        if (Schema::hasTable('translation_requests')) {
            Schema::table('translation_requests', function (Blueprint $table) {
                if (!Schema::hasColumn('translation_requests', 'document_type')) {
                    $table->enum('document_type', ['general', 'certified', 'government'])->default('general')->after('id');
                }
                
                if (!Schema::hasColumn('translation_requests', 'country_code')) {
                    $table->string('country_code', 2)->nullable()->after('document_type');
                }
                
                if (!Schema::hasColumn('translation_requests', 'status')) {
                    $table->enum('status', [
                        'uploaded',
                        'ai_translating',
                        'ai_done',
                        'awaiting_reviewer',
                        'assigned',
                        'in_review',
                        'changes_requested',
                        'approved',
                        'certified',
                        'rejected',
                        'failed'
                    ])->default('uploaded')->after('country_code');
                }
                
                if (!Schema::hasColumn('translation_requests', 'requires_certification')) {
                    $table->boolean('requires_certification')->default(false)->after('status');
                }
                
                if (!Schema::hasColumn('translation_requests', 'specialization')) {
                    $table->string('specialization')->nullable()->after('requires_certification');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('translation_requests')) {
            Schema::table('translation_requests', function (Blueprint $table) {
                $table->dropColumn([
                    'document_type',
                    'country_code',
                    'status',
                    'requires_certification',
                    'specialization'
                ]);
            });
        }
    }
};
