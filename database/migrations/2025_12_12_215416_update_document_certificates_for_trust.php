<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('document_certificates', function (Blueprint $table) {
            // Add verification code for public verification
            if (!Schema::hasColumn('document_certificates', 'verification_code')) {
                $table->string('verification_code', 64)->unique()->nullable()->after('cert_id');
            }
            
            // Add translator information
            if (!Schema::hasColumn('document_certificates', 'translator_name')) {
                $table->string('translator_name')->nullable()->after('document_id');
            }
            if (!Schema::hasColumn('document_certificates', 'translator_license')) {
                $table->string('translator_license')->nullable()->after('translator_name');
            }
            if (!Schema::hasColumn('document_certificates', 'translator_signature_path')) {
                $table->string('translator_signature_path')->nullable()->after('translator_license');
            }
            
            // Add compliance seals
            if (!Schema::hasColumn('document_certificates', 'compliance_seals')) {
                $table->json('compliance_seals')->nullable()->after('translator_signature_path');
            }
            if (!Schema::hasColumn('document_certificates', 'sworn_translation')) {
                $table->boolean('sworn_translation')->default(false)->after('compliance_seals');
            }
            
            // Add revocation support
            if (!Schema::hasColumn('document_certificates', 'revocation_reason')) {
                $table->text('revocation_reason')->nullable()->after('status');
            }
            if (!Schema::hasColumn('document_certificates', 'revoked_at')) {
                $table->timestamp('revoked_at')->nullable()->after('revocation_reason');
            }
            
            // Add verification tracking
            if (!Schema::hasColumn('document_certificates', 'verification_count')) {
                $table->integer('verification_count')->default(0)->after('revoked_at');
            }
            if (!Schema::hasColumn('document_certificates', 'last_verified_at')) {
                $table->timestamp('last_verified_at')->nullable()->after('verification_count');
            }
            
            // Add blockchain hash for audit trail
            if (!Schema::hasColumn('document_certificates', 'blockchain_hash')) {
                $table->string('blockchain_hash', 64)->nullable()->after('translated_hash');
            }
            
            // Add indexes
            if (!Schema::hasIndex('document_certificates', ['verification_code'])) {
                $table->index('verification_code');
            }
            if (!Schema::hasIndex('document_certificates', ['status'])) {
                $table->index('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('document_certificates', function (Blueprint $table) {
            $table->dropColumn([
                'verification_code',
                'translator_name',
                'translator_license',
                'translator_signature_path',
                'compliance_seals',
                'sworn_translation',
                'revocation_reason',
                'revoked_at',
                'verification_count',
                'last_verified_at',
                'blockchain_hash',
            ]);
        });
    }
};
