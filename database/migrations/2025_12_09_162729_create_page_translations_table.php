<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('page_translations')) {
            Schema::create('page_translations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('page_id')->constrained()->onDelete('cascade');
                $table->string('locale', 5); // en, ar, es, fr, etc.
                $table->string('title');
                $table->text('content')->nullable();
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->text('meta_keywords')->nullable();
                $table->timestamps();
                
                // Ensure one translation per page per locale
                $table->unique(['page_id', 'locale']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('page_translations');
    }
};
