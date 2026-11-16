<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon_path')->nullable();
            $table->string('badge_path')->nullable();
            $table->string('type', 30)->default('badge');
            $table->string('category', 30)->default('learning');
            $table->json('criteria')->nullable();
            $table->integer('points')->default(0);
            $table->boolean('is_public')->default(true);
            $table->boolean('is_active')->default(true);
            $table->boolean('status')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['institution_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};