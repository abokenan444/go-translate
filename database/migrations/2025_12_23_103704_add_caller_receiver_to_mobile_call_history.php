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
        Schema::table('mobile_call_history', function (Blueprint $table) {
            $table->unsignedBigInteger('caller_id')->nullable()->after('user_id');
            $table->unsignedBigInteger('receiver_id')->nullable()->after('caller_id');
            
            $table->foreign('caller_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mobile_call_history', function (Blueprint $table) {
            $table->dropForeign(['caller_id']);
            $table->dropForeign(['receiver_id']);
            $table->dropColumn(['caller_id', 'receiver_id']);
        });
    }
};
