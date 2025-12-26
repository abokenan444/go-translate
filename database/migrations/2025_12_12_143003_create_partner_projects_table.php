<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('partner_projects')) {
            Schema::create('partner_projects', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                $table->string('name');
                $table->text('description')->nullable();
                $table->enum('status', ['active', 'completed', 'archived'])->default('active');
                $table->integer('total_translations')->default(0);
                $table->decimal('total_revenue', 12, 2)->default(0);
                $table->timestamps();
                
                $table->index(['partner_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_projects');
    }
};
