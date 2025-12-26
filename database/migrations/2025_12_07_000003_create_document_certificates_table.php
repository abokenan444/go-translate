<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('document_certificates')) {
            Schema::create('document_certificates', function (Blueprint $table) {
                $table->id();
                $table->string('cert_id', 100)->unique(); // CT-2025-12-00000001
                $table->foreignId('document_id')->constrained('official_documents')->onDelete('cascade');
                $table->string('original_hash');
                $table->string('translated_hash');
                $table->string('status', 50)->default('valid'); // valid, revoked, expired
                $table->timestamp('issued_at');
                $table->timestamp('expires_at')->nullable();
                $table->string('issuer_name')->default('Cultural Translate');
                $table->string('issuer_location')->default('Amsterdam, Netherlands');
                $table->json('metadata')->nullable();
                $table->text('revocation_reason')->nullable();
                $table->timestamp('revoked_at')->nullable();
                $table->timestamps();
                
                $table->index('cert_id');
                $table->index(['document_id', 'status']);
                $table->index('status');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('document_certificates');
    }
};
