<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cts_partners')) {
            Schema::create('cts_partners', function (Blueprint $table) {
            $table->id();
            $table->string('partner_name');
            $table->string('partner_type'); // certified_translator, law_firm, translation_agency, corporate, university
            $table->string('partner_code')->unique(); // e.g., PT-001
            $table->string('country_code', 2);
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('license_number')->nullable();
            $table->string('seal_image_path')->nullable(); // Path to partner's seal/stamp
            $table->string('status')->default('pending'); // pending, active, suspended, revoked
            $table->date('certification_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->json('permissions')->nullable(); // What they can certify
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->integer('certificates_issued')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            });
        }

        if (!Schema::hasTable('partner_certificates')) {
            Schema::create('partner_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cts_partner_id')->constrained('cts_partners')->onDelete('cascade');
            $table->foreignId('cts_certificate_id')->constrained('cts_certificates')->onDelete('cascade');
            $table->string('partner_seal_applied')->default('no'); // yes, no
            $table->timestamp('sealed_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_certificates');
        Schema::dropIfExists('cts_partners');
    }
};
