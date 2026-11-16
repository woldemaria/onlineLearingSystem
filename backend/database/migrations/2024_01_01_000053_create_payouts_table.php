<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained()->cascadeOnDelete();
            $table->string('payout_number', 50)->unique();
            $table->string('status', 30)->default('pending');
            $table->string('method', 30);
            $table->decimal('gross_amount', 12, 2);
            $table->decimal('platform_fee', 12, 2)->default(0);
            $table->decimal('tax_deducted', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->json('bank_details')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('requested_at');
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->string('provider_payout_id')->nullable();
            $table->boolean('reconciled')->default(false);
            $table->timestamp('reconciled_at')->nullable();
            $table->foreignId('reconciled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('fiscal_year')->nullable();
            $table->integer('fiscal_month')->nullable();
            $table->string('invoice_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['instructor_id', 'status']);
            $table->index(['payout_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};