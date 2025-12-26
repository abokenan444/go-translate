<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add missing columns to partners table for assignment system
        Schema::table('partners', function (Blueprint $table) {
            // Partner classification
            if (!Schema::hasColumn('partners', 'partner_type')) {
                $table->string('partner_type')->default('translator')->after('type');
                // translator, office, institution
            }
            
            // Legal information
            if (!Schema::hasColumn('partners', 'legal_name')) {
                $table->string('legal_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('partners', 'country_code')) {
                $table->string('country_code', 3)->nullable()->index()->after('legal_name');
            }
            if (!Schema::hasColumn('partners', 'jurisdiction')) {
                $table->string('jurisdiction')->nullable()->after('country_code');
            }
            
            // Performance metrics
            if (!Schema::hasColumn('partners', 'rating')) {
                $table->decimal('rating', 3, 2)->default(5.00)->after('notes');
            }
            if (!Schema::hasColumn('partners', 'total_reviews')) {
                $table->unsignedInteger('total_reviews')->default(0)->after('rating');
            }
            if (!Schema::hasColumn('partners', 'acceptance_rate')) {
                $table->decimal('acceptance_rate', 5, 2)->default(100.00)->after('total_reviews');
            }
            if (!Schema::hasColumn('partners', 'on_time_rate')) {
                $table->decimal('on_time_rate', 5, 2)->default(100.00)->after('acceptance_rate');
            }
            
            // Capacity management
            if (!Schema::hasColumn('partners', 'max_concurrent_jobs')) {
                $table->unsignedInteger('max_concurrent_jobs')->default(5)->after('on_time_rate');
            }
            if (!Schema::hasColumn('partners', 'current_active_jobs')) {
                $table->unsignedInteger('current_active_jobs')->default(0)->after('max_concurrent_jobs');
            }
            
            // Verification status
            if (!Schema::hasColumn('partners', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('status');
            }
            if (!Schema::hasColumn('partners', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('is_verified');
            }
            if (!Schema::hasColumn('partners', 'verified_by')) {
                $table->unsignedBigInteger('verified_by')->nullable()->after('verified_at');
            }
            
            // Suspension
            if (!Schema::hasColumn('partners', 'is_suspended')) {
                $table->boolean('is_suspended')->default(false)->after('verified_by');
            }
            if (!Schema::hasColumn('partners', 'suspended_at')) {
                $table->timestamp('suspended_at')->nullable()->after('is_suspended');
            }
            if (!Schema::hasColumn('partners', 'suspension_reason')) {
                $table->text('suspension_reason')->nullable()->after('suspended_at');
            }
            
            // Public profile
            if (!Schema::hasColumn('partners', 'is_public')) {
                $table->boolean('is_public')->default(false)->after('suspension_reason');
            }
            if (!Schema::hasColumn('partners', 'public_profile_url')) {
                $table->string('public_profile_url')->nullable()->after('is_public');
            }
            
            // Notification preferences
            if (!Schema::hasColumn('partners', 'notify_email')) {
                $table->boolean('notify_email')->default(true)->after('public_profile_url');
            }
            if (!Schema::hasColumn('partners', 'notify_sms')) {
                $table->boolean('notify_sms')->default(false)->after('notify_email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $columns = [
                'partner_type', 'legal_name', 'country_code', 'jurisdiction',
                'rating', 'total_reviews', 'acceptance_rate', 'on_time_rate',
                'max_concurrent_jobs', 'current_active_jobs',
                'is_verified', 'verified_at', 'verified_by',
                'is_suspended', 'suspended_at', 'suspension_reason',
                'is_public', 'public_profile_url',
                'notify_email', 'notify_sms',
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('partners', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
