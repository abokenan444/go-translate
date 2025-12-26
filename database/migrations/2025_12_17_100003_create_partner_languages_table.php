<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('partner_languages')) {
            Schema::create('partner_languages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                $table->string('source_lang', 10)->index();
                $table->string('target_lang', 10)->index();
                $table->string('specialization')->nullable(); // legal|medical|technical|general
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->unique(['partner_id', 'source_lang', 'target_lang']);
                $table->index(['source_lang', 'target_lang', 'is_active']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_languages');
    }
};
