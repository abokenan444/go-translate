<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'minutes_balance')) {
                $table->decimal('minutes_balance', 10, 2)->default(0)->after('email');
            }
            if (!Schema::hasColumn('users', 'auto_topup_enabled')) {
                $table->boolean('auto_topup_enabled')->default(false)->after('minutes_balance');
            }
            if (!Schema::hasColumn('users', 'auto_topup_threshold')) {
                $table->decimal('auto_topup_threshold', 10, 2)->default(5)->after('auto_topup_enabled');
            }
            if (!Schema::hasColumn('users', 'auto_topup_amount')) {
                $table->decimal('auto_topup_amount', 10, 2)->default(30)->after('auto_topup_threshold');
            }
            if (!Schema::hasColumn('users', 'referral_code')) {
                $table->string('referral_code', 10)->nullable()->unique()->after('auto_topup_amount');
            }
            if (!Schema::hasColumn('users', 'referred_by')) {
                $table->foreignId('referred_by')->nullable()->after('referral_code');
            }
            if (!Schema::hasColumn('users', 'app_language')) {
                $table->string('app_language', 10)->default('en')->after('referred_by');
            }
            if (!Schema::hasColumn('users', 'default_send_language')) {
                $table->string('default_send_language', 10)->default('en')->after('app_language');
            }
            if (!Schema::hasColumn('users', 'default_receive_language')) {
                $table->string('default_receive_language', 10)->default('ar')->after('default_send_language');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'minutes_balance', 'auto_topup_enabled', 'auto_topup_threshold',
                'auto_topup_amount', 'referral_code', 'referred_by',
                'app_language', 'default_send_language', 'default_receive_language'
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
