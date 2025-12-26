<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('government_registrations')) {
            Schema::create('government_registrations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                
                // Basic Information
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password')->nullable(); // Will be set after approval
                
                // Government Entity Information
                $table->string('entity_name'); // Official entity name
                $table->string('entity_type'); // ministry, embassy, municipality, agency
                $table->string('country_code', 2);
                $table->string('country');
                $table->string('job_title');
                $table->string('department')->nullable();
                $table->string('phone')->nullable();
                
                // Official Verification Documents
                $table->json('documents')->nullable(); // Array of uploaded file paths
                $table->string('official_website_url')->nullable();
                $table->text('additional_info')->nullable();
                
                // Verification Status
                $table->enum('status', [
                    'pending_verification',
                    'under_review',
                    'approved',
                    'rejected',
                    'more_info_required',
                    'suspended'
                ])->default('pending_verification');
                
                // Review Information
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('reviewed_at')->nullable();
                $table->text('review_notes')->nullable();
                $table->text('rejection_reason')->nullable();
                
                // Verification Badge
                $table->boolean('is_verified')->default(false);
                $table->string('verification_badge')->nullable(); // Verified Government Entity
                $table->timestamp('verified_at')->nullable();
                
                // Legal Acknowledgment
                $table->boolean('legal_agreement_accepted')->default(false);
                $table->string('ip_address')->nullable();
                $table->timestamp('legal_agreement_at')->nullable();
                
                // Audit Trail
                $table->json('audit_log')->nullable(); // Track all status changes
                
                // Expiry and Renewal
                $table->date('verification_expiry_date')->nullable();
                $table->boolean('requires_renewal')->default(false);
                
                $table->timestamps();
                $table->softDeletes();
                
                // Indexes
                $table->index('status');
                $table->index('email');
                $table->index('country_code');
                $table->index('is_verified');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('government_registrations');
    }
};
