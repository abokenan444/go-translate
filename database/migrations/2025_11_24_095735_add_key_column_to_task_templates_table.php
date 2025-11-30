<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'key' column to task_templates
        if (Schema::hasTable('task_templates') && !Schema::hasColumn('task_templates', 'key')) {
            Schema::table('task_templates', function (Blueprint $table) {
                $table->string('key')->nullable()->after('id');
            });
            
            // Populate key from task_code
            DB::statement('UPDATE task_templates SET key = task_code WHERE key IS NULL');
            
            // Make it unique after populating
            Schema::table('task_templates', function (Blueprint $table) {
                $table->unique('key');
            });
        }

        // Add 'name' column to task_templates (alias for task_name_en)
        if (Schema::hasTable('task_templates') && !Schema::hasColumn('task_templates', 'name')) {
            Schema::table('task_templates', function (Blueprint $table) {
                $table->string('name')->nullable()->after('key');
            });
            
            DB::statement('UPDATE task_templates SET name = task_name_en WHERE name IS NULL');
        }

        // Add 'base_prompt' column to task_templates (combining system_prompt + user_prompt_template)
        if (Schema::hasTable('task_templates') && !Schema::hasColumn('task_templates', 'base_prompt')) {
            Schema::table('task_templates', function (Blueprint $table) {
                $table->text('base_prompt')->nullable()->after('description');
            });
            
            // Populate base_prompt from available columns. Some installations use
            // `system_prompt` + `user_prompt_template`, others use `prompt_template`.
            if (Schema::hasColumn('task_templates', 'system_prompt') && Schema::hasColumn('task_templates', 'user_prompt_template')) {
                DB::statement("UPDATE task_templates SET base_prompt = system_prompt || '\n\n' || user_prompt_template WHERE base_prompt IS NULL");
            } elseif (Schema::hasColumn('task_templates', 'prompt_template')) {
                DB::statement("UPDATE task_templates SET base_prompt = prompt_template WHERE base_prompt IS NULL");
            }
        }

        // Add 'industry_key' column to task_templates
        if (Schema::hasTable('task_templates') && !Schema::hasColumn('task_templates', 'industry_key')) {
            Schema::table('task_templates', function (Blueprint $table) {
                $table->string('industry_key')->nullable()->after('category');
            });
        }

        // Add 'meta' column to task_templates
        if (Schema::hasTable('task_templates') && !Schema::hasColumn('task_templates', 'meta')) {
            Schema::table('task_templates', function (Blueprint $table) {
                $table->json('meta')->nullable();
            });
        }

        // Add 'key' column to industries (using slug as source)
        if (Schema::hasTable('industries') && !Schema::hasColumn('industries', 'key')) {
            Schema::table('industries', function (Blueprint $table) {
                $table->string('key')->nullable()->after('id');
            });
            
            DB::statement('UPDATE industries SET key = slug WHERE key IS NULL');
            
            Schema::table('industries', function (Blueprint $table) {
                $table->unique('key');
            });
        }

        // industries already has 'name' column, no need to add

        // Add 'key' column to emotional_tones
        if (Schema::hasTable('emotional_tones') && !Schema::hasColumn('emotional_tones', 'key')) {
            Schema::table('emotional_tones', function (Blueprint $table) {
                $table->string('key')->nullable()->after('id');
            });
            
            DB::statement('UPDATE emotional_tones SET key = tone_code WHERE key IS NULL');
            
            Schema::table('emotional_tones', function (Blueprint $table) {
                $table->unique('key');
            });
        }

        // Add 'label' column to emotional_tones
        if (Schema::hasTable('emotional_tones') && !Schema::hasColumn('emotional_tones', 'label')) {
            Schema::table('emotional_tones', function (Blueprint $table) {
                $table->string('label')->nullable()->after('key');
            });
            
            if (Schema::hasColumn('emotional_tones', 'tone_name_en')) {
                DB::statement('UPDATE emotional_tones SET label = tone_name_en WHERE label IS NULL');
            }
        }

        // Add 'intensity' column to emotional_tones
        if (Schema::hasTable('emotional_tones') && !Schema::hasColumn('emotional_tones', 'intensity')) {
            Schema::table('emotional_tones', function (Blueprint $table) {
                $table->unsignedTinyInteger('intensity')->default(5)->after('description');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('task_templates', 'key')) {
            Schema::table('task_templates', function (Blueprint $table) {
                $table->dropUnique(['key']);
                $table->dropColumn(['key', 'name', 'base_prompt', 'industry_key', 'meta']);
            });
        }

        if (Schema::hasColumn('industries', 'key')) {
            Schema::table('industries', function (Blueprint $table) {
                $table->dropUnique(['key']);
                $table->dropColumn('key');
            });
        }

        if (Schema::hasColumn('emotional_tones', 'key')) {
            Schema::table('emotional_tones', function (Blueprint $table) {
                $table->dropUnique(['key']);
                $table->dropColumn(['key', 'label', 'intensity']);
            });
        }
    }
};
