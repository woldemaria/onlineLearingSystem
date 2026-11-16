<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('achievement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('enrollment_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('earned_at');
            $table->foreignId('earned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_public')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->json('metadata')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['user_id', 'achievement_id']);
            $table->index(['user_id', 'earned_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_achievements');
    }
};