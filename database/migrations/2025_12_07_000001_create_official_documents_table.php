<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('official_documents')) {
            Schema::create('official_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('original_file_path');
                $table->string('source_language', 10);
                $table->string('target_language', 10);
                $table->string('document_type', 100);
                $table->string('status', 50)->default('uploaded'); // uploaded, processing, completed, failed
                $table->string('original_hash');
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->softDeletes();
                
                $table->index(['user_id', 'status']);
                $table->index('status');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('official_documents');
    }
};
