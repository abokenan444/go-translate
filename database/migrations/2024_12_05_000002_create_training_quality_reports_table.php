<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_quality_reports', function (Blueprint $table) {
            $table->id();
            $table->json('report_data');
            $table->integer('issues_count')->default(0);
            $table->decimal('quality_score', 5, 2)->default(0);
            $table->timestamp('created_at');
            
            $table->index('created_at');
            $table->index('quality_score');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_quality_reports');
    }
};
