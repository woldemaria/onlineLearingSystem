<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credentials', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('issuer_id')->nullable();
            $table->string('issuer_type', 100)->nullable();
            $table->foreignId('recipient_id')->constrained('users')->cascadeOnDelete();
            $table->string('recipient_type', 100)->default('App\\Models\\User');
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('enrollment_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 30)->default('certificate');
            $table->string('category', 30)->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('criteria')->nullable();
            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();
            $table->date('expiration_date')->nullable();
            $table->boolean('revoked')->default(false);
            $table->timestamp('revoked_at')->nullable();
            $table->text('revocation_reason')->nullable();
            $table->string('verification_code', 20)->unique();
            $table->string('verification_url')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->boolean('is_public')->default(true);
            $table->boolean('is_verifiable')->default(true);
            $table->timestamp('issued_at');
            $table->timestamp('expires_at')->nullable();
            $table->json('metadata')->nullable();
            $table->json('attachments')->nullable();
            $table->json('tags')->nullable();
            $table->json('skills')->nullable();
            $table->json('alignment')->nullable();
            $table->json('evidence')->nullable();
            $table->string('badge_class_id')->nullable();
            $table->string('open_badges_id')->nullable();
            $table->string('clr_id')->nullable();
            $table->string('accreditation')->nullable();
            $table->string('level')->nullable();
            $table->string('grade', 5)->nullable();
            $table->decimal('score', 8, 2)->nullable();
            $table->decimal('score_max', 8, 2)->nullable();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['recipient_id', 'revoked']);
            $table->index(['verification_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credentials');
    }
};