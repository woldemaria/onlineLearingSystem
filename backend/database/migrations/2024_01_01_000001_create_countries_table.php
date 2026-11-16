<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name');
            $table->string('iso2', 2)->unique();
            $table->string('phone_code', 10);
            $table->foreignId('region_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('status')->default(true);
            $table->boolean('data_residency_required')->default(false);
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};