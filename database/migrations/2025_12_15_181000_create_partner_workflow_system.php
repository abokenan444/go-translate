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
                $table->unsignedBigInteger('document_id');
                $table->unsignedBigInteger('partner_id');
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
                $table->index('document_id');
                $table->index('partner_id');
            });
        }

        // Partner shipments table
        if (!Schema::hasTable('partner_shipments')) {
            Schema::create('partner_shipments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('assignment_id');
                $table->unsignedBigInteger('partner_id');
                $table->enum('shipment_type', ['standard', 'express', 'overnight'])->default('standard');
                $table->string('tracking_number')->nullable();
                $table->string('carrier')->nullable();
                $table->decimal('shipping_cost', 10, 2)->nullable();
                $table->enum('status', [
                    'pending', 'preparing', 'shipped', 'in_transit', 
                    'delivered', 'failed', 'returned'
                ])->default('pending');
                $table->text('shipping_address');
                $table->text('special_instructions')->nullable();
                $table->string('recipient_name');
                $table->string('recipient_phone')->nullable();
                $table->timestamp('prepared_at')->nullable();
                $table->timestamp('shipped_at')->nullable();
                $table->timestamp('expected_delivery')->nullable();
                $table->timestamp('delivered_at')->nullable();
                $table->timestamps();
                
                $table->index('assignment_id');
                $table->index('partner_id');
                $table->index('status');
                $table->index('tracking_number');
            });
        }

        // Partner activity logs table
        if (!Schema::hasTable('partner_activity_logs')) {
            Schema::create('partner_activity_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('partner_id');
                $table->unsignedBigInteger('assignment_id')->nullable();
                $table->string('action_type');
                $table->text('action_details')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('user_agent')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
                
                $table->index('partner_id');
                $table->index('assignment_id');
                $table->index('action_type');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_activity_logs');
        Schema::dropIfExists('partner_shipments');
        Schema::dropIfExists('partner_assignments');
    }
};
