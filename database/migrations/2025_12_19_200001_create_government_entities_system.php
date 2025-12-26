<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Government Entities & Invites System
     */
    public function up(): void
    {
        // Government Entities
        Schema::create('gov_entities', function (Blueprint $table) {
            $table->id();
            $table->string('entity_code')->unique(); // e.g., MOI-SA, MOF-AE
            $table->string('entity_name'); // Ministry of Interior - Saudi Arabia
            $table->string('entity_type'); // ministry, embassy, court, municipality
            $table->string('country_code', 2); // ISO 3166-1 alpha-2
            $table->string('jurisdiction'); // legal jurisdiction
            
            $table->string('official_email')->nullable();
            $table->string('official_phone')->nullable();
            $table->text('official_address')->nullable();
            
            $table->string('subdomain')->nullable()->unique(); // moi-sa.government.culturaltranslate.com
            $table->json('allowed_domains')->nullable(); // ['@moi.gov.sa', '@interior.gov.sa']
            $table->json('ip_whitelist')->nullable();
            
            $table->enum('status', ['pending', 'active', 'suspended', 'terminated'])->default('pending');
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            
            $table->json('pilot_config')->nullable(); // pilot settings
            $table->json('sla_config')->nullable(); // custom SLA
            $table->json('compliance_requirements')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['country_code', 'entity_type']);
            $table->index('status');
        });

        // Government Contacts
        Schema::create('gov_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gov_entity_id')->constrained('gov_entities')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            $table->string('full_name');
            $table->string('title'); // Director, Coordinator, etc.
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('department')->nullable();
            
            $table->enum('role', ['gov_client_operator', 'gov_client_supervisor'])->default('gov_client_operator');
            $table->boolean('is_primary')->default(false);
            $table->boolean('can_approve_uploads')->default(false);
            $table->boolean('can_view_compliance')->default(false);
            
            $table->enum('status', ['pending', 'active', 'suspended'])->default('pending');
            $table->timestamp('activated_at')->nullable();
            
            $table->timestamps();
            
            $table->index(['gov_entity_id', 'status']);
        });

        // Government Invites (One-time tokens)
        Schema::create('gov_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gov_entity_id')->constrained('gov_entities')->onDelete('cascade');
            $table->foreignId('invited_by')->constrained('users')->onDelete('cascade');
            
            $table->string('token', 64)->unique();
            $table->string('email');
            $table->string('invited_name')->nullable();
            $table->enum('role', ['gov_client_operator', 'gov_client_supervisor', 'gov_authority_officer', 'gov_authority_supervisor']);
            
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->foreignId('used_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->json('metadata')->nullable(); // extra info
            
            $table->timestamps();
            
            $table->index(['token', 'expires_at']);
            $table->index('email');
        });

        // Government Pilots
        Schema::create('gov_pilots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gov_entity_id')->constrained('gov_entities')->onDelete('cascade');
            
            $table->string('pilot_name');
            $table->text('pilot_description')->nullable();
            
            $table->enum('status', ['requested', 'approved', 'active', 'review', 'completed', 'rejected'])->default('requested');
            
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            
            // Scope
            $table->json('allowed_doc_types')->nullable(); // ['passport', 'birth_certificate']
            $table->json('allowed_languages')->nullable(); // ['en-ar', 'ar-fr']
            $table->integer('max_uploads_per_day')->default(50);
            $table->integer('max_pages_per_month')->default(1000);
            
            // KPIs
            $table->integer('target_volume')->nullable();
            $table->decimal('target_accuracy', 5, 2)->nullable(); // 99.50%
            $table->integer('target_turnaround_hours')->nullable(); // 24h
            
            $table->json('kpi_metrics')->nullable(); // actual metrics
            $table->text('review_notes')->nullable();
            
            $table->timestamps();
            
            $table->index(['gov_entity_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gov_pilots');
        Schema::dropIfExists('gov_invites');
        Schema::dropIfExists('gov_contacts');
        Schema::dropIfExists('gov_entities');
    }
};
