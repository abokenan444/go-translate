<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('affiliates')) {
            Schema::create('affiliates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
                $table->string('code')->unique();
                $table->string('name')->nullable();
                $table->string('status')->default('active');
                $table->string('api_key')->nullable()->unique();
                $table->string('country')->nullable();
                $table->string('payout_method')->nullable();
                $table->json('payout_details')->nullable();
                $table->decimal('current_rate', 5, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
