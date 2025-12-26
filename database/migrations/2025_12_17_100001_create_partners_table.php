<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('partners')) {
            Schema::create('partners', function (Blueprint $table) {
                $table->id();
                $table->string('type')->index(); // translator|office|institution
                $table->string('status')->default('pending')->index(); // pending|verified|rejected|suspended
                $table->string('display_name');
                $table->string('legal_name');
                $table->string('country_code', 2)->index();
                $table->string('jurisdiction')->nullable(); // state/province
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->decimal('rating', 3, 2)->default(0);
                $table->unsignedInteger('max_concurrent_jobs')->default(5);
                $table->boolean('is_public')->default(true);
                $table->text('bio')->nullable();
                $table->json('specializations')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
