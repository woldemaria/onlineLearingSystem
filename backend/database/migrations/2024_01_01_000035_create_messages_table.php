<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('parent_message_id')->nullable()->constrained('messages')->nullOnDelete();
            $table->string('subject')->nullable();
            $table->longText('body');
            $table->string('type', 30)->default('direct');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->boolean('is_starred')->default(false);
            $table->boolean('is_archived')->default(false);
            $table->boolean('is_flagged')->default(false);
            $table->json('attachments')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['sender_id', 'receiver_id', 'is_read']);
            $table->index(['course_id', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};