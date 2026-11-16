<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event_type', 50);
            $table->string('event_name', 100);
            $table->string('context_type', 50)->nullable();
            $table->string('context_id', 100)->nullable();
            $table->json('properties')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('occurred_at');
            $table->index(['user_id', 'occurred_at']);
            $table->index(['event_type', 'occurred_at']);
            $table->index(['context_type', 'context_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_events');
    }
};