<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('research_collaborators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('research_projects')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role', 30)->default('collaborator');
            $table->string('status', 30)->default('pending');
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
            $table->unique(['project_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('research_collaborators');
    }
};