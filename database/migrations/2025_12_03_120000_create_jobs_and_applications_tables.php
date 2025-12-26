<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Job Postings Table
        if (!Schema::hasTable('job_postings')) {
            Schema::create('job_postings', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('description');
                $table->text('responsibilities')->nullable();
                $table->text('requirements')->nullable();
                $table->text('benefits')->nullable();
                $table->string('department')->nullable();
                $table->string('location');
                $table->enum('employment_type', ['full-time', 'part-time', 'contract', 'internship'])->default('full-time');
                $table->enum('experience_level', ['entry', 'mid', 'senior', 'lead', 'executive'])->default('mid');
                $table->string('salary_range')->nullable();
                $table->integer('positions_available')->default(1);
                $table->enum('status', ['draft', 'published', 'closed'])->default('draft');
                $table->date('application_deadline')->nullable();
                $table->json('required_skills')->nullable();
                $table->json('nice_to_have_skills')->nullable();
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->json('meta_keywords')->nullable();
                $table->integer('views_count')->default(0);
                $table->integer('applications_count')->default(0);
                $table->timestamp('published_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
                
                $table->index('status');
                $table->index('employment_type');
                $table->index('published_at');
            });
        }

        // Job Applications Table
        if (!Schema::hasTable('job_applications')) {
            Schema::create('job_applications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('job_posting_id')->constrained()->onDelete('cascade');
                $table->string('full_name');
                $table->string('email');
                $table->string('phone');
                $table->string('linkedin_url')->nullable();
                $table->string('portfolio_url')->nullable();
                $table->text('cover_letter')->nullable();
                $table->string('resume_path');
                $table->string('resume_original_name');
                $table->json('additional_documents')->nullable();
                $table->integer('years_of_experience')->nullable();
                $table->string('current_position')->nullable();
                $table->string('current_company')->nullable();
                $table->decimal('expected_salary', 10, 2)->nullable();
                $table->string('notice_period')->nullable();
                $table->text('additional_info')->nullable();
                $table->enum('status', ['pending', 'reviewing', 'shortlisted', 'interviewing', 'rejected', 'accepted'])->default('pending');
                $table->text('admin_notes')->nullable();
                $table->integer('rating')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('user_agent')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->foreignId('reviewed_by')->nullable()->constrained('users');
                $table->timestamps();
                $table->softDeletes();
                
                $table->index('job_posting_id');
                $table->index('status');
                $table->index('email');
                $table->index('created_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('job_postings');
    }
};
