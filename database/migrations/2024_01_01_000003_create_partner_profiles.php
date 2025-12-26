<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('partner_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->string('business_type');
            $table->string('country');
            $table->string('phone');
            $table->text('address');
            $table->string('tax_id')->nullable();
            $table->string('website')->nullable();
            $table->decimal('commission_rate', 5, 2)->default(15.00);
            $table->enum('status', ['pending', 'active', 'suspended'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('partner_profiles');
    }
};
