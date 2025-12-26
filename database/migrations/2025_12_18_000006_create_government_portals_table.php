<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Create government_portals table for subdomain routing
        if (!Schema::hasTable('government_portals')) {
            Schema::create('government_portals', function (Blueprint $table) {
                $table->id();
                $table->string('country_code', 3)->unique();
                $table->string('country_name');
                $table->string('country_name_native')->nullable();
                
                // Portal configuration
                $table->string('portal_slug')->unique(); // e.g., 'nl', 'uk', 'ae'
                $table->string('subdomain_pattern')->default('path'); // 'path' or 'prefix'
                // path = gov.culturaltranslate.com/nl
                // prefix = nl-gov.culturaltranslate.com
                
                // Customization
                $table->string('default_language', 10)->default('en');
                $table->json('supported_languages')->nullable();
                $table->string('currency_code', 3)->default('USD');
                $table->string('timezone')->default('UTC');
                
                // Legal requirements
                $table->boolean('requires_certified_translation')->default(true);
                $table->boolean('requires_notarization')->default(false);
                $table->boolean('requires_apostille')->default(false);
                $table->text('legal_disclaimer')->nullable();
                
                // Contact information
                $table->string('contact_email')->nullable();
                $table->string('contact_phone')->nullable();
                $table->text('contact_address')->nullable();
                
                // Branding
                $table->string('logo_path')->nullable();
                $table->string('primary_color')->default('#1a365d');
                $table->string('secondary_color')->default('#2563eb');
                
                // Status
                $table->boolean('is_active')->default(true);
                $table->timestamp('launched_at')->nullable();
                
                $table->timestamps();
                
                $table->index(['country_code', 'is_active']);
                $table->index(['portal_slug', 'is_active']);
            });
        }

        // Create government_portal_stats for analytics
        if (!Schema::hasTable('government_portal_stats')) {
            Schema::create('government_portal_stats', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('portal_id');
                $table->date('date');
                
                $table->unsignedInteger('page_views')->default(0);
                $table->unsignedInteger('unique_visitors')->default(0);
                $table->unsignedInteger('documents_submitted')->default(0);
                $table->unsignedInteger('documents_completed')->default(0);
                $table->decimal('revenue', 12, 2)->default(0);
                
                $table->timestamps();
                
                $table->unique(['portal_id', 'date']);
                $table->foreign('portal_id')->references('id')->on('government_portals')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('government_portal_stats');
        Schema::dropIfExists('government_portals');
    }
};
