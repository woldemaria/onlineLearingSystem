<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('research_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('status', 30)->default('planning');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['institution_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('research_projects');
    }
};