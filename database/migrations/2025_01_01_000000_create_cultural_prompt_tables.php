<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cultural_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('locale')->default('en');
            $table->string('region')->nullable();
            $table->text('description')->nullable();
            $table->json('values_json')->nullable();
            $table->json('examples_json')->nullable();
            $table->timestamps();
        });

        Schema::create('emotional_tones', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('parameters_json')->nullable();
            $table->timestamps();
        });

        Schema::create('industry_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('locale')->default('en');
            $table->text('description')->nullable();
            $table->longText('prompt_template');
            $table->timestamps();
        });

        Schema::create('task_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->longText('prompt_template');
            $table->json('input_schema_json')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_templates');
        Schema::dropIfExists('industry_templates');
        Schema::dropIfExists('emotional_tones');
        Schema::dropIfExists('cultural_profiles');
    }
};
