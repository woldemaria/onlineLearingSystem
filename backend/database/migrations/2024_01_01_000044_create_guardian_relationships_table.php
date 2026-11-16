<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guardian_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guardian_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('relationship_type', 30);
            $table->string('status', 30)->default('pending');
            $table->boolean('verified')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->string('verification_method', 30)->nullable();
            $table->json('verification_documents')->nullable();
            $table->json('consent_records')->nullable();
            $table->json('access_level')->nullable();
            $table->json('permissions')->nullable();
            $table->boolean('notifications_enabled')->default(true);
            $table->boolean('is_primary')->default(false);
            $table->boolean('revoked')->default(false);
            $table->timestamp('revoked_at')->nullable();
            $table->text('revocation_reason')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['guardian_id', 'student_id']);
            $table->index(['student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guardian_relationships');
    }
};