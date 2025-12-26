<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create immutable audit logs table
        if (!Schema::hasTable('audit_logs_immutable')) {
            Schema::create('audit_logs_immutable', function (Blueprint $table) {
            $table->id();
            
            // Event information
            $table->string('event_type'); // created, updated, deleted, viewed, signed, verified, etc.
            $table->string('auditable_type'); // Model class name
            $table->unsignedBigInteger('auditable_id');
            
            // User information
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_role')->nullable();
            
            // Request information
            $table->ipAddress('ip_address');
            $table->string('user_agent')->nullable();
            $table->string('request_method')->nullable();
            $table->string('request_url')->nullable();
            
            // Change tracking
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->json('metadata')->nullable();
            
            // Immutability hash chain
            $table->string('action_hash', 64); // SHA-256 hash of this record
            $table->string('previous_hash', 64)->nullable(); // Hash of previous record (blockchain-style)
            $table->string('chain_hash', 64); // Combined hash for verification
            
            // Timestamp (immutable)
            $table->timestamp('created_at')->useCurrent();
            
            // Critical: No updated_at, no soft deletes - truly immutable
            
            $table->index(['auditable_type', 'auditable_id']);
            $table->index('user_id');
            $table->index('event_type');
            $table->index('created_at');
            $table->index('action_hash');
                $table->index('previous_hash');
            });

            // Add trigger to prevent updates and deletes (database-level immutability)
            if (DB::getDriverName() === 'mysql') {
                DB::unprepared('
                    CREATE TRIGGER prevent_audit_update
                    BEFORE UPDATE ON audit_logs_immutable
                    FOR EACH ROW
                    BEGIN
                        SIGNAL SQLSTATE "45000"
                        SET MESSAGE_TEXT = "Audit logs are immutable and cannot be updated";
                    END
                ');

                DB::unprepared('
                    CREATE TRIGGER prevent_audit_delete
                    BEFORE DELETE ON audit_logs_immutable
                    FOR EACH ROW
                    BEGIN
                        SIGNAL SQLSTATE "45000"
                        SET MESSAGE_TEXT = "Audit logs are immutable and cannot be deleted";
                    END
                ');
            }
        }

        // Create audit log verification table
        if (!Schema::hasTable('audit_log_verifications')) {
            Schema::create('audit_log_verifications', function (Blueprint $table) {
            $table->id();
            $table->timestamp('verification_time');
            $table->unsignedBigInteger('records_verified');
            $table->boolean('chain_integrity_valid');
            $table->json('verification_results')->nullable();
            $table->string('verified_by')->nullable();
            $table->timestamps();

                $table->index('verification_time');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers first
        if (DB::getDriverName() === 'mysql') {
            DB::unprepared('DROP TRIGGER IF EXISTS prevent_audit_update');
            DB::unprepared('DROP TRIGGER IF EXISTS prevent_audit_delete');
        }

        Schema::dropIfExists('audit_log_verifications');
        Schema::dropIfExists('audit_logs_immutable');
    }
};
