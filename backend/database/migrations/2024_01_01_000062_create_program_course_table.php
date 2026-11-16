<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_course', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('academic_programs')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->integer('semester')->nullable();
            $table->boolean('is_required')->default(true);
            $table->integer('credits')->nullable();
            $table->timestamps();
            $table->unique(['program_id', 'course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_course');
    }
};