<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cts_certificates')) {
            Schema::create('cts_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_id')->unique(); // CT-CTS-2025-12-00000001
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId("project_id")->nullable()->constrained()->nullOnDelete();
            $table->foreignId("translation_id")->nullable()->constrained()->nullOnDelete();
            $table->foreignId('risk_assessment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('partner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('cts_level'); // CTS-A, CTS-B, CTS-C
            $table->integer('cultural_impact_score');
            $table->string('source_language');
            $table->string('target_language');
            $table->string('target_country')->nullable();
            $table->string('source_country')->nullable();
            $table->string('use_case')->nullable();
            $table->string('domain')->nullable();
            $table->string('original_document_path')->nullable();
            $table->string('translated_document_path')->nullable();
            $table->string('certificate_pdf_path')->nullable();
            $table->string("document_hash"); // SHA-256 hash for verification
            $table->string("translation_hash");
            $table->string('qr_code_path')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('issued_at');
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('issued_by_partner_id')->nullable()->constrained('cts_partners')->onDelete('set null');
            $table->boolean('is_verified')->default(true);
            $table->boolean('is_public')->default(false); // For public verification registry
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('certificate_id');
            $table->index(['user_id', 'cts_level']);
                $table->index('issued_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cts_certificates');
    }
};
