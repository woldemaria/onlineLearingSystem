<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event_type', 50);
            $table->string('severity', 20);
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('occurred_at');
            $table->index(['user_id', 'occurred_at']);
            $table->index(['event_type', 'occurred_at']);
            $table->index(['severity', 'is_resolved']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_events');
    }
};