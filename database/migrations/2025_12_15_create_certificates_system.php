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
        // Certificates table
        if (!Schema::hasTable('certificates')) {
            Schema::create('certificates', function (Blueprint $table) {
                $table->id();
                $table->string('certificate_id')->unique();
                $table->string('serial_number')->unique();
                $table->string('document_type')->default('official_translation');
                $table->string('source_language', 10);
                $table->string('target_language', 10);
                $table->string('pdf_path')->nullable();
                $table->string('qr_code_path')->nullable();
                $table->text('verification_url');
                $table->enum('status', ['active', 'revoked', 'expired'])->default('active');
                $table->timestamp('issued_at');
                $table->timestamp('expires_at')->nullable();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('translator_id')->nullable()->constrained('users')->onDelete('set null');
                $table->foreignId('partner_id')->nullable()->constrained('partners')->onDelete('set null');
                $table->json('metadata')->nullable();
                $table->timestamps();
                
                $table->index('certificate_id');
                $table->index('serial_number');
                $table->index('status');
            });
        }

        // Certificate verifications tracking
        if (!Schema::hasTable('certificate_verifications')) {
            Schema::create('certificate_verifications', function (Blueprint $table) {
                $table->id();
                $table->string('certificate_id');
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->string('status')->default('success');
                $table->timestamp('verified_at');
                $table->timestamps();
                
                $table->index('certificate_id');
                $table->index('verified_at');
            });
        }

        // Certificate revocations
        if (!Schema::hasTable('certificate_revocations')) {
            Schema::create('certificate_revocations', function (Blueprint $table) {
                $table->id();
                $table->string('certificate_id');
                $table->enum('status', ['revoked', 'reinstated'])->default('revoked');
                $table->text('reason')->nullable();
                $table->foreignId('revoked_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('revoked_at')->nullable();
                $table->timestamp('reinstated_at')->nullable();
                $table->timestamps();
                
                $table->index('certificate_id');
                $table->index('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_verifications');
        Schema::dropIfExists('certificate_revocations');
        Schema::dropIfExists('certificates');
    }
};
