<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sandbox_webhook_endpoints')) {
            Schema::create('sandbox_webhook_endpoints', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sandbox_instance_id')->constrained()->onDelete('cascade');
                $table->string('url')->nullable();
                $table->text('description')->nullable();
                $table->string('secret', 64)->nullable();
                $table->json('events')->nullable(); // ["translation.completed", "meeting.started"]
                $table->boolean('is_simulation_only')->default(true);
                $table->timestamps();
                
                // Indexes
                $table->index('sandbox_instance_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sandbox_webhook_endpoints');
    }
};
