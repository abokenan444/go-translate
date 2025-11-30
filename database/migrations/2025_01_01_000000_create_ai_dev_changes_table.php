<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_dev_changes', function (Blueprint $table) {
            $table->id();
            $table->string('type');   // file_edit, command, sql
            $table->string('status')->default('pending'); // pending, applied, cancelled

            $table->string('target_path')->nullable(); // لمسار الملف (نسبي من base_path)
            $table->longText('original_content')->nullable();
            $table->longText('proposed_content')->nullable();
            $table->longText('diff')->nullable();

            $table->longText('command')->nullable();
            $table->longText('sql')->nullable();

            $table->json('meta')->nullable();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_dev_changes');
    }
};
