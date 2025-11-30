<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('pages')) {
            Schema::create('pages', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('content')->nullable();
                $table->string('status')->default('draft');
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->json('meta_keywords')->nullable();
                $table->boolean('show_in_header')->default(false);
                $table->boolean('show_in_footer')->default(false);
                $table->integer('header_order')->nullable();
                $table->integer('footer_order')->nullable();
                $table->string('footer_column')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('page_sections')) {
            Schema::create('page_sections', function (Blueprint $table) {
                $table->id();
                $table->foreignId('page_id')->constrained('pages')->onDelete('cascade');
                $table->string('section_type')->nullable();
                $table->string('title')->nullable();
                $table->string('subtitle')->nullable();
                $table->text('content')->nullable();
                $table->string('button_text')->nullable();
                $table->string('button_link')->nullable();
                $table->string('button_text_secondary')->nullable();
                $table->string('button_link_secondary')->nullable();
                $table->string('image')->nullable();
                $table->json('data')->nullable();
                $table->integer('order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('page_sections')) {
            Schema::dropIfExists('page_sections');
        }

        if (Schema::hasTable('pages')) {
            Schema::dropIfExists('pages');
        }
    }
};
