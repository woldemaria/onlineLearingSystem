<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('device_id', 100)->unique();
            $table->string('device_name', 100)->nullable();
            $table->string('device_type', 30)->nullable();
            $table->string('platform', 30)->nullable();
            $table->string('app_version', 20)->nullable();
            $table->string('push_token', 255)->nullable();
            $table->string('push_provider', 30)->nullable();
            $table->boolean('is_trusted')->default(false);
            $table->timestamp('last_used_at')->nullable();
            $table->string('last_ip', 45)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['user_id', 'is_trusted']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_devices');
    }
};