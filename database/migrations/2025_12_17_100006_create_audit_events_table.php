<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('audit_events')) {
            Schema::create('audit_events', function (Blueprint $table) {
                $table->id();
                $table->string('actor_type')->index(); // system|user|partner|admin
                $table->unsignedBigInteger('actor_id')->nullable()->index();
                $table->string('event_type')->index();
                
                $table->string('subject_type')->index();
                $table->unsignedBigInteger('subject_id')->index();
                
                $table->json('metadata')->nullable();
                
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();
                
                $table->index(['subject_type', 'subject_id']);
                $table->index(['actor_type', 'actor_id']);
                $table->index('created_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_events');
    }
};
