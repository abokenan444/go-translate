<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blockchain_anchors', function (Blueprint $table) {
            $table->id();
            $table->string('chain_hash', 64)->index();
            $table->string('blockchain_network', 50); // ethereum, polygon, bitcoin
            $table->string('transaction_hash', 128)->nullable();
            $table->bigInteger('block_number')->nullable();
            $table->json('anchor_data');
            $table->json('anchor_result')->nullable();
            $table->timestamp('anchored_at');
            $table->timestamps();

            $table->index(['blockchain_network', 'anchored_at']);
            $table->index('transaction_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blockchain_anchors');
    }
};
