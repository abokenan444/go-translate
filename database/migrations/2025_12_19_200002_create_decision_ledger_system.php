<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Decision Ledger (Tamper-evident)
     */
    public function up(): void
    {
        // Decision Ledger Events (Append-only, immutable)
        if (!Schema::hasTable('decision_ledger_events')) {
            Schema::create('decision_ledger_events', function (Blueprint $table) {
                $table->id();
                $table->uuid('event_uuid')->unique();
                
                $table->string('event_type'); // certificate_issued, revoked, frozen, review_completed, priority_changed, dispute_opened
                $table->unsignedBigInteger('actor_user_id')->nullable();
                $table->string('actor_role')->nullable(); // gov_client_operator, gov_authority_officer, platform_admin
                $table->string('actor_ip')->nullable();
                
                // References
                $table->unsignedBigInteger('document_id')->nullable();
                $table->unsignedBigInteger('certificate_id')->nullable();
                $table->unsignedBigInteger('gov_entity_id')->nullable();
                $table->unsignedBigInteger('dispute_id')->nullable();
            
            // Payload (all decision data)
            $table->json('payload'); // includes: reason, previous_values, new_values, evidence_refs
            
            // Hash Chain (Tamper-evident)
            $table->string('prev_hash', 64)->nullable(); // sha256 of previous event
            $table->string('hash', 64)->index(); // sha256(prev_hash + payload + event_type + created_at + actor_user_id)
            
            $table->timestamp('created_at'); // no updated_at (immutable)
            
            // Indexes
            $table->index(['event_type', 'created_at']);
            $table->index(['actor_user_id', 'created_at']);
            $table->index(['document_id', 'created_at']);
            $table->index(['certificate_id', 'created_at']);
            $table->index('gov_entity_id');
            });
        }

        // Certificate Revocations - skip if exists
        if (!Schema::hasTable('certificate_revocations')) {
            Schema::create('certificate_revocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_id')->constrained('document_certificates')->onDelete('cascade');
            $table->foreignId('ledger_event_id')->constrained('decision_ledger_events')->onDelete('cascade');
            
            $table->enum('action', ['frozen', 'revoked']); // frozen = temporary, revoked = permanent
            $table->text('reason');
            $table->text('legal_reference')->nullable(); // law/article reference
            
            $table->unsignedBigInteger('requested_by'); // who requested
            $table->unsignedBigInteger('approved_by')->nullable(); // supervisor approval (required for revoke)
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamp('effective_from'); // when it becomes invalid
            $table->timestamp('restored_at')->nullable(); // if frozen then unfrozen
            
            $table->string('revocation_receipt_path')->nullable(); // PDF proof
            
            $table->timestamps();
            
            $table->index(['certificate_id', 'action']);
            });
        }

        // Disputes - skip if exists
        if (!Schema::hasTable('disputes')) {
            Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('document_id')->nullable();
            $table->unsignedInteger('certificate_id')->nullable();
            
            $table->string('dispute_type'); // quality_issue, legal_error, fraudulent_certificate, translation_accuracy
            $table->text('description');
            
            $table->unsignedBigInteger('raised_by'); // user_id
            $table->string('raised_by_role'); // gov_authority_officer, gov_client_supervisor
            $table->unsignedBigInteger('gov_entity_id')->nullable();
            
            $table->enum('status', ['open', 'investigating', 'resolved', 'rejected', 'escalated'])->default('open');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            
            $table->unsignedBigInteger('assigned_to')->nullable(); // investigator
            $table->text('resolution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            
            $table->json('evidence_files')->nullable(); // uploaded evidence
            $table->json('timeline')->nullable(); // activity log
            
            $table->timestamps();
            
            $table->index(['status', 'priority']);
            $table->index('gov_entity_id');
            });
        }

        // Audit Samples (Authority random checks)
        if (!Schema::hasTable('audit_samples')) {
            Schema::create('audit_samples', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gov_entity_id')->nullable();
            $table->unsignedInteger('document_id')->nullable();
            
            $table->string('sample_type'); // random, risk_based, complaint_triggered
            $table->decimal('sample_percentage', 5, 2)->nullable(); // 5.00 = 5%
            
            $table->unsignedBigInteger('auditor_id'); // gov_authority_officer
            $table->enum('status', ['pending', 'in_review', 'passed', 'failed'])->default('pending');
            
            $table->text('audit_notes')->nullable();
            $table->json('findings')->nullable(); // issues found
            $table->enum('outcome', ['approved', 'requires_correction', 'revoke_recommended'])->nullable();
            
            $table->timestamps();
            
            $table->index(['gov_entity_id', 'status']);
            });
        }

        // Gov Entities table - create if not exists
        if (!Schema::hasTable('gov_entities')) {
            Schema::create('gov_entities', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('country')->nullable();
                $table->string('entity_type')->nullable(); // ministry, embassy, court, police
                $table->string('contact_email')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_samples');
        Schema::dropIfExists('disputes');
        Schema::dropIfExists('certificate_revocations');
        Schema::dropIfExists('decision_ledger_events');
    }
};
