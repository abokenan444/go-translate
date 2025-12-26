<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('plans')) {
            return;
        }
        if (!Schema::hasColumn('plans', 'display_order')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->integer('display_order')->default(0)->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('plans', 'display_order')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->dropColumn('display_order');
            });
        }
    }
};
