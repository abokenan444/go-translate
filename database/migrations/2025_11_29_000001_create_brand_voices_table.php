<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('brand_voices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name');
            $table->string('tone')->nullable();
            $table->string('formality')->nullable();
            $table->json('rules')->nullable();
            $table->json('vocabulary_use')->nullable();
            $table->json('vocabulary_avoid')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['company_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brand_voices');
    }
};
