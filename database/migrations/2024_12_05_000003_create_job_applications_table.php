<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_posting_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone', 50);
            $table->string('linkedin_url', 500)->nullable();
            $table->string('portfolio_url', 500)->nullable();
            $table->text('cover_letter')->nullable();
            $table->string('resume_path');
            $table->string('resume_original_name');
            $table->json('additional_documents')->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->string('current_position')->nullable();
            $table->string('current_company')->nullable();
            $table->decimal('expected_salary', 10, 2)->nullable();
            $table->string('notice_period', 100)->nullable();
            $table->text('additional_info')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('status', 50)->default('pending'); // pending, reviewing, interviewed, accepted, rejected
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['job_posting_id', 'status']);
            $table->index('email');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
