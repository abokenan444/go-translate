<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Government Verification Requests Table
        if (Schema::hasTable('government_verifications')) {
            return;
        }

        Schema::create('government_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Entity Information
            $table->string('entity_name'); // Official government entity name
            $table->string('entity_name_ar')->nullable(); // Arabic name
            $table->string('entity_type'); // ministry, embassy, municipality, authority
            $table->string('country_code', 2); // ISO country code
            $table->string('country_name');
            
            // Contact Information
            $table->string('official_email'); // Must be .gov domain
            $table->string('official_phone')->nullable();
            $table->string('official_website')->nullable();
            $table->string('job_title'); // Position in entity
            $table->string('department')->nullable();
            
            // Verification Documents
            $table->json('documents')->nullable(); // Array of uploaded proof files
            $table->text('additional_info')->nullable();
            
            // Verification Status
            $table->enum('status', [
                'pending_verification',
                'under_review',
                'documents_requested',
                'approved',
                'rejected'
            ])->default('pending_verification');
            
            // Review Information
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            
            // Legal Declaration
            $table->boolean('legal_declaration_accepted')->default(false);
            $table->string('declaration_ip')->nullable();
            $table->timestamp('declaration_timestamp')->nullable();
            
            // Security & Audit
            $table->string('verification_token')->unique()->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->json('audit_trail')->nullable(); // Track all status changes
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('status');
            $table->index('entity_type');
            $table->index('country_code');
            $table->index(['user_id', 'status']);
        });
        
        // Add government verification fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_government_verified')->default(false)->after('account_status');
            $table->timestamp('government_verified_at')->nullable()->after('is_government_verified');
            $table->string('government_badge')->nullable()->after('government_verified_at'); // Badge level
            $table->enum('government_access_level', [
                'none',
                'read_only',
                'standard',
                'pilot',
                'full_access'
            ])->default('none')->after('government_badge');
        });
        
        // Government Verification Audit Log
        Schema::create('government_verification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('government_verification_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Who made the action
            $table->string('action'); // status_changed, documents_uploaded, review_completed, etc.
            $table->string('from_status')->nullable();
            $table->string('to_status')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index('action');
            $table->index('created_at');
        });
        
        // Government Allowed Email Domains
        Schema::create('government_email_domains', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->unique(); // e.g., gov.sa, gov.uk, gouv.fr
            $table->string('country_code', 2);
            $table->string('country_name');
            $table->string('entity_type')->nullable(); // federal, state, municipal
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_additional_verification')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('domain');
            $table->index(['country_code', 'is_active']);
        });
        
        // Seed common government domains
        DB::table('government_email_domains')->insert([
            // Saudi Arabia
            ['domain' => 'gov.sa', 'country_code' => 'SA', 'country_name' => 'Saudi Arabia', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['domain' => 'moi.gov.sa', 'country_code' => 'SA', 'country_name' => 'Saudi Arabia', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['domain' => 'mofa.gov.sa', 'country_code' => 'SA', 'country_name' => 'Saudi Arabia', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            
            // UAE
            ['domain' => 'gov.ae', 'country_code' => 'AE', 'country_name' => 'United Arab Emirates', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['domain' => 'mofaic.gov.ae', 'country_code' => 'AE', 'country_name' => 'United Arab Emirates', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            
            // United States
            ['domain' => 'gov', 'country_code' => 'US', 'country_name' => 'United States', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['domain' => 'state.gov', 'country_code' => 'US', 'country_name' => 'United States', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['domain' => 'mil', 'country_code' => 'US', 'country_name' => 'United States', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            
            // United Kingdom
            ['domain' => 'gov.uk', 'country_code' => 'GB', 'country_name' => 'United Kingdom', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['domain' => 'parliament.uk', 'country_code' => 'GB', 'country_name' => 'United Kingdom', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            
            // Canada
            ['domain' => 'gc.ca', 'country_code' => 'CA', 'country_name' => 'Canada', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['domain' => 'canada.ca', 'country_code' => 'CA', 'country_name' => 'Canada', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            
            // France
            ['domain' => 'gouv.fr', 'country_code' => 'FR', 'country_name' => 'France', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            
            // Germany
            ['domain' => 'bund.de', 'country_code' => 'DE', 'country_name' => 'Germany', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            
            // Netherlands
            ['domain' => 'gov.nl', 'country_code' => 'NL', 'country_name' => 'Netherlands', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            
            // Australia
            ['domain' => 'gov.au', 'country_code' => 'AU', 'country_name' => 'Australia', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            
            // Egypt
            ['domain' => 'gov.eg', 'country_code' => 'EG', 'country_name' => 'Egypt', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            
            // Jordan
            ['domain' => 'gov.jo', 'country_code' => 'JO', 'country_name' => 'Jordan', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('government_verification_logs');
        Schema::dropIfExists('government_email_domains');
        Schema::dropIfExists('government_verifications');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_government_verified',
                'government_verified_at',
                'government_badge',
                'government_access_level'
            ]);
        });
    }
};
