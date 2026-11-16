<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('academic_programs')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained()->nullOnDelete();
            $table->date('admission_date');
            $table->date('expected_graduation')->nullable();
            $table->date('actual_graduation')->nullable();
            $table->string('status', 30)->default('active');
            $table->string('student_number')->nullable();
            $table->string('academic_standing')->default('good');
            $table->decimal('gpa', 3, 2)->nullable();
            $table->decimal('cgpa', 3, 2)->nullable();
            $table->integer('credits_earned')->default(0);
            $table->integer('credits_required')->default(0);
            $table->boolean('graduation_eligibility')->default(false);
            $table->foreignId('advisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['program_id', 'user_id']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_enrollments');
    }
};