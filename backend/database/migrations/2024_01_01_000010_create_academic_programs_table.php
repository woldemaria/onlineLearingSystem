<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('faculty_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('campus_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('code', 30)->unique();
            $table->string('degree_type', 50);
            $table->string('level', 30);
            $table->integer('credits_required')->default(0);
            $table->integer('duration_years')->default(0);
            $table->json('admission_requirements')->nullable();
            $table->json('curriculum')->nullable();
            $table->string('accreditation_body')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['institution_id', 'slug']);
            $table->index(['institution_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_programs');
    }
};