<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('minutes_balance')->default(0);
            $table->boolean('payg_enabled')->default(false);
            $table->unsignedInteger('payg_monthly_cap_minutes')->default(0);
            $table->string('trusted_level')->default('none'); // none|basic|trusted
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
