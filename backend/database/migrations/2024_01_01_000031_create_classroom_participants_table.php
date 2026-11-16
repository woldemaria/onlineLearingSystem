<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classroom_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('virtual_classroom_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role', 30)->default('student');
            $table->string('status', 20)->default('joined');
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->boolean('is_recorded')->default(false);
            $table->boolean('is_muted')->default(true);
            $table->boolean('is_camera_on')->default(false);
            $table->boolean('is_screen_sharing')->default(false);
            $table->boolean('hand_raised')->default(false);
            $table->integer('chat_messages_count')->default(0);
            $table->integer('polls_responded')->default(0);
            $table->string('breakout_room_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['virtual_classroom_id', 'user_id']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classroom_participants');
    }
};