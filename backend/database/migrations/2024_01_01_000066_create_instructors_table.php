<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instructors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->string('employee_id', 50)->nullable();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('faculty_id')->nullable()->constrained()->nullOnDelete();
            $table->string('designation', 100)->nullable();
            $table->string('employment_type', 30)->nullable();
            $table->date('employment_started_at')->nullable();
            $table->date('employment_ended_at')->nullable();
            $table->json('qualifications')->nullable();
            $table->json('experience')->nullable();
            $table->json('teaching_subjects')->nullable();
            $table->json('languages')->nullable();
            $table->json('specializations')->nullable();
            $table->json('office_hours')->nullable();
            $table->string('office_location')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->json('verification_documents')->nullable();
            $table->string('verification_status', 30)->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_reviews')->default(0);
            $table->integer('total_courses')->default(0);
            $table->integer('total_students')->default(0);
            $table->decimal('earnings_total', 12, 2)->default(0);
            $table->decimal('earnings_pending', 12, 2)->default(0);
            $table->decimal('earnings_paid', 12, 2)->default(0);
            $table->json('bank_details')->nullable();
            $table->string('tax_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['user_id', 'institution_id']);
            $table->index(['institution_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructors');
    }
};