<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('partner_credentials')) {
            Schema::create('partner_credentials', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->constrained()->onDelete('cascade');
                $table->string('license_number')->index();
                $table->string('issuing_authority');
                $table->date('issue_date');
                $table->date('expiry_date')->nullable();
                $table->string('license_file_path')->nullable();
                $table->string('id_document_path')->nullable();
                $table->string('verification_status')->default('pending'); // pending|approved|rejected
                $table->unsignedBigInteger('verified_by')->nullable();
                $table->timestamp('verified_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                
                $table->index(['partner_id', 'verification_status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_credentials');
    }
};
