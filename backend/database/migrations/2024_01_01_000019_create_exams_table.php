<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('module_id')->nullable()->constrained('course_modules')->nullOnDelete();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('type', 30)->default('quiz');
            $table->string('status', 20)->default('draft');
            $table->integer('total_questions')->default(0);
            $table->integer('total_marks')->default(0);
            $table->integer('passing_marks')->default(0);
            $table->integer('duration_minutes')->default(0);
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->boolean('allow_resume')->default(false);
            $table->integer('max_attempts')->default(1);
            $table->boolean('shuffle_questions')->default(false);
            $table->boolean('shuffle_options')->default(false);
            $table->boolean('show_results_immediately')->default(false);
            $table->timestamp('release_results_at')->nullable();
            $table->boolean('proctoring_enabled')->default(false);
            $table->boolean('identity_verification_required')->default(false);
            $table->integer('allowed_attempts')->default(1);
            $table->json('question_bank_ids')->nullable();
            $table->json('question_selection')->nullable();
            $table->integer('random_question_count')->default(0);
            $table->integer('random_question_marks')->default(0);
            $table->boolean('negative_marking')->default(false);
            $table->decimal('negative_mark_value', 5, 2)->default(0);
            $table->boolean('calculator_allowed')->default(false);
            $table->json('formula_sheet')->nullable();
            $table->boolean('notes_allowed')->default(false);
            $table->json('accommodations')->nullable();
            $table->string('access_code')->nullable();
            $table->string('ip_restriction')->nullable();
            $table->boolean('browser_lockdown')->default(false);
            $table->boolean('tab_switch_detection')->default(false);
            $table->boolean('webcam_required')->default(false);
            $table->boolean('screen_sharing_required')->default(false);
            $table->boolean('plagiarism_detection')->default(false);
            $table->decimal('plagiarism_threshold', 5, 2)->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->json('settings')->nullable();
            $table->integer('version')->default(1);
            $table->foreignId('parent_exam_id')->nullable()->constrained('exams')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['course_id', 'is_published']);
            $table->index(['module_id', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};