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
        Schema::table('user_subscriptions', function (Blueprint $table) {
            // Add complimentary/exception field to allow free subscriptions as exceptions
            $table->boolean('is_complimentary')->default(false)->after('auto_renew')
                ->comment('True if this is a free/complimentary subscription given as an exception by admin');
            
            // Add admin notes for complimentary subscriptions
            $table->text('complimentary_reason')->nullable()->after('is_complimentary')
                ->comment('Admin note explaining why this subscription was granted for free');
            
            // Track who granted the complimentary subscription
            $table->foreignId('granted_by_admin_id')->nullable()->after('complimentary_reason')
                ->constrained('users')->nullOnDelete()
                ->comment('Admin user who granted this complimentary subscription');
            
            // Track when it was granted
            $table->timestamp('granted_at')->nullable()->after('granted_by_admin_id')
                ->comment('When the complimentary subscription was granted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->dropForeign(['granted_by_admin_id']);
            $table->dropColumn([
                'is_complimentary',
                'complimentary_reason',
                'granted_by_admin_id',
                'granted_at',
            ]);
        });
    }
};
