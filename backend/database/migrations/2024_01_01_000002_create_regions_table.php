<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('code', 20)->unique();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->boolean('status')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['country_id', 'parent_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};