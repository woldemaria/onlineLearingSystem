<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('group_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('submission_number')->default(1);
            $table->string('status', 30)->default('submitted');
            $table->json('content')->nullable();
            $table->json('files')->nullable();
            $table->string('external_url')->nullable();
            $table->integer('word_count')->nullable();
            $table->decimal('plagiarism_score', 5, 2)->nullable();
            $table->json('plagiarism_report')->nullable();
            $table->decimal('ai_originality_score', 5, 2)->nullable();
            $table->json('ai_feedback')->nullable();
            $table->boolean('is_late')->default(false);
            $table->decimal('late_penalty', 5, 2)->default(0);
            $table->timestamp('submitted_at');
            $table->timestamp('graded_at')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('grade', 8, 2)->nullable();
            $table->decimal('max_grade', 8, 2)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->string('letter_grade', 5)->nullable();
            $table->text('feedback')->nullable();
            $table->json('rubric_scores')->nullable();
            $table->string('review_status', 20)->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->json('peer_reviews')->nullable();
            $table->boolean('resubmission_requested')->default(false);
            $table->timestamp('resubmission_deadline')->nullable();
            $table->boolean('is_official')->default(false);
            $table->json('metadata')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['assignment_id', 'user_id', 'submission_number']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }
};