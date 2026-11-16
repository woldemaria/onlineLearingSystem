<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admission_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('program_id')->nullable()->constrained('academic_programs')->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained()->nullOnDelete();
            $table->string('application_number', 50)->unique();
            $table->string('status', 30)->default('submitted');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('decision_at')->nullable();
            $table->string('decision', 30)->nullable();
            $table->text('decision_notes')->nullable();
            $table->json('documents')->nullable();
            $table->timestamp('interview_scheduled_at')->nullable();
            $table->timestamp('interview_completed_at')->nullable();
            $table->integer('waitlist_position')->nullable();
            $table->string('admission_offer_id')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['institution_id', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_applications');
    }
};