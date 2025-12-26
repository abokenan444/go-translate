<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('partner_white_labels')) {
            Schema::create('partner_white_labels', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->unique()->constrained()->onDelete('cascade');
                $table->string('brand_name');
                $table->string('logo_url')->nullable();
                $table->string('favicon_url')->nullable();
                $table->string('primary_color', 7)->default('#3B82F6');
                $table->string('secondary_color', 7)->default('#10B981');
                $table->text('custom_css')->nullable();
                $table->string('custom_domain')->unique()->nullable();
                $table->boolean('domain_verified')->default(false);
                $table->boolean('ssl_enabled')->default(false);
                $table->string('email_from_name')->nullable();
                $table->string('email_from_address')->nullable();
                $table->string('support_email')->nullable();
                $table->string('support_phone')->nullable();
                $table->timestamps();
                
                $table->index('custom_domain');
                $table->index('domain_verified');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_white_labels');
    }
};
