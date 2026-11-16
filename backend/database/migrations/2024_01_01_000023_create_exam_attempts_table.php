<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('enrollment_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('attempt_number')->default(1);
            $table->string('status', 30)->default('in_progress');
            $table->timestamp('started_at');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->integer('time_remaining_seconds')->nullable();
            $table->json('answers')->nullable();
            $table->decimal('score', 8, 2)->nullable();
            $table->decimal('max_score', 8, 2)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->string('grade', 5)->nullable();
            $table->string('pass_fail', 10)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device_id')->nullable();
            $table->json('browser_lockdown_events')->nullable();
            $table->integer('tab_switch_count')->default(0);
            $table->json('violations')->nullable();
            $table->decimal('plagiarism_score', 5, 2)->nullable();
            $table->boolean('plagiarism_flagged')->default(false);
            $table->boolean('identity_verified')->default(false);
            $table->json('identity_verification')->nullable();
            $table->json('proctoring_flags')->nullable();
            $table->string('review_status', 20)->default('pending');
            $table->text('review_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->boolean('is_graded')->default(false);
            $table->foreignId('graded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_official')->default(false);
            $table->timestamp('released_at')->nullable();
            $table->string('release_status', 20)->default('pending');
            $table->json('metadata')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['exam_id', 'user_id', 'attempt_number']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_attempts');
    }
};