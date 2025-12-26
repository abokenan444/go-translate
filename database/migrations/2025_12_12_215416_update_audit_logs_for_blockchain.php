<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            // Add blockchain-inspired integrity fields
            if (!Schema::hasColumn('audit_logs', 'previous_hash')) {
                $table->string('previous_hash', 64)->nullable()->after('metadata');
            }
            if (!Schema::hasColumn('audit_logs', 'current_hash')) {
                $table->string('current_hash', 64)->nullable()->after('previous_hash');
            }
            if (!Schema::hasColumn('audit_logs', 'chain_hash')) {
                $table->string('chain_hash', 64)->nullable()->after('current_hash');
            }
            if (!Schema::hasColumn('audit_logs', 'is_tampered')) {
                $table->boolean('is_tampered')->default(false)->after('chain_hash');
            }
            if (!Schema::hasColumn('audit_logs', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('is_tampered');
            }
            
            // Add request context
            if (!Schema::hasColumn('audit_logs', 'user_agent')) {
                $table->string('user_agent')->nullable()->after('ip');
            }
            if (!Schema::hasColumn('audit_logs', 'request_id')) {
                $table->string('request_id')->nullable()->after('user_agent');
            }
            
            // Add data changes tracking
            if (!Schema::hasColumn('audit_logs', 'old_values')) {
                $table->json('old_values')->nullable()->after('request_id');
            }
            if (!Schema::hasColumn('audit_logs', 'new_values')) {
                $table->json('new_values')->nullable()->after('old_values');
            }
            
            // Add indexes
            if (!Schema::hasIndex('audit_logs', ['current_hash'])) {
                $table->index('current_hash');
            }
        });
    }

    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropColumn([
                'previous_hash',
                'current_hash',
                'chain_hash',
                'is_tampered',
                'verified_at',
                'user_agent',
                'request_id',
                'old_values',
                'new_values',
            ]);
        });
    }
};
