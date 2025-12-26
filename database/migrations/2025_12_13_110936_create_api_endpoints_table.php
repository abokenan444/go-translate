<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('api_endpoints')) {
            Schema::create('api_endpoints', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('endpoint');
                $table->string('method')->default('GET');
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('api_endpoints');
    }
};
