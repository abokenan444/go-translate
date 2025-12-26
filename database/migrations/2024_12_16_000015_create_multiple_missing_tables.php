<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create company_services table (pivot table for companies and services)
        if (!Schema::hasTable('company_services')) {
            Schema::create('company_services', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->constrained()->cascadeOnDelete();
                $table->foreignId('service_id')->constrained()->cascadeOnDelete();
                $table->boolean('is_enabled')->default(true);
                $table->json('settings')->nullable();
                $table->timestamp('enabled_at')->nullable();
                $table->timestamps();
                
                $table->unique(['company_id', 'service_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_services');
    }
};
