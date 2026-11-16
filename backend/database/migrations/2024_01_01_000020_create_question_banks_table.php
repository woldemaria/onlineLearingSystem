<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_banks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['course_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_banks');
    }
};