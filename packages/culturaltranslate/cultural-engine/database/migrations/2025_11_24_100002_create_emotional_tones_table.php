<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('emotional_tones')) {
            Schema::create('emotional_tones', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->string('label');
                $table->text('description')->nullable();
                $table->unsignedTinyInteger('intensity')->default(5);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('emotional_tones');
    }
};
