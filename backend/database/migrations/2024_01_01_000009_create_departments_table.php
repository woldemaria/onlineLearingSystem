<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('faculty_id')->nullable()->constrained('faculties')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('code', 20)->unique();
            $table->foreignId('head_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('settings')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['institution_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};