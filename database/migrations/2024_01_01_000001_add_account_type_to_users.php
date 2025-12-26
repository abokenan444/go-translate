<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('users', 'account_type')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('account_type', [
                    'customer',
                    'affiliate',
                    'government',
                    'partner',
                    'translator'
                ])->default('customer')->after('email');
                
                $table->string('company_name')->nullable()->after('account_type');
                $table->enum('status', ['active', 'pending', 'suspended'])->default('active')->after('company_name');
                $table->timestamp('verified_at')->nullable()->after('status');
            });
        }
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['account_type', 'company_name', 'status', 'verified_at']);
        });
    }
};
