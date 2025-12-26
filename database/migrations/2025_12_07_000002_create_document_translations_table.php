<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('document_translations')) {
            Schema::create('document_translations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('official_document_id')->constrained()->onDelete('cascade');
                $table->string('translated_file_path');
                $table->json('layout_data')->nullable(); // OCR coordinates and layout preservation data
                $table->string('ai_engine_version', 50)->nullable();
                $table->decimal('quality_score', 5, 2)->default(0);
                $table->boolean('reviewed_by_human')->default(false);
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('reviewed_at')->nullable();
                $table->text('review_notes')->nullable();
                $table->timestamps();
                
                $table->index('official_document_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('document_translations');
    }
};
