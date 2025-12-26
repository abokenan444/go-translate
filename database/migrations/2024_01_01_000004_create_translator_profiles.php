<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('translator_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->json('languages'); // ['en', 'ar', 'fr']
            $table->json('specializations'); // ['legal', 'medical', 'technical']
            $table->integer('years_of_experience');
            $table->string('certification_number')->unique();
            $table->string('country');
            $table->string('phone');
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('completed_translations')->default(0);
            $table->enum('status', ['pending', 'active', 'suspended'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('translator_profiles');
    }
};
