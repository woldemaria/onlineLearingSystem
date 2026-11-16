<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->morphs('profileable');
            $table->string('legal_name')->nullable();
            $table->string('preferred_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('nationality')->nullable();
            $table->text('bio')->nullable();
            $table->string('website')->nullable();
            $table->boolean('public_portfolio')->default(false);
            $table->text('learning_goals')->nullable();
            $table->json('skills')->nullable();
            $table->json('interests')->nullable();
            $table->json('education_background')->nullable();
            $table->json('professional_experience')->nullable();
            $table->json('languages')->nullable();
            $table->json('social_links')->nullable();
            $table->json('visibility_settings')->nullable();
            $table->boolean('verified_credentials_display')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};