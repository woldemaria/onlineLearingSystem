<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institution_licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->string('license_key', 100)->unique();
            $table->integer('max_users')->default(0);
            $table->integer('used_users')->default(0);
            $table->string('type', 30)->default('site');
            $table->string('status', 30)->default('active');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->integer('seats_available')->default(0);
            $table->integer('seats_used')->default(0);
            $table->boolean('auto_renew')->default(false);
            $table->integer('renewal_count')->default(0);
            $table->json('billing_contact')->nullable();
            $table->string('billing_email')->nullable();
            $table->json('billing_address')->nullable();
            $table->json('metadata')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['organization_id', 'status']);
            $table->index(['institution_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institution_licenses');
    }
};