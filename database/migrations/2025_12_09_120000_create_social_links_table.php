<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('social_links')) {
            Schema::create('social_links', function (Blueprint $table) {
                $table->id();
                $table->string('platform'); // facebook, twitter, linkedin, instagram, youtube, etc.
                $table->string('url');
                $table->string('icon')->nullable(); // icon class or path
                $table->integer('order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->index('platform');
                $table->index('is_active');
                $table->index('order');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('social_links');
    }
};
