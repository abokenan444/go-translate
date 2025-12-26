<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('official_name')->nullable();
            $table->string('country');
            $table->string('city')->nullable();
            $table->string('license_number')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('address')->nullable();
            
            // Verification fields
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'active', 'suspended', 'inactive'])->default('pending');
            $table->timestamp('suspended_at')->nullable();
            $table->text('suspension_reason')->nullable();
            
            // Settings
            $table->integer('max_students')->default(100);
            $table->integer('current_students_count')->default(0);
            $table->boolean('api_enabled')->default(true);
            $table->decimal('discount_rate', 5, 2)->default(0);
            
            // Metadata
            $table->json('metadata')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('user_id');
            $table->index('status');
            $table->index('is_verified');
        });

        Schema::create('university_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('student_id')->nullable(); // University's internal student ID
            $table->string('department')->nullable();
            $table->string('program')->nullable();
            $table->enum('status', ['active', 'suspended', 'graduated', 'inactive'])->default('active');
            $table->timestamp('enrolled_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->unique(['university_id', 'user_id']);
            $table->index('university_id');
            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('university_students');
        Schema::dropIfExists('universities');
    }
};
