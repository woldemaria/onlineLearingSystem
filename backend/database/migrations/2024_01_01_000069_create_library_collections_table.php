<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('type', 30)->default('custom');
            $table->boolean('is_public')->default(false);
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['institution_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_collections');
    }
};