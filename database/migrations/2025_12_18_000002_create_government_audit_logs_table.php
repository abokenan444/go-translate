<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('government_audit_logs')) {
            Schema::create('government_audit_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('government_registration_id')
                    ->constrained('government_registrations')
                    ->onDelete('cascade');
                
                // Action Details
                $table->enum('action', [
                    'submitted',
                    'under_review',
                    'approved',
                    'rejected',
                    'more_info_requested',
                    'info_provided',
                    'suspended',
                    'reactivated',
                    'document_uploaded',
                    'document_verified',
                    'document_rejected',
                    'badge_issued',
                    'badge_revoked',
                    'admin_note_added',
                    'status_changed',
                    'contact_attempted',
                    'email_sent',
                ]);
                
                // Who performed the action
                $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->string('performer_name')->nullable(); // Cache name for deleted users
                $table->string('performer_role')->nullable();
                
                // Action Details
                $table->text('notes')->nullable();
                $table->json('metadata')->nullable(); // Additional structured data
                $table->string('old_value')->nullable(); // For status changes
                $table->string('new_value')->nullable(); // For status changes
                
                // Security & Tracking
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                
                $table->timestamps();
                
                // Indexes
                $table->index('government_registration_id');
                $table->index('action');
                $table->index('performed_by');
                $table->index('created_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('government_audit_logs');
    }
};
