<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('translations')) {
            Schema::create('translations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('source_language');
                $table->string('target_language');
                $table->text('source_text');
                $table->text('translated_text');
                $table->integer('character_count');
                $table->string('status')->default('completed');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
