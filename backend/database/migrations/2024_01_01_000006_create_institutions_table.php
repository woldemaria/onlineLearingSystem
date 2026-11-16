<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type', 50);
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->foreignId('region_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('campus_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('status')->default(true);
            $table->string('verification_status', 30)->default('pending');
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->text('address')->nullable();
            $table->string('website')->nullable();
            $table->string('logo_path')->nullable();
            $table->json('branding')->nullable();
            $table->json('settings')->nullable();
            $table->json('academic_calendar')->nullable();
            $table->json('grading_policies')->nullable();
            $table->foreignId('subscription_plan_id')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->timestamp('onboarded_at')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'verification_status']);
            $table->index(['organization_id', 'status']);
            $table->index(['country_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};