<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sandbox_pages')) {
            Schema::create('sandbox_pages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sandbox_instance_id')->constrained()->onDelete('cascade');
                $table->foreignId('template_id')->nullable()->constrained('sandbox_site_templates')->onDelete('set null');
                $table->string('path')->default('/');
                $table->json('original_content')->nullable(); // {"hero_title": "Welcome", ...}
                $table->json('translated_content')->nullable(); // {"hero_title": "مرحباً", ...}
                $table->string('locale', 10)->default('en');
                $table->string('market', 50)->nullable();
                $table->foreignId('last_translation_job_id')->nullable()->constrained('jobs')->onDelete('set null');
                $table->timestamps();
                
                // Indexes
                $table->index('sandbox_instance_id');
                $table->index('locale');
                $table->index(['sandbox_instance_id', 'path']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sandbox_pages');
    }
};
