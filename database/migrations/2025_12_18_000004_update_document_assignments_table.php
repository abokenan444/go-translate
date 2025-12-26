<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Check if document_assignments exists, if not create it, if yes update it
        if (!Schema::hasTable('document_assignments')) {
            Schema::create('document_assignments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('document_id')->index();
                $table->string('document_type')->default('official_documents'); // Table name reference
                $table->unsignedBigInteger('partner_id')->index();

                // Parallel offer tracking
                $table->uuid('offer_group_id')->index(); // Groups parallel offers
                $table->unsignedTinyInteger('priority_rank')->default(1); // 1..N
                $table->unsignedInteger('attempt_no')->default(1);

                // Status management
                $table->string('status')->index(); // offered|accepted|rejected|timed_out|cancelled|completed|lost
                
                // Timestamps
                $table->timestamp('offered_at')->nullable();
                $table->timestamp('expires_at')->nullable()->index();
                $table->timestamp('responded_at')->nullable();
                $table->timestamp('accepted_at')->nullable();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('completed_at')->nullable();

                // Additional info
                $table->string('reason')->nullable(); // For rejection/timeout reason
                $table->decimal('estimated_duration_hours', 5, 2)->nullable();
                $table->decimal('actual_duration_hours', 5, 2)->nullable();
                $table->text('reviewer_notes')->nullable();
                
                $table->timestamps();

                // Indexes
                $table->index(['document_id', 'status']);
                $table->index(['partner_id', 'status']);
                $table->index(['offer_group_id', 'status']);
                $table->index(['status', 'expires_at']);
                
                // Foreign keys
                $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');
            });
        } else {
            // Add missing columns to existing table
            Schema::table('document_assignments', function (Blueprint $table) {
                if (!Schema::hasColumn('document_assignments', 'document_type')) {
                    $table->string('document_type')->default('official_documents')->after('document_id');
                }
                if (!Schema::hasColumn('document_assignments', 'offer_group_id')) {
                    $table->uuid('offer_group_id')->nullable()->index()->after('partner_id');
                }
                if (!Schema::hasColumn('document_assignments', 'priority_rank')) {
                    $table->unsignedTinyInteger('priority_rank')->default(1)->after('offer_group_id');
                }
                if (!Schema::hasColumn('document_assignments', 'attempt_no')) {
                    $table->unsignedInteger('attempt_no')->default(1)->after('priority_rank');
                }
                if (!Schema::hasColumn('document_assignments', 'offered_at')) {
                    $table->timestamp('offered_at')->nullable()->after('status');
                }
                if (!Schema::hasColumn('document_assignments', 'expires_at')) {
                    $table->timestamp('expires_at')->nullable()->index()->after('offered_at');
                }
                if (!Schema::hasColumn('document_assignments', 'responded_at')) {
                    $table->timestamp('responded_at')->nullable()->after('expires_at');
                }
                if (!Schema::hasColumn('document_assignments', 'accepted_at')) {
                    $table->timestamp('accepted_at')->nullable()->after('responded_at');
                }
                if (!Schema::hasColumn('document_assignments', 'started_at')) {
                    $table->timestamp('started_at')->nullable()->after('accepted_at');
                }
                if (!Schema::hasColumn('document_assignments', 'completed_at')) {
                    $table->timestamp('completed_at')->nullable()->after('started_at');
                }
                if (!Schema::hasColumn('document_assignments', 'reason')) {
                    $table->string('reason')->nullable()->after('completed_at');
                }
                if (!Schema::hasColumn('document_assignments', 'estimated_duration_hours')) {
                    $table->decimal('estimated_duration_hours', 5, 2)->nullable()->after('reason');
                }
                if (!Schema::hasColumn('document_assignments', 'actual_duration_hours')) {
                    $table->decimal('actual_duration_hours', 5, 2)->nullable()->after('estimated_duration_hours');
                }
                if (!Schema::hasColumn('document_assignments', 'reviewer_notes')) {
                    $table->text('reviewer_notes')->nullable()->after('actual_duration_hours');
                }
            });
        }
    }

    public function down(): void
    {
        // Don't drop the table, just remove added columns
        if (Schema::hasTable('document_assignments')) {
            Schema::table('document_assignments', function (Blueprint $table) {
                $columns = [
                    'document_type', 'offer_group_id', 'priority_rank', 'attempt_no',
                    'offered_at', 'expires_at', 'responded_at', 'accepted_at',
                    'started_at', 'completed_at', 'reason',
                    'estimated_duration_hours', 'actual_duration_hours', 'reviewer_notes',
                ];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('document_assignments', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
