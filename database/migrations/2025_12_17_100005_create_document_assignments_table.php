<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('document_assignments')) {
            Schema::create('document_assignments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('document_id')->index();
                $table->unsignedBigInteger('partner_id')->index();
                
                $table->uuid('offer_group_id')->index();
                $table->unsignedTinyInteger('priority_rank')->default(1);
                $table->unsignedInteger('attempt_no')->default(1);
                
                $table->string('status')->index(); // offered|accepted|rejected|timed_out|cancelled|completed|lost
                $table->timestamp('offered_at')->nullable();
                $table->timestamp('expires_at')->nullable()->index();
                $table->timestamp('responded_at')->nullable();
                $table->timestamp('accepted_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                
                $table->string('reason')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                
                $table->index(['document_id', 'status']);
                $table->index(['partner_id', 'status']);
                $table->index(['offer_group_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('document_assignments');
    }
};
