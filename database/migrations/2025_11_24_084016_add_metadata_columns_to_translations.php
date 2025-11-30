<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('translations', function (Blueprint $table) {
            if (!Schema::hasColumn('translations', 'source_text')) {
                $table->longText('source_text')->nullable();
            }

            if (!Schema::hasColumn('translations', 'translated_text')) {
                $table->longText('translated_text')->nullable();
            }

            if (!Schema::hasColumn('translations', 'tone')) {
                $table->string('tone')->nullable();
            }

            if (!Schema::hasColumn('translations', 'context')) {
                $table->text('context')->nullable();
            }

            if (!Schema::hasColumn('translations', 'word_count')) {
                $table->integer('word_count')->nullable();
            }

            if (!Schema::hasColumn('translations', 'total_tokens')) {
                $table->integer('total_tokens')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('translations', function (Blueprint $table) {
            if (Schema::hasColumn('translations', 'total_tokens')) {
                $table->dropColumn('total_tokens');
            }
            if (Schema::hasColumn('translations', 'word_count')) {
                $table->dropColumn('word_count');
            }
            if (Schema::hasColumn('translations', 'context')) {
                $table->dropColumn('context');
            }
            if (Schema::hasColumn('translations', 'tone')) {
                $table->dropColumn('tone');
            }
            if (Schema::hasColumn('translations', 'translated_text')) {
                $table->dropColumn('translated_text');
            }
            if (Schema::hasColumn('translations', 'source_text')) {
                $table->dropColumn('source_text');
            }
        });
    }
};
