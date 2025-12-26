<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Two-man rule: revocation request/approval separation
     */
    public function up(): void
    {
        Schema::create('certificate_revocation_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('certificate_id');
            $table->foreign('certificate_id')->references('id')->on('document_certificates')->cascadeOnDelete();
            $table->enum('action', ['freeze', 'revoke'])->default('freeze');
            
            // Requester (officer)
            $table->foreignId('requested_by')->constrained('users');
            $table->string('requested_by_role')->default('gov_authority_officer');
            $table->timestamp('requested_at');
            
            // Approver (supervisor) - must be different user
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->string('approved_by_role')->nullable();
            $table->timestamp('approved_at')->nullable();
            
            // Legal details
            $table->text('reason');
            $table->string('legal_reference');
            $table->string('jurisdiction_country')->nullable();
            $table->string('jurisdiction_purpose')->nullable(); // embassy, court, etc
            $table->string('legal_basis_code')->nullable(); // article/law reference
            
            // Authority entity
            $table->foreignId('authority_entity_id')->nullable()->constrained('gov_entities');
            
            // Status
            $table->enum('status', ['pending', 'approved', 'rejected', 'executed'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('rejected_at')->nullable();
            
            // Executed revocation link
            $table->foreignId('revocation_id')->nullable()->constrained('certificate_revocations');
            
            $table->timestamps();
            
            // Indexes
            $table->index('status');
            $table->index(['certificate_id', 'status']);
            $table->index('requested_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_revocation_requests');
    }
};
