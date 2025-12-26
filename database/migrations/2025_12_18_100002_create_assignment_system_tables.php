<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Document Assignments (Auto-Assignment System)
        if (!Schema::hasTable('document_assignments')) {
            Schema::create('document_assignments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('document_id')->constrained('translation_requests')->onDelete('cascade');
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                
                // Assignment Status
                $table->enum('status', [
                    'offered',      // Sent to partner
                    'accepted',     // Partner accepted
                    'rejected',     // Partner declined
                    'timed_out',    // No response within TTL
                    'cancelled',    // Admin/System cancelled
                    'completed'     // Review finished
                ])->default('offered');
                
                // Timestamps
                $table->timestamp('offered_at');
                $table->timestamp('expires_at'); // offered_at + 30 minutes
                $table->timestamp('responded_at')->nullable();
                $table->timestamp('accepted_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                
                // Tracking
                $table->integer('attempt_no')->default(1); // Which attempt is this
                $table->text('reason')->nullable(); // Rejection/cancellation reason
                
                $table->timestamps();
                
                // Indexes
                $table->index('document_id');
                $table->index('partner_id');
                $table->index(['status', 'expires_at']);
                $table->index('offered_at');
            });
        }

        // Document Reviews (Human Review Process)
        if (!Schema::hasTable('document_reviews')) {
            Schema::create('document_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('document_id')->constrained('translation_requests')->onDelete('cascade');
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                
                // Review Status
                $table->enum('status', [
                    'in_progress',
                    'submitted',
                    'approved',
                    'rejected',
                    'changes_requested'
                ])->default('in_progress');
                
                // Review Content
                $table->text('original_text')->nullable();
                $table->text('ai_translation')->nullable();
                $table->text('reviewed_translation')->nullable();
                $table->json('changes_made')->nullable(); // Track what was modified
                
                // Reviewer Notes
                $table->text('reviewer_notes')->nullable();
                $table->text('quality_notes')->nullable();
                $table->integer('quality_score')->nullable(); // 1-100
                
                // Timestamps
                $table->timestamp('started_at')->nullable();
                $table->timestamp('submitted_at')->nullable();
                $table->timestamp('approved_at')->nullable();
                
                $table->timestamps();
                
                // Indexes
                $table->index('document_id');
                $table->index('partner_id');
                $table->index('status');
            });
        }

        // Certificates (Issued Certifications)
        if (!Schema::hasTable('certificates')) {
            Schema::create('certificates', function (Blueprint $table) {
                $table->id();
                $table->string('certificate_id')->unique(); // CT-2025-XXXXXX
                $table->foreignId('document_id')->constrained('translation_requests')->onDelete('cascade');
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                
                // Certificate Details
                $table->string('document_type'); // certified, government, legal
                $table->string('source_lang', 2);
                $table->string('target_lang', 2);
                $table->string('country_code', 2);
                
                // Security & Verification
                $table->string('document_hash', 64); // SHA-256 hash
                $table->string('verification_url');
                $table->text('qr_payload')->nullable();
                
                // Files
                $table->string('pdf_path')->nullable();
                $table->string('seal_svg_version')->nullable();
                
                // Partner Info (cached for immutability)
                $table->string('partner_name');
                $table->string('partner_license_number')->nullable();
                
                // Timestamps
                $table->timestamp('issued_at');
                $table->timestamp('expires_at')->nullable();
                $table->boolean('is_revoked')->default(false);
                $table->timestamp('revoked_at')->nullable();
                $table->text('revocation_reason')->nullable();
                
                $table->timestamps();
                
                // Indexes
                $table->index('certificate_id');
                $table->index('document_id');
                $table->index('partner_id');
                $table->index('user_id');
                $table->index('issued_at');
                $table->index('is_revoked');
            });
        }

        // Audit Events (Complete Audit Trail)
        if (!Schema::hasTable('audit_events')) {
            Schema::create('audit_events', function (Blueprint $table) {
                $table->id();
                
                // Actor (Who did it)
                $table->enum('actor_type', ['system', 'user', 'partner', 'admin']);
                $table->unsignedBigInteger('actor_id')->nullable();
                $table->string('actor_name')->nullable(); // Cached for deleted actors
                
                // Event Details
                $table->string('event_type'); // assignment_offered, assignment_accepted, etc.
                $table->string('subject_type'); // document, assignment, certificate, etc.
                $table->unsignedBigInteger('subject_id')->nullable();
                
                // Additional Data
                $table->json('metadata')->nullable();
                $table->text('description')->nullable();
                
                // Security
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                
                $table->timestamp('created_at');
                
                // Indexes
                $table->index(['subject_type', 'subject_id']);
                $table->index('event_type');
                $table->index('created_at');
                $table->index(['actor_type', 'actor_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_events');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('document_reviews');
        Schema::dropIfExists('document_assignments');
    }
};
