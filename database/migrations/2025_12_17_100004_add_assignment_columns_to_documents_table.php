<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Skip if documents table doesn't exist - it will be created by another migration
        if (!Schema::hasTable('documents')) {
            return;
        }
        
        Schema::table('documents', function (Blueprint $table) {
            if (!Schema::hasColumn('documents', 'document_type')) {
                $table->string('document_type')->default('general')->index()->after('id');
            }
            
            if (!Schema::hasColumn('documents', 'country_selected_by_user')) {
                $table->string('country_selected_by_user', 2)->nullable()->index()->after('document_type');
            }
            
            if (!Schema::hasColumn('documents', 'country_from_portal')) {
                $table->string('country_from_portal', 2)->nullable()->index()->after('country_selected_by_user');
            }
            
            if (!Schema::hasColumn('documents', 'jurisdiction_country')) {
                $table->string('jurisdiction_country', 2)->nullable()->index()->after('country_from_portal');
            }
            
            if (!Schema::hasColumn('documents', 'source_lang')) {
                $table->string('source_lang', 10)->nullable()->index()->after('jurisdiction_country');
            }
            
            if (!Schema::hasColumn('documents', 'target_lang')) {
                $table->string('target_lang', 10)->nullable()->index()->after('source_lang');
            }
            
            if (!Schema::hasColumn('documents', 'reviewer_partner_id')) {
                $table->unsignedBigInteger('reviewer_partner_id')->nullable()->index()->after('target_lang');
            }
            
            if (!Schema::hasColumn('documents', 'locked_assignment_id')) {
                $table->unsignedBigInteger('locked_assignment_id')->nullable()->unique()->after('reviewer_partner_id');
            }
            
            if (!Schema::hasColumn('documents', 'assignment_attempts')) {
                $table->unsignedInteger('assignment_attempts')->default(0)->after('locked_assignment_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $columns = [
                'document_type',
                'country_selected_by_user',
                'country_from_portal',
                'jurisdiction_country',
                'source_lang',
                'target_lang',
                'reviewer_partner_id',
                'locked_assignment_id',
                'assignment_attempts',
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('documents', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
