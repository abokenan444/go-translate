<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('integrations')) {
            return;
        }
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type'); // api, webhook, oauth
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('category')->nullable(); // payment, crm, marketing, etc
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_auth')->default(false);
            $table->json('config')->nullable();
            $table->json('auth_config')->nullable();
            $table->string('documentation_url')->nullable();
            $table->integer('install_count')->default(0);
            $table->decimal('rating', 3, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
            $table->index('type');
            $table->index('category');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
};
