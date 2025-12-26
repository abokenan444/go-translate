<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('certified_documents')) {
            Schema::create('certified_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('cert_number')->unique();
                $table->string('document_type');
                $table->string('original_filename');
                $table->string('original_path');
                $table->string('translated_path');
                $table->string('source_language');
                $table->string('target_language');
                $table->text('original_text')->nullable();
                $table->text('translated_text')->nullable();
                $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
                $table->timestamp('verified_at')->nullable();
                $table->enum('payment_type', ['subscription', 'balance', 'free'])->default('balance');
                $table->decimal('amount_paid', 10, 2)->default(0);
                $table->timestamps();
                
                $table->index('cert_number');
                $table->index('user_id');
                $table->index('status');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('certified_documents');
    }
};
