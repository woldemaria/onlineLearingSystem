<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_session_id')->constrained()->cascadeOnDelete();
            $table->string('role', 20);
            $table->longText('content');
            $table->json('sources')->nullable();
            $table->integer('tokens_used')->default(0);
            $table->integer('latency_ms')->default(0);
            $table->string('model', 100)->nullable();
            $table->string('provider', 50)->nullable();
            $table->boolean('is_flagged')->default(false);
            $table->text('flag_reason')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['ai_session_id', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_messages');
    }
};