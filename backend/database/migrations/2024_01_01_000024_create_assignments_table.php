<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('module_id')->nullable()->constrained('course_modules')->nullOnDelete();
            $table->foreignId('lesson_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->longText('description')->nullable();
            $table->longText('instructions')->nullable();
            $table->string('type', 30)->default('individual');
            $table->string('status', 20)->default('draft');
            $table->decimal('max_score', 8, 2)->default(100);
            $table->decimal('passing_score', 8, 2)->default(50);
            $table->timestamp('due_date')->nullable();
            $table->boolean('allow_late_submission')->default(true);
            $table->decimal('late_submission_penalty', 5, 2)->default(0);
            $table->boolean('allow_resubmission')->default(false);
            $table->integer('max_attempts')->default(1);
            $table->boolean('group_assignment')->default(false);
            $table->integer('group_size')->default(1);
            $table->boolean('peer_review_enabled')->default(false);
            $table->foreignId('rubric_id')->nullable()->constrained('grading_rubrics')->nullOnDelete();
            $table->boolean('plagiarism_detection')->default(false);
            $table->json('allowed_file_types')->nullable();
            $table->integer('max_file_size')->nullable();
            $table->string('external_tool')->nullable();
            $table->string('external_url')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('ai_assistance_enabled')->default(false);
            $table->boolean('ai_feedback_enabled')->default(false);
            $table->boolean('ai_originality_check')->default(false);
            $table->boolean('release_feedback_automatically')->default(false);
            $table->boolean('release_grades_automatically')->default(false);
            $table->json('accommodations')->nullable();
            $table->json('settings')->nullable();
            $table->integer('version')->default(1);
            $table->foreignId('parent_assignment_id')->nullable()->constrained('assignments')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['course_id', 'is_published']);
            $table->index(['module_id', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};