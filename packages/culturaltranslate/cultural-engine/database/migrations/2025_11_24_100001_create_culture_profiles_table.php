<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('culture_profiles')) {
            Schema::create('culture_profiles', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->string('name');
                $table->string('locale')->nullable();
                $table->string('country_code', 5)->nullable();
                $table->text('description')->nullable();
                $table->text('audience_notes')->nullable();
                $table->json('constraints')->nullable();
                $table->boolean('is_default')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('culture_profiles');
    }
};
