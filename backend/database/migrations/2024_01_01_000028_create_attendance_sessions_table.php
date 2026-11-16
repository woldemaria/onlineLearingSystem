<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_section_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('instructor_id')->nullable()->constrained('instructors')->nullOnDelete();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('semester_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('type', 30)->default('physical');
            $table->timestamp('scheduled_at');
            $table->timestamp('actual_start_at')->nullable();
            $table->timestamp('actual_end_at')->nullable();
            $table->integer('duration_minutes')->default(0);
            $table->string('location')->nullable();
            $table->string('virtual_classroom_id')->nullable();
            $table->string('method', 30)->default('manual');
            $table->boolean('status')->default(true);
            $table->text('notes')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['course_id', 'scheduled_at']);
            $table->index(['class_section_id', 'scheduled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_sessions');
    }
};