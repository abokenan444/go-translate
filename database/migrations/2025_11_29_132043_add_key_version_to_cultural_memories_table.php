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
        Schema::table('cultural_memories', function (Blueprint $table) {
            if (!Schema::hasColumn('cultural_memories', 'encryption_key_id')) {
                $table->string('encryption_key_id', 32)->nullable()->after('metadata')->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cultural_memories', function (Blueprint $table) {
            if (Schema::hasColumn('cultural_memories', 'encryption_key_id')) {
                $table->dropColumn('encryption_key_id');
            }
        });
    }
};
