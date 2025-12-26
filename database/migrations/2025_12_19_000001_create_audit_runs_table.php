<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('audit_runs')) {
            return;
        }
        
        Schema::create('audit_runs', function (Blueprint $table) {
            $table->id();

            $table->string('scope')->default('government'); // government/authority/full
            $table->string('dir')->unique();                // audits/20251219_120301
            $table->string('status')->index();              // pass|fail|warn
            $table->unsignedInteger('total')->default(0);
            $table->unsignedInteger('passed')->default(0);
            $table->unsignedInteger('failed')->default(0);
            $table->unsignedInteger('warn')->default(0);

            $table->json('modules')->nullable();
            $table->string('report_html_path')->nullable();
            $table->string('report_json_path')->nullable();

            $table->boolean('is_release_candidate')->default(false)->index();
            $table->unsignedBigInteger('marked_by')->nullable();
            $table->timestamp('marked_at')->nullable();
            $table->text('release_notes')->nullable();

            $table->timestamps();

            $table->index(['created_at', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_runs');
    }
};
