<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credential_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credential_id')->constrained()->cascadeOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('verification_method', 30);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->boolean('result')->default(false);
            $table->text('result_message')->nullable();
            $table->timestamp('requested_at');
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['credential_id', 'requested_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credential_verifications');
    }
};