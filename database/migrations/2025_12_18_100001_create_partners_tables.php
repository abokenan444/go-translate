<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Partners (Certified Translators / Offices / Institutions)
        if (!Schema::hasTable('partners')) {
            Schema::create('partners', function (Blueprint $table) {
                $table->id();
                $table->enum('type', ['translator', 'office', 'institution'])->default('translator');
                $table->enum('status', ['pending', 'verified', 'rejected', 'suspended'])->default('pending');
                
                // Identity
                $table->string('display_name');
                $table->string('legal_name')->nullable();
                $table->string('country_code', 2);
                $table->string('jurisdiction')->nullable(); // State/Province
                
                // Contact
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('website')->nullable();
                $table->text('address')->nullable();
                
                // Capacity & Performance
                $table->integer('max_concurrent_jobs')->default(5);
                $table->decimal('rating', 3, 2)->default(0.00); // 0.00 to 5.00
                $table->integer('completed_jobs')->default(0);
                $table->integer('acceptance_rate')->default(100); // Percentage
                $table->integer('on_time_rate')->default(100); // Percentage
                
                // Visibility
                $table->boolean('is_public')->default(false);
                $table->boolean('is_featured')->default(false);
                
                // Verification
                $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('verified_at')->nullable();
                $table->text('admin_notes')->nullable();
                
                $table->timestamps();
                $table->softDeletes();
                
                // Indexes
                $table->index('status');
                $table->index(['country_code', 'status']);
                $table->index('type');
            });
        }

        // Partner Credentials (Licenses, IDs, Certifications)
        if (!Schema::hasTable('partner_credentials')) {
            Schema::create('partner_credentials', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                
                // License Information
                $table->string('license_number');
                $table->string('issuing_authority');
                $table->date('issue_date')->nullable();
                $table->date('expiry_date')->nullable();
                
                // Document Uploads
                $table->string('license_file_path')->nullable();
                $table->string('id_document_path')->nullable();
                $table->json('additional_documents')->nullable(); // Array of paths
                
                // Verification
                $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('verified_at')->nullable();
                $table->text('verification_notes')->nullable();
                $table->text('rejection_reason')->nullable();
                
                $table->timestamps();
                
                // Indexes
                $table->index('partner_id');
                $table->index('verification_status');
                $table->index('expiry_date');
            });
        }

        // Partner Language Pairs & Specializations
        if (!Schema::hasTable('partner_languages')) {
            Schema::create('partner_languages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                
                $table->string('source_lang', 2); // ISO code
                $table->string('target_lang', 2); // ISO code
                $table->string('specialization')->nullable(); // legal, medical, technical, academic
                $table->boolean('is_active')->default(true);
                
                $table->timestamps();
                
                // Indexes
                $table->index('partner_id');
                $table->index(['source_lang', 'target_lang']);
                $table->unique(['partner_id', 'source_lang', 'target_lang', 'specialization'], 'partner_lang_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_languages');
        Schema::dropIfExists('partner_credentials');
        Schema::dropIfExists('partners');
    }
};
