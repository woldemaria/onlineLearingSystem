<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 30)->default('tutor');
            $table->string('context_type', 30)->nullable();
            $table->string('context_id', 100)->nullable();
            $table->string('model', 100);
            $table->string('provider', 50);
            $table->integer('prompt_tokens')->default(0);
            $table->integer('completion_tokens')->default(0);
            $table->integer('total_tokens')->default(0);
            $table->decimal('cost', 10, 6)->default(0);
            $table->string('status', 20)->default('active');
            $table->boolean('is_flagged')->default(false);
            $table->text('flag_reason')->nullable();
            $table->decimal('safety_score', 5, 2)->nullable();
            $table->decimal('hallucination_risk', 5, 2)->nullable();
            $table->boolean('human_reviewed')->default(false);
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->json('metadata')->nullable();
            $table->json('settings')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['user_id', 'status']);
            $table->index(['course_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_sessions');
    }
};