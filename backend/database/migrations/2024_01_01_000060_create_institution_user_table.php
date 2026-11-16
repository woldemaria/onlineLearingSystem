<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institution_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role', 50);
            $table->string('status', 30)->default('active');
            $table->boolean('is_primary')->default(false);
            $table->string('employment_type')->nullable();
            $table->timestamp('employment_started_at')->nullable();
            $table->string('student_number')->nullable();
            $table->integer('admission_year')->nullable();
            $table->integer('expected_graduation_year')->nullable();
            $table->string('academic_status')->nullable();
            $table->string('class_of')->nullable();
            $table->timestamps();
            $table->unique(['institution_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institution_user');
    }
};