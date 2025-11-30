<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('task_templates', function (Blueprint $table) {
            if (!Schema::hasColumn('task_templates', 'type')) {
                $table->string('type')->default('translation')->after('id');
            }

            if (!Schema::hasColumn('task_templates', 'category')) {
                $table->string('category')->nullable()->after('type');
            }

            if (!Schema::hasColumn('task_templates', 'tone')) {
                $table->string('tone')->nullable()->after('category');
            }

            if (!Schema::hasColumn('task_templates', 'industry')) {
                $table->string('industry')->nullable()->after('tone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('task_templates', function (Blueprint $table) {
            if (Schema::hasColumn('task_templates', 'industry')) {
                $table->dropColumn('industry');
            }
            if (Schema::hasColumn('task_templates', 'tone')) {
                $table->dropColumn('tone');
            }
            if (Schema::hasColumn('task_templates', 'category')) {
                $table->dropColumn('category');
            }
            if (Schema::hasColumn('task_templates', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
