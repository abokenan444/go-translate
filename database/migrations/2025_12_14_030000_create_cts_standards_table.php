<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cts_standards')) {
            Schema::create('cts_standards', function (Blueprint $table) {
            $table->id();
            $table->string('version')->default('v1.0'); // CTS version
            $table->string('level')->index(); // CTS-A, CTS-B, CTS-C, CTS-R
            $table->string('level_name'); // حكومي آمن، تجاري آمن، etc.
            $table->text('description');
            $table->json('required_checks')->nullable(); // Context Preservation, Cultural Sensitivity, etc.
            $table->json('certification_rules')->nullable(); // Human-in-the-loop requirements
            $table->integer('min_impact_score')->default(0);
            $table->integer('max_impact_score')->default(100);
            $table->boolean('requires_human_review')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cts_standards');
    }
};
