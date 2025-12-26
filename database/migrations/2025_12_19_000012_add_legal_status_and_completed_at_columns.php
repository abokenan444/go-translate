<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add legal_status column to certificates table if not exists
        if (Schema::hasTable('certificates') && !Schema::hasColumn('certificates', 'legal_status')) {
            Schema::table('certificates', function (Blueprint $table) {
                $table->string('legal_status', 20)->default('valid')->after('status');
                $table->index('legal_status');
            });
        }
        
        // Add completed_at column to official_documents table if not exists
        if (Schema::hasTable('official_documents') && !Schema::hasColumn('official_documents', 'completed_at')) {
            Schema::table('official_documents', function (Blueprint $table) {
                $table->timestamp('completed_at')->nullable()->after('status');
                $table->index('completed_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('certificates', 'legal_status')) {
            Schema::table('certificates', function (Blueprint $table) {
                $table->dropIndex(['legal_status']);
                $table->dropColumn('legal_status');
            });
        }
        
        if (Schema::hasColumn('official_documents', 'completed_at')) {
            Schema::table('official_documents', function (Blueprint $table) {
                $table->dropIndex(['completed_at']);
                $table->dropColumn('completed_at');
            });
        }
    }
};
