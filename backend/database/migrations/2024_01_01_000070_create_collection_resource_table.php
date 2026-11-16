<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collection_resource', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('library_collections')->cascadeOnDelete();
            $table->foreignId('resource_id')->constrained('library_resources')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['collection_id', 'resource_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_resource');
    }
};