<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('official_documents', function (Blueprint $table) {
            // Add new columns if they don't exist
            if (!Schema::hasColumn('official_documents', 'original_filename')) {
                $table->string('original_filename')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('official_documents', 'stored_filename')) {
                $table->string('stored_filename')->nullable()->after('original_filename');
            }
            if (!Schema::hasColumn('official_documents', 'file_path')) {
                $table->string('file_path')->nullable()->after('stored_filename');
            }
            if (!Schema::hasColumn('official_documents', 'file_size')) {
                $table->bigInteger('file_size')->nullable()->after('file_path');
            }
            if (!Schema::hasColumn('official_documents', 'estimated_pages')) {
                $table->integer('estimated_pages')->nullable()->after('file_size');
            }
            if (!Schema::hasColumn('official_documents', 'estimated_words')) {
                $table->integer('estimated_words')->nullable()->after('estimated_pages');
            }
            if (!Schema::hasColumn('official_documents', 'estimated_cost')) {
                $table->decimal('estimated_cost', 10, 2)->nullable()->after('estimated_words');
            }
            if (!Schema::hasColumn('official_documents', 'amount')) {
                $table->decimal('amount', 10, 2)->nullable()->after('estimated_cost');
            }
            if (!Schema::hasColumn('official_documents', 'certificate_id')) {
                $table->string('certificate_id')->nullable()->unique()->after('amount');
            }
            if (!Schema::hasColumn('official_documents', 'stripe_session_id')) {
                $table->string('stripe_session_id')->nullable()->after('certificate_id');
            }
            if (!Schema::hasColumn('official_documents', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('stripe_session_id');
            }
            if (!Schema::hasColumn('official_documents', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('official_documents', 'translated_path')) {
                $table->string('translated_path')->nullable()->after('paid_at');
            }
            if (!Schema::hasColumn('official_documents', 'certified_path')) {
                $table->string('certified_path')->nullable()->after('translated_path');
            }
            if (!Schema::hasColumn('official_documents', 'qr_code_path')) {
                $table->string('qr_code_path')->nullable()->after('certified_path');
            }
        });
    }

    public function down()
    {
        Schema::table('official_documents', function (Blueprint $table) {
            $columns = [
                'original_filename', 'stored_filename', 'file_path', 'file_size',
                'estimated_pages', 'estimated_words', 'estimated_cost', 'amount',
                'certificate_id', 'stripe_session_id', 'payment_status', 'paid_at',
                'translated_path', 'certified_path', 'qr_code_path'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('official_documents', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
