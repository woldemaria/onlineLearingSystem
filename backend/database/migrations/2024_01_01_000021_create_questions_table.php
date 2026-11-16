<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_bank_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('exam_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 30);
            $table->longText('question_text');
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->text('explanation')->nullable();
            $table->json('options')->nullable();
            $table->json('correct_answer')->nullable();
            $table->json('correct_answers')->nullable();
            $table->decimal('points', 5, 2)->default(1);
            $table->string('difficulty', 20)->default('medium');
            $table->string('topic')->nullable();
            $table->json('skills')->nullable();
            $table->json('keywords')->nullable();
            $table->boolean('is_ai_generated')->default(false);
            $table->string('ai_generated_by')->nullable();
            $table->foreignId('ai_reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('ai_reviewed_at')->nullable();
            $table->decimal('ai_confidence', 5, 2)->nullable();
            $table->integer('usage_count')->default(0);
            $table->decimal('success_rate', 5, 2)->nullable();
            $table->boolean('status')->default(true);
            $table->integer('version')->default(1);
            $table->foreignId('parent_question_id')->nullable()->constrained('questions')->nullOnDelete();
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['question_bank_id', 'status']);
            $table->index(['exam_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};