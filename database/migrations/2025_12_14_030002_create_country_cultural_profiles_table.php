<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('country_cultural_profiles')) {
            Schema::create('country_cultural_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('country_code', 2)->unique(); // ISO 3166-1 alpha-2
            $table->string('country_name');
            $table->json('languages')->nullable(); // Primary and secondary languages
            $table->json('religious_sensitivities')->nullable();
            $table->json('political_sensitivities')->nullable();
            $table->json('social_norms')->nullable();
            $table->json('legal_requirements')->nullable();
            $table->json('taboo_topics')->nullable();
            $table->json('preferred_communication_style')->nullable(); // formal, informal, etc.
            $table->integer('cultural_tolerance_score')->default(50); // 0-100
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('country_cultural_profiles');
    }
};
