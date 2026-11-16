<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 50);
            $table->string('channel', 20);
            $table->string('notifiable_type')->nullable();
            $table->string('notifiable_id')->nullable();
            $table->string('title');
            $table->longText('message');
            $table->json('data')->nullable();
            $table->string('action_url')->nullable();
            $table->string('action_label')->nullable();
            $table->integer('priority')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamp('read_at')->nullable();
            $table->foreignId('read_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('sent_at')->nullable();
            $table->string('delivery_status', 30)->default('pending');
            $table->json('delivery_response')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('group_key')->nullable();
            $table->boolean('is_ignored')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['user_id', 'read_at']);
            $table->index(['notifiable_type', 'notifiable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};