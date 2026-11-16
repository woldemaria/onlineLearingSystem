<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('enrollment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lesson_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('module_id')->nullable()->constrained('course_modules')->nullOnDelete();
            $table->foreignId('activity_id')->nullable()->constrained('learning_activities')->nullOnDelete();
            $table->string('type', 50);
            $table->string('context_type', 50)->nullable();
            $table->string('context_id', 100)->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();
            $table->index(['user_id', 'occurred_at']);
            $table->index(['course_id', 'occurred_at']);
            $table->index(['enrollment_id', 'occurred_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_events');
    }
};