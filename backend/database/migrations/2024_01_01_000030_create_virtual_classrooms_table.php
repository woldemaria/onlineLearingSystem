<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('virtual_classrooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('module_id')->nullable()->constrained('course_modules')->nullOnDelete();
            $table->foreignId('lesson_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('instructor_id')->nullable()->constrained('instructors')->nullOnDelete();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('type', 30)->default('live');
            $table->string('status', 20)->default('scheduled');
            $table->timestamp('scheduled_at');
            $table->integer('duration_minutes')->default(60);
            $table->timestamp('actual_start_at')->nullable();
            $table->timestamp('actual_end_at')->nullable();
            $table->string('provider', 50);
            $table->string('provider_room_id')->nullable();
            $table->string('provider_room_url')->nullable();
            $table->string('recording_url')->nullable();
            $table->string('recording_provider_id')->nullable();
            $table->string('recording_status', 30)->default('pending');
            $table->boolean('is_recorded')->default(false);
            $table->boolean('is_captions_enabled')->default(false);
            $table->boolean('is_transcript_enabled')->default(false);
            $table->boolean('is_chat_enabled')->default(true);
            $table->boolean('is_polls_enabled')->default(false);
            $table->boolean('is_breakout_rooms_enabled')->default(false);
            $table->boolean('is_whiteboard_enabled')->default(false);
            $table->boolean('is_screen_sharing_enabled')->default(false);
            $table->integer('max_participants')->default(100);
            $table->boolean('waiting_room_enabled')->default(true);
            $table->boolean('mute_on_entry')->default(true);
            $table->boolean('allow_join_before_start')->default(true);
            $table->boolean('allow_recordings_download')->default(false);
            $table->string('access_code')->nullable();
            $table->string('password')->nullable();
            $table->json('ip_whitelist')->nullable();
            $table->json('domain_whitelist')->nullable();
            $table->boolean('is_archived')->default(false);
            $table->timestamp('archived_at')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['course_id', 'scheduled_at']);
            $table->index(['instructor_id', 'scheduled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('virtual_classrooms');
    }
};