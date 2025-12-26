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
        // Add digital signature and versioning to cts_certificates
        if (Schema::hasTable('cts_certificates')) {
            Schema::table('cts_certificates', function (Blueprint $table) {
                // Digital Signature fields
                if (!Schema::hasColumn('cts_certificates', 'digital_signature')) {
                    $table->text('digital_signature')->nullable()->after('qr_code');
                }
                if (!Schema::hasColumn('cts_certificates', 'signature_algorithm')) {
                    $table->string('signature_algorithm')->nullable()->after('digital_signature');
                }
                if (!Schema::hasColumn('cts_certificates', 'signed_at')) {
                    $table->timestamp('signed_at')->nullable()->after('signature_algorithm');
                }
                if (!Schema::hasColumn('cts_certificates', 'signer_identity')) {
                    $table->string('signer_identity')->nullable()->after('signed_at');
                }
                if (!Schema::hasColumn('cts_certificates', 'certificate_chain')) {
                    $table->text('certificate_chain')->nullable()->after('signer_identity');
                }
                
                // Versioning fields
                if (!Schema::hasColumn('cts_certificates', 'certificate_version')) {
                    $table->integer('certificate_version')->default(1)->after('certificate_chain');
                }
                if (!Schema::hasColumn('cts_certificates', 'revision_number')) {
                    $table->integer('revision_number')->default(0)->after('certificate_version');
                }
                if (!Schema::hasColumn('cts_certificates', 'previous_version_id')) {
                    $table->foreignId('previous_version_id')->nullable()->constrained('cts_certificates')->onDelete('set null')->after('revision_number');
                }
                if (!Schema::hasColumn('cts_certificates', 'version_reason')) {
                    $table->string('version_reason')->nullable()->after('previous_version_id');
                }
                if (!Schema::hasColumn('cts_certificates', 'version_created_at')) {
                    $table->timestamp('version_created_at')->nullable()->after('version_reason');
                }
                
                // Revocation fields
                if (!Schema::hasColumn('cts_certificates', 'is_revoked')) {
                    $table->boolean('is_revoked')->default(false)->after('version_created_at');
                }
                if (!Schema::hasColumn('cts_certificates', 'revoked_at')) {
                    $table->timestamp('revoked_at')->nullable()->after('is_revoked');
                }
                if (!Schema::hasColumn('cts_certificates', 'revocation_reason')) {
                    $table->string('revocation_reason')->nullable()->after('revoked_at');
                }
                if (!Schema::hasColumn('cts_certificates', 'revoked_by')) {
                    $table->foreignId('revoked_by')->nullable()->constrained('users')->onDelete('set null')->after('revocation_reason');
                }
                if (!Schema::hasColumn('cts_certificates', 'revocation_notes')) {
                    $table->text('revocation_notes')->nullable()->after('revoked_by');
                }
            });
        }

        // Add versioning to translations
        if (Schema::hasTable('translations')) {
            Schema::table('translations', function (Blueprint $table) {
                if (!Schema::hasColumn('translations', 'translation_version')) {
                    $table->integer('translation_version')->default(1)->after('status');
                }
                if (!Schema::hasColumn('translations', 'revision_number')) {
                    $table->integer('revision_number')->default(0)->after('translation_version');
                }
                if (!Schema::hasColumn('translations', 'previous_version_id')) {
                    $table->foreignId('previous_version_id')->nullable()->constrained('translations')->onDelete('set null')->after('revision_number');
                }
                if (!Schema::hasColumn('translations', 'revision_reason')) {
                    $table->string('revision_reason')->nullable()->after('previous_version_id');
                }
                if (!Schema::hasColumn('translations', 'version_created_at')) {
                    $table->timestamp('version_created_at')->nullable()->after('revision_reason');
                }
            });
        }

        // Create digital signatures table for tracking all signatures
        if (!Schema::hasTable('digital_signatures')) {
            Schema::create('digital_signatures', function (Blueprint $table) {
            $table->id();
            $table->string('signable_type'); // Certificate, Translation, Document
            $table->unsignedBigInteger('signable_id');
            $table->text('signature_value');
            $table->string('algorithm'); // RSA-SHA256, ECDSA, etc.
            $table->text('public_key');
            $table->text('certificate_chain')->nullable();
            $table->string('hash_algorithm')->default('SHA256');
            $table->text('signed_data_hash');
            $table->foreignId('signer_id')->constrained('users')->onDelete('cascade');
            $table->string('signer_role'); // platform, partner, notary
            $table->timestamp('signed_at');
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_valid')->default(true);
            $table->timestamp('verified_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['signable_type', 'signable_id']);
                $table->index('signer_id');
                $table->index('signed_at');
                $table->index('is_valid');
            });
        }

        // Create certificate versions table for complete version history
        if (!Schema::hasTable('certificate_versions')) {
            Schema::create('certificate_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_id')->constrained('cts_certificates')->onDelete('cascade');
            $table->integer('version_number');
            $table->integer('revision_number');
            $table->json('certificate_data'); // Full snapshot of certificate data
            $table->string('change_type'); // created, modified, reissued
            $table->text('changes_summary')->nullable();
            $table->foreignId('changed_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('created_at');

            $table->unique(['certificate_id', 'version_number', 'revision_number']);
                $table->index('certificate_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_versions');
        Schema::dropIfExists('digital_signatures');
        
        Schema::table('translations', function (Blueprint $table) {
            $table->dropColumn([
                'translation_version',
                'revision_number',
                'previous_version_id',
                'revision_reason',
                'version_created_at',
            ]);
        });

        Schema::table('cts_certificates', function (Blueprint $table) {
            $table->dropColumn([
                'digital_signature',
                'signature_algorithm',
                'signed_at',
                'signer_identity',
                'certificate_chain',
                'certificate_version',
                'revision_number',
                'previous_version_id',
                'version_reason',
                'version_created_at',
                'is_revoked',
                'revoked_at',
                'revocation_reason',
                'revoked_by',
                'revocation_notes',
            ]);
        });
    }
};
