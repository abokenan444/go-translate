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
        Schema::table('mobile_invites', function (Blueprint $table) {
            $table->string('invited_name')->nullable()->after('invited_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mobile_invites', function (Blueprint $table) {
            $table->dropColumn('invited_name');
        });
    }
};
