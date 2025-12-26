<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add missing columns to existing projects table
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'target_languages')) {
                $table->text('target_languages')->nullable()->after('target_language'); // JSON array
            }
            if (!Schema::hasColumn('projects', 'industry')) {
                $table->string('industry', 100)->nullable()->after('target_languages');
            }
            if (!Schema::hasColumn('projects', 'company_id')) {
                $table->foreignId('company_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('projects', 'brand_voice_id')) {
                $table->foreignId('brand_voice_id')->nullable()->after('industry')->constrained()->onDelete('set null');
            }
        });

        // Brand Voices Table
        if (!Schema::hasTable('brand_voices')) {
            Schema::create('brand_voices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('name');
                $table->string('tone', 100); // formal, friendly, bold, corporate, professional
                $table->text('values')->nullable(); // JSON array of brand values
                $table->text('examples')->nullable(); // JSON array of before/after examples
                $table->text('guidelines')->nullable(); // Additional writing guidelines
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->index(['user_id', 'is_active']);
            });
        }

        // Glossaries Table
        if (!Schema::hasTable('glossaries')) {
            Schema::create('glossaries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('term');
                $table->string('translation');
                $table->string('language_pair', 10); // en-ar, ar-fr, en-nl
                $table->text('context')->nullable();
                $table->text('notes')->nullable();
                $table->boolean('case_sensitive')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->index(['user_id', 'language_pair', 'is_active']);
                $table->index('term');
            });
        }

        // Translation Memory Table
        if (!Schema::hasTable('translation_memory')) {
            Schema::create('translation_memory', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
                $table->text('source_text');
                $table->text('target_text');
                $table->string('source_language', 5);
                $table->string('target_language', 5);
                $table->string('hash', 64)->index(); // MD5 hash for quick matching
                $table->integer('usage_count')->default(1);
                $table->string('context_type')->nullable(); // marketing, legal, technical
                $table->boolean('is_approved')->default(true);
                $table->timestamps();
                
                $table->index(['user_id', 'source_language', 'target_language']);
                $table->index(['hash', 'is_approved']);
            });
        }

        // Project Members Table
        if (!Schema::hasTable('project_members')) {
            Schema::create('project_members', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('role', 50); // owner, editor, reviewer, viewer
                $table->timestamps();
                
                $table->unique(['project_id', 'user_id']);
                $table->index('user_id');
            });
        }

        // Translation Tasks Table
        if (!Schema::hasTable('translation_tasks')) {
            Schema::create('translation_tasks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->constrained()->onDelete('cascade');
                $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
                $table->string('content_type', 50); // text, file, url
                $table->text('source_content');
                $table->text('translated_content')->nullable();
                $table->string('source_language', 5);
                $table->string('target_language', 5);
                $table->string('status', 50)->default('pending'); // pending, in_progress, review, completed, rejected
                $table->text('notes')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
                
                $table->index(['project_id', 'status']);
                $table->index('assigned_to');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('translation_tasks');
        Schema::dropIfExists('project_members');
        Schema::dropIfExists('translation_memory');
        Schema::dropIfExists('glossaries');
        Schema::dropIfExists('brand_voices');
        
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['target_languages', 'industry', 'company_id', 'brand_voice_id']);
        });
    }
};
