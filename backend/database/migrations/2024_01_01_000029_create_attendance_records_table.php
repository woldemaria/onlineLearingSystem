<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->string('status', 30)->default('absent');
            $table->timestamp('arrival_time')->nullable();
            $table->timestamp('departure_time')->nullable();
            $table->integer('minutes_present')->default(0);
            $table->integer('minutes_absent')->default(0);
            $table->text('excuse')->nullable();
            $table->boolean('excuse_status')->default(false);
            $table->foreignId('excuse_approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('excuse_approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->string('method', 30)->default('manual');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['attendance_session_id', 'user_id']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};