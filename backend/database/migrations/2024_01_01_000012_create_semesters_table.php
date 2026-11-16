<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 20);
            $table->string('term', 30);
            $table->date('start_date');
            $table->date('end_date');
            $table->date('registration_start')->nullable();
            $table->date('registration_end')->nullable();
            $table->boolean('is_current')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['academic_year_id', 'code']);
            $table->index(['institution_id', 'is_current']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('semesters');
    }
};