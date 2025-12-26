<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('referral_links')) {
            Schema::create('referral_links', function (Blueprint $table) {
                $table->id();
                $table->foreignId('affiliate_id')->constrained()->cascadeOnDelete();
                $table->string('slug')->unique();
                $table->string('destination_url');
                $table->string('utm_source')->nullable();
                $table->string('utm_medium')->nullable();
                $table->string('utm_campaign')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_links');
    }
};
