<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Create or update audit_events table
        if (!Schema::hasTable('audit_events')) {
            Schema::create('audit_events', function (Blueprint $table) {
                $table->id();
                $table->string('actor_type')->index(); // system|user|partner|admin
                $table->unsignedBigInteger('actor_id')->nullable()->index();
                $table->string('event_type')->index(); // assignment.offered, assignment.accepted, etc.

                $table->string('subject_type')->index(); // Model class name
                $table->unsignedBigInteger('subject_id')->index();

                $table->json('metadata')->nullable();
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();

                $table->string('ip_address')->nullable();
                $table->text('user_agent')->nullable();
                $table->string('request_id')->nullable()->index(); // For request tracing
                
                $table->timestamps();
                
                // Composite indexes for common queries
                $table->index(['subject_type', 'subject_id']);
                $table->index(['actor_type', 'actor_id']);
                $table->index(['event_type', 'created_at']);
            });
        } else {
            Schema::table('audit_events', function (Blueprint $table) {
                if (!Schema::hasColumn('audit_events', 'old_values')) {
                    $table->json('old_values')->nullable()->after('metadata');
                }
                if (!Schema::hasColumn('audit_events', 'new_values')) {
                    $table->json('new_values')->nullable()->after('old_values');
                }
                if (!Schema::hasColumn('audit_events', 'request_id')) {
                    $table->string('request_id')->nullable()->index()->after('user_agent');
                }
            });
        }
    }

    public function down(): void
    {
        // Don't drop, just remove added columns
        if (Schema::hasTable('audit_events')) {
            Schema::table('audit_events', function (Blueprint $table) {
                if (Schema::hasColumn('audit_events', 'old_values')) {
                    $table->dropColumn('old_values');
                }
                if (Schema::hasColumn('audit_events', 'new_values')) {
                    $table->dropColumn('new_values');
                }
                if (Schema::hasColumn('audit_events', 'request_id')) {
                    $table->dropColumn('request_id');
                }
            });
        }
    }
};
