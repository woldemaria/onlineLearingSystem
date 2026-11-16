<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('semester_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status', 30)->default('pending');
            $table->string('enrollment_type', 30)->default('self');
            $table->date('enrollment_date');
            $table->date('completion_date')->nullable();
            $table->decimal('grade', 5, 2)->nullable();
            $table->decimal('progress_percent', 5, 2)->default(0);
            $table->timestamp('last_accessed_at')->nullable();
            $table->integer('completed_lessons_count')->default(0);
            $table->integer('total_lessons_count')->default(0);
            $table->boolean('is_offline_access_granted')->default(false);
            $table->boolean('certificate_issued')->default(false);
            $table->decimal('final_grade', 5, 2)->nullable();
            $table->string('letter_grade', 5)->nullable();
            $table->integer('credits_earned')->default(0);
            $table->text('withdrawal_reason')->nullable();
            $table->timestamp('withdrawn_at')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['course_id', 'user_id']);
            $table->index(['user_id', 'status']);
            $table->index(['course_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};