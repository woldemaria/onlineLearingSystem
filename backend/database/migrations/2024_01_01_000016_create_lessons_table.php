<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('course_modules')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('type', 30)->default('video');
            $table->json('content')->nullable();
            $table->string('content_format', 20)->nullable();
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('external_url')->nullable();
            $table->integer('duration_minutes')->default(0);
            $table->integer('order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->boolean('is_free_preview')->default(false);
            $table->json('prerequisites')->nullable();
            $table->json('learning_objectives')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('offline_download_enabled')->default(false);
            $table->json('ai_summary')->nullable();
            $table->json('ai_transcript')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['module_id', 'order']);
            $table->index(['course_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};