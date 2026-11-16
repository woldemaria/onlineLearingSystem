<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('program_id')->nullable()->constrained('academic_programs')->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained()->nullOnDelete();
            $table->string('student_number', 50)->unique();
            $table->integer('admission_year');
            $table->integer('expected_graduation_year');
            $table->string('class_of')->nullable();
            $table->string('academic_status', 30)->default('active');
            $table->string('enrollment_status', 30)->default('active');
            $table->foreignId('program_enrollment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('advisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('guardian_ids')->nullable();
            $table->json('emergency_contact')->nullable();
            $table->json('disability_accommodations')->nullable();
            $table->string('boarding_status', 20)->nullable();
            $table->unsignedBigInteger('hostel_id')->nullable();
            $table->unsignedBigInteger('room_id')->nullable();
            $table->unsignedBigInteger('transport_id')->nullable();
            $table->string('fee_status', 30)->nullable();
            $table->unsignedBigInteger('library_id')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('nationality')->nullable();
            $table->string('blood_group', 5)->nullable();
            $table->string('religion')->nullable();
            $table->string('caste_category')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('guardian_occupation')->nullable();
            $table->string('guardian_income')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('previous_board')->nullable();
            $table->decimal('previous_percentage', 5, 2)->nullable();
            $table->boolean('transfer_certificate')->default(false);
            $table->json('medical_history')->nullable();
            $table->json('immunization_record')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['institution_id', 'enrollment_status']);
            $table->index(['student_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};