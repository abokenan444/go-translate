<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('glossary_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('language', 10);
            $table->string('term');
            $table->string('preferred')->nullable();
            $table->boolean('forbidden')->default(false);
            $table->string('context')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['organization_id', 'user_id', 'language']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('glossary_terms');
    }
};
