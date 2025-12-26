<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('job_postings')) {
            Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->text('responsibilities')->nullable();
            $table->string('location')->nullable();
            $table->enum('type', ['full-time', 'part-time', 'contract', 'remote'])->default('full-time');
            $table->string('department')->nullable();
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['draft', 'published', 'closed'])->default('draft');
            $table->timestamp('application_deadline')->nullable();
            $table->string('contact_email')->nullable();
            $table->json('benefits')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
