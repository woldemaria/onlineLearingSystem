<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teaching_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('semester_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('class_id')->nullable()->constrained('class_sections')->nullOnDelete();
            $table->string('section', 50)->nullable();
            $table->string('role', 30)->default('instructor');
            $table->string('status', 30)->default('active');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('unassigned_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['instructor_id', 'course_id', 'semester_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_assignments');
    }
};