<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('module_id')->nullable()->constrained('course_modules')->nullOnDelete();
            $table->foreignId('lesson_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('type', 30);
            $table->text('description')->nullable();
            $table->json('content')->nullable();
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('external_url')->nullable();
            $table->integer('duration_minutes')->default(0);
            $table->integer('order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->boolean('is_required')->default(true);
            $table->json('completion_criteria')->nullable();
            $table->json('settings')->nullable();
            $table->integer('max_score')->default(0);
            $table->integer('passing_score')->default(0);
            $table->boolean('ai_assisted')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['course_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_activities');
    }
};