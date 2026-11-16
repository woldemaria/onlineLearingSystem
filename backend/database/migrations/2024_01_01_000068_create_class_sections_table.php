<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('academic_program_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('semester_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('faculty_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('code', 20);
            $table->string('section', 20);
            $table->integer('capacity')->default(30);
            $table->integer('current_enrollment')->default(0);
            $table->json('schedule')->nullable();
            $table->string('room')->nullable();
            $table->foreignId('instructor_id')->nullable()->constrained('instructors')->nullOnDelete();
            $table->boolean('status')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['institution_id', 'code', 'section']);
            $table->index(['course_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_sections');
    }
};