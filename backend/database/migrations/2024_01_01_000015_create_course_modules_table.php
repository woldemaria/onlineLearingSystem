<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('course_modules')->nullOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['course_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_modules');
    }
};