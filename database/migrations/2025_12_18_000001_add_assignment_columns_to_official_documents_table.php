<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add assignment columns to official_documents table
        Schema::table('official_documents', function (Blueprint $table) {
            // Document classification
            if (!Schema::hasColumn('official_documents', 'certification_type')) {
                $table->string('certification_type')->default('general')->index()->after('document_type'); // general|certified|government
            }
            
            // Country/Jurisdiction tracking
            if (!Schema::hasColumn('official_documents', 'country_selected_by_user')) {
                $table->string('country_selected_by_user', 3)->nullable()->index()->after('target_language');
            }
            if (!Schema::hasColumn('official_documents', 'country_from_portal')) {
                $table->string('country_from_portal', 3)->nullable()->index()->after('country_selected_by_user');
            }
            if (!Schema::hasColumn('official_documents', 'jurisdiction_country')) {
                $table->string('jurisdiction_country', 3)->nullable()->index()->after('country_from_portal');
            }
            
            // Language codes (ISO 639-1)
            if (!Schema::hasColumn('official_documents', 'source_lang')) {
                $table->string('source_lang', 10)->nullable()->index()->after('jurisdiction_country');
            }
            if (!Schema::hasColumn('official_documents', 'target_lang')) {
                $table->string('target_lang', 10)->nullable()->index()->after('source_lang');
            }
            
            // Assignment tracking
            if (!Schema::hasColumn('official_documents', 'reviewer_partner_id')) {
                $table->unsignedBigInteger('reviewer_partner_id')->nullable()->index()->after('certified_partner_id');
            }
            if (!Schema::hasColumn('official_documents', 'locked_assignment_id')) {
                $table->unsignedBigInteger('locked_assignment_id')->nullable()->unique()->after('reviewer_partner_id');
            }
            if (!Schema::hasColumn('official_documents', 'assignment_attempts')) {
                $table->unsignedInteger('assignment_attempts')->default(0)->after('locked_assignment_id');
            }
            
            // Assignment status
            if (!Schema::hasColumn('official_documents', 'assignment_status')) {
                $table->string('assignment_status')->default('pending')->index()->after('assignment_attempts');
                // pending, awaiting_reviewer, assigned, in_review, approved, certified, escalated
            }
            
            // Priority and deadline
            if (!Schema::hasColumn('official_documents', 'priority_level')) {
                $table->enum('priority_level', ['normal', 'urgent', 'express'])->default('normal')->after('assignment_status');
            }
            if (!Schema::hasColumn('official_documents', 'deadline_at')) {
                $table->timestamp('deadline_at')->nullable()->after('priority_level');
            }
        });
    }

    public function down(): void
    {
        Schema::table('official_documents', function (Blueprint $table) {
            $columns = [
                'certification_type',
                'country_selected_by_user',
                'country_from_portal',
                'jurisdiction_country',
                'source_lang',
                'target_lang',
                'reviewer_partner_id',
                'locked_assignment_id',
                'assignment_attempts',
                'assignment_status',
                'priority_level',
                'deadline_at',
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('official_documents', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
