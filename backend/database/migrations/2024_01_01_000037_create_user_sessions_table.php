<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('token', 255)->unique();
            $table->string('device_id', 100)->nullable();
            $table->string('device_name', 100)->nullable();
            $table->string('device_type', 30)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_current')->default(false);
            $table->timestamp('last_activity_at');
            $table->timestamp('expires_at');
            $table->timestamp('revoked_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['user_id', 'is_current']);
            $table->index(['token']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};