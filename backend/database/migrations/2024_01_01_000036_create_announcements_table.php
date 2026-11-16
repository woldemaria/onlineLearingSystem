<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->string('audience_type', 30);
            $table->string('audience_id', 100)->nullable();
            $table->json('audience_ids')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->longText('content');
            $table->json('attachments')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->integer('priority')->default(0);
            $table->boolean('requires_acknowledgment')->default(false);
            $table->timestamp('acknowledgment_deadline')->nullable();
            $table->json('metadata')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['institution_id', 'is_published']);
            $table->index(['course_id', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};