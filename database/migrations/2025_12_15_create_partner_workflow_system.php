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
        // Partner assignments table
        if (!Schema::hasTable('partner_assignments')) {
            Schema::create('partner_assignments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('document_id')->constrained('official_documents')->onDelete('cascade');
                $table->foreignId('partner_id')->constrained('partners')->onDelete('cascade');
                $table->enum('status', [
                    'assigned', 'accepted', 'rejected', 'in_review', 
                    'review_completed', 'stamped', 'ready_to_ship', 
                    'shipped', 'delivered'
                ])->default('assigned');
                $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
                $table->text('instructions')->nullable();
                $table->text('review_notes')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->string('stamp_image_path')->nullable();
                $table->string('stamp_location')->nullable();
                $table->timestamp('assigned_at')->nullable();
                $table->timestamp('accepted_at')->nullable();
                $table->timestamp('rejected_at')->nullable();
                $table->timestamp('review_completed_at')->nullable();
                $table->timestamp('stamped_at')->nullable();
                $table->timestamp('shipping_prepared_at')->nullable();
                $table->timestamp('shipped_at')->nullable();
                $table->timestamp('delivered_at')->nullable();
                $table->timestamp('deadline')->nullable();
                $table->timestamps();
                
                $table->index('status');
                $table->index('priority');
                $table->index(['partner_id', 'status']);
            });
        }

        // Partner shipments table
        if (!Schema::hasTable('partner_shipments')) {
            Schema::create('partner_shipments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('assignment_id')->constrained('partner_assignments')->onDelete('cascade');
                $table->string('shipping_method')->default('standard');
                $table->string('tracking_number')->nullable();
                $table->string('carrier')->nullable();
                $table->string('recipient_name');
                $table->text('recipient_address');
                $table->string('recipient_phone')->nullable();
                $table->enum('status', [
                    'preparing', 'ready_to_ship', 'shipped', 
                    'in_transit', 'out_for_delivery', 'delivered',
                    'failed_delivery', 'returned'
                ])->default('preparing');
                $table->json('tracking_updates')->nullable();
                $table->timestamp('shipped_at')->nullable();
                $table->timestamp('delivered_at')->nullable();
                $table->timestamp('estimated_delivery')->nullable();
                $table->timestamps();
                
                $table->index('tracking_number');
                $table->index('status');
            });
        }

        // Partner workflow logs table
        if (!Schema::hasTable('partner_workflow_logs')) {
            Schema::create('partner_workflow_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('assignment_id')->constrained('partner_assignments')->onDelete('cascade');
                $table->string('action');
                $table->json('data')->nullable();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->timestamps();
                
                $table->index(['assignment_id', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_workflow_logs');
        Schema::dropIfExists('partner_shipments');
        Schema::dropIfExists('partner_assignments');
    }
};
