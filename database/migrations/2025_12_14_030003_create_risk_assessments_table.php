<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('risk_assessments')) {
            Schema::create('risk_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('assessment_type')->default('translation'); // translation, document, content
            $table->string('source_language');
            $table->string('target_language');
            $table->string('target_country')->nullable();
            $table->string('use_case')->nullable(); // government, corporate, NGO, etc.
            $table->string('domain')->nullable(); // legal, marketing, medical, etc.
            $table->text('source_text')->nullable();
            $table->text('translated_text')->nullable();
            $table->string('cts_level')->nullable(); // CTS-A, CTS-B, CTS-C, CTS-R
            $table->json('risk_flags')->nullable(); // Array of detected risks
            $table->integer('cultural_impact_score')->default(0); // 0-100
            $table->text('recommendation')->nullable();
            $table->boolean('requires_human_review')->default(false);
            $table->timestamp('assessed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'project_id']);
            $table->index(['cts_level', 'cultural_impact_score']);
                $table->index('assessed_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_assessments');
    }
};
