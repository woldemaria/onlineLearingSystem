<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('password');
            $table->string('display_name')->nullable();
            $table->string('avatar_path')->nullable();
            $table->text('bio')->nullable();
            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('region_id')->nullable()->constrained()->nullOnDelete();
            $table->string('timezone', 50)->default('UTC');
            $table->string('locale', 10)->default('en');
            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('password_changed_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->timestamp('deletion_requested_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['is_active', 'email']);
            $table->index(['is_active', 'phone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};