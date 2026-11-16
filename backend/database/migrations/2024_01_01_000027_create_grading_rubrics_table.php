<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grading_rubrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assignment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->json('criteria');
            $table->decimal('total_points', 8, 2)->default(100);
            $table->string('scale_type', 20)->default('points');
            $table->boolean('is_published')->default(false);
            $table->boolean('is_default')->default(false);
            $table->boolean('status')->default(true);
            $table->json('settings')->nullable();
            $table->integer('version')->default(1);
            $table->foreignId('parent_rubric_id')->nullable()->constrained('grading_rubrics')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['course_id', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grading_rubrics');
    }
};