<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add missing columns to partner_credentials if needed
        Schema::table('partner_credentials', function (Blueprint $table) {
            if (!Schema::hasColumn('partner_credentials', 'credential_type')) {
                $table->string('credential_type')->default('license')->after('id');
                // license, certification, diploma, id_document
            }
            if (!Schema::hasColumn('partner_credentials', 'license_number')) {
                $table->string('license_number')->nullable()->after('credential_type');
            }
            if (!Schema::hasColumn('partner_credentials', 'issuing_authority')) {
                $table->string('issuing_authority')->nullable()->after('license_number');
            }
            if (!Schema::hasColumn('partner_credentials', 'issuing_country')) {
                $table->string('issuing_country', 3)->nullable()->after('issuing_authority');
            }
            if (!Schema::hasColumn('partner_credentials', 'issue_date')) {
                $table->date('issue_date')->nullable()->after('issuing_country');
            }
            if (!Schema::hasColumn('partner_credentials', 'expiry_date')) {
                $table->date('expiry_date')->nullable()->after('issue_date');
            }
            if (!Schema::hasColumn('partner_credentials', 'document_path')) {
                $table->string('document_path')->nullable()->after('expiry_date');
            }
            if (!Schema::hasColumn('partner_credentials', 'verification_status')) {
                $table->string('verification_status')->default('pending')->after('document_path');
                // pending, under_review, approved, rejected, expired
            }
            if (!Schema::hasColumn('partner_credentials', 'verified_by')) {
                $table->unsignedBigInteger('verified_by')->nullable()->after('verification_status');
            }
            if (!Schema::hasColumn('partner_credentials', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('verified_by');
            }
            if (!Schema::hasColumn('partner_credentials', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('verified_at');
            }
            if (!Schema::hasColumn('partner_credentials', 'notes')) {
                $table->text('notes')->nullable()->after('rejection_reason');
            }
        });

        // Add columns to partner_languages if needed
        Schema::table('partner_languages', function (Blueprint $table) {
            if (!Schema::hasColumn('partner_languages', 'source_lang')) {
                $table->string('source_lang', 10)->nullable()->after('partner_id');
            }
            if (!Schema::hasColumn('partner_languages', 'target_lang')) {
                $table->string('target_lang', 10)->nullable()->after('source_lang');
            }
            if (!Schema::hasColumn('partner_languages', 'specialization')) {
                $table->string('specialization')->nullable()->after('target_lang');
                // legal, medical, technical, financial, general
            }
            if (!Schema::hasColumn('partner_languages', 'proficiency_level')) {
                $table->enum('proficiency_level', ['native', 'fluent', 'professional', 'basic'])->default('professional')->after('specialization');
            }
            if (!Schema::hasColumn('partner_languages', 'is_certified')) {
                $table->boolean('is_certified')->default(false)->after('proficiency_level');
            }
            if (!Schema::hasColumn('partner_languages', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('is_certified');
            }
        });
    }

    public function down(): void
    {
        Schema::table('partner_credentials', function (Blueprint $table) {
            $columns = [
                'credential_type', 'license_number', 'issuing_authority', 'issuing_country',
                'issue_date', 'expiry_date', 'document_path', 'verification_status',
                'verified_by', 'verified_at', 'rejection_reason', 'notes',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('partner_credentials', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('partner_languages', function (Blueprint $table) {
            $columns = [
                'source_lang', 'target_lang', 'specialization', 'proficiency_level',
                'is_certified', 'is_active',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('partner_languages', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
