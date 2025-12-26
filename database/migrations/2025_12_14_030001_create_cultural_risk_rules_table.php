<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cultural_risk_rules')) {
            Schema::create('cultural_risk_rules', function (Blueprint $table) {
            $table->id();
            $table->string('rule_code')->unique(); // e.g., REL-001, POL-002
            $table->string('category')->index(); // religious, political, social, legal, linguistic
            $table->string('risk_level')->index(); // low, medium, high, critical
            $table->string('target_language')->nullable();
            $table->string('target_country')->nullable();
            $table->json('keywords')->nullable(); // List of sensitive keywords
            $table->json('patterns')->nullable(); // Regex patterns for detection
            $table->text('description');
            $table->text('recommendation')->nullable();
            $table->integer('severity_score')->default(0); // 0-100
            $table->boolean('requires_immediate_flag')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['category', 'risk_level']);
                $table->index(['target_language', 'target_country']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cultural_risk_rules');
    }
};
