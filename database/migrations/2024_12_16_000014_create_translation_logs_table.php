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
        Schema::create('translation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('source_lang', 10);
            $table->string('target_lang', 10);
            $table->unsignedInteger('word_count')->default(0);
            $table->string('model')->nullable();
            $table->boolean('has_error')->default(false);
            $table->timestamps();
            
            $table->index('source_lang');
            $table->index('target_lang');
            $table->index('model');
            $table->index('has_error');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translation_logs');
    }
};
