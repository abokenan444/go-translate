<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('affiliate_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('affiliate_code')->unique();
            $table->decimal('commission_rate', 5, 2)->default(10.00);
            $table->decimal('total_earnings', 10, 2)->default(0.00);
            $table->integer('total_referrals')->default(0);
            $table->string('payment_method')->nullable();
            $table->string('payment_details')->nullable();
            $table->enum('status', ['active', 'suspended'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('affiliate_profiles');
    }
};
