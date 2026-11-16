<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 30);
            $table->string('disk', 50);
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type');
            $table->bigInteger('size');
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->integer('bitrate')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('alt_text')->nullable();
            $table->string('caption')->nullable();
            $table->boolean('is_public')->default(false);
            $table->string('visibility', 20)->default('private');
            $table->string('storage_provider', 50)->default('local');
            $table->string('processing_status', 30)->default('pending');
            $table->json('processing_metadata')->nullable();
            $table->string('cdn_url')->nullable();
            $table->string('checksum', 64)->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['user_id', 'type']);
            $table->index(['checksum']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};