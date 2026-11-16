<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('enrollment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assignment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('exam_attempt_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assignment_submission_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('graded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('category', 30);
            $table->string('type', 30);
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('score', 8, 2);
            $table->decimal('max_score', 8, 2);
            $table->decimal('weight', 5, 2)->default(1);
            $table->decimal('percentage', 5, 2)->nullable();
            $table->string('letter_grade', 5)->nullable();
            $table->decimal('numeric_grade', 5, 2)->nullable();
            $table->decimal('grade_point', 5, 2)->nullable();
            $table->boolean('is_official')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamp('released_at')->nullable();
            $table->boolean('is_adjusted')->default(false);
            $table->text('adjustment_reason')->nullable();
            $table->timestamp('adjustment_at')->nullable();
            $table->decimal('previous_score', 8, 2)->nullable();
            $table->string('previous_letter_grade', 5)->nullable();
            $table->decimal('previous_percentage', 5, 2)->nullable();
            $table->decimal('previous_grade_point', 5, 2)->nullable();
            $table->timestamp('previous_released_at')->nullable();
            $table->foreignId('previous_approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('previous_approved_at')->nullable();
            $table->string('previous_category', 30)->nullable();
            $table->string('previous_type', 30)->nullable();
            $table->string('previous_title')->nullable();
            $table->text('previous_description')->nullable();
            $table->decimal('previous_weight', 5, 2)->nullable();
            $table->decimal('previous_max_score', 8, 2)->nullable();
            $table->decimal('previous_numeric_grade', 5, 2)->nullable();
            $table->boolean('previous_is_official')->default(false);
            $table->boolean('previous_is_published')->default(false);
            $table->boolean('previous_is_adjusted')->default(false);
            $table->text('previous_adjustment_reason')->nullable();
            $table->json('metadata')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['user_id', 'is_official', 'is_published']);
            $table->index(['course_id', 'user_id', 'category']);
            $table->index(['enrollment_id', 'is_official']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};