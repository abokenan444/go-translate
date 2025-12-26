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
            // Cost sharing fields
            $table->enum('cost_payer', ['caller', 'shared', 'receiver'])->default('caller')->after('status');
            $table->boolean('cost_share_requested')->default(false)->after('cost_payer');
            $table->enum('cost_share_status', ['pending', 'accepted', 'rejected', 'none'])->default('none')->after('cost_share_requested');
            $table->decimal('caller_cost_minutes', 10, 2)->default(0)->after('cost_share_status');
            $table->decimal('receiver_cost_minutes', 10, 2)->default(0)->after('caller_cost_minutes');
            $table->decimal('total_cost_minutes', 10, 2)->default(0)->after('receiver_cost_minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mobile_call_history', function (Blueprint $table) {
            $table->dropColumn([
                'cost_payer',
                'cost_share_requested',
                'cost_share_status',
                'caller_cost_minutes',
                'receiver_cost_minutes',
                'total_cost_minutes',
            ]);
        });
    }
};
