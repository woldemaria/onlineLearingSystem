<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consent_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guardian_relationship_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('guardian_id')->constrained('users')->cascadeOnDelete();
            $table->string('consent_type', 50);
            $table->json('scope')->nullable();
            $table->boolean('granted')->default(false);
            $table->timestamp('granted_at')->nullable();
            $table->foreignId('granted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('revoked')->default(false);
            $table->timestamp('revoked_at')->nullable();
            $table->foreignId('revoked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('revocation_reason')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['guardian_relationship_id', 'consent_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consent_records');
    }
};