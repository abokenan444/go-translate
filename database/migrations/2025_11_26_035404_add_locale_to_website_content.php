<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_content', function (Blueprint $table) {
            $table->string('locale', 5)->default('en')->after('page_slug');
            $table->dropUnique(['page_slug']);
            $table->unique(['page_slug', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::table('website_content', function (Blueprint $table) {
            $table->dropUnique(['page_slug', 'locale']);
            $table->dropColumn('locale');
            $table->unique('page_slug');
        });
    }
};
