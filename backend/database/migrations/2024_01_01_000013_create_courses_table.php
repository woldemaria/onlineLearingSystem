<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('academic_program_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('faculty_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->string('subtitle')->nullable();
            $table->longText('description')->nullable();
            $table->json('objectives')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('course_categories')->nullOnDelete();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('language', 10)->default('en');
            $table->string('level', 20)->default('beginner');
            $table->string('type', 30)->default('self_paced');
            $table->string('status', 20)->default('active');
            $table->string('state', 20)->default('draft');
            $table->string('visibility', 20)->default('private');
            $table->decimal('price', 10, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->boolean('is_free')->default(false);
            $table->json('enrollment_policy')->nullable();
            $table->integer('max_enrollments')->nullable();
            $table->integer('current_enrollments')->default(0);
            $table->integer('credits')->default(0);
            $table->integer('duration_hours')->default(0);
            $table->integer('estimated_weeks')->default(0);
            $table->json('prerequisites')->nullable();
            $table->json('co_requisites')->nullable();
            $table->json('syllabus')->nullable();
            $table->json('grading_scheme')->nullable();
            $table->decimal('passing_grade', 5, 2)->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamp('retired_at')->nullable();
            $table->integer('version')->default(1);
            $table->foreignId('parent_course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_listed')->default(false);
            $table->boolean('is_certified')->default(false);
            $table->string('thumbnail_path')->nullable();
            $table->string('preview_video_path')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('ai_tutor_enabled')->default(false);
            $table->boolean('offline_download_enabled')->default(false);
            $table->boolean('proctoring_required')->default(false);
            $table->string('accreditation_body')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['institution_id', 'slug']);
            $table->index(['state', 'visibility', 'is_listed']);
            $table->index(['creator_id', 'state']);
            $table->index(['institution_id', 'state']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};