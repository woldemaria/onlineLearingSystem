<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instructor_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_item_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            $table->string('transaction_id', 100)->unique();
            $table->string('type', 30)->default('course_sale');
            $table->string('status', 30)->default('pending');
            $table->decimal('gross_amount', 12, 2);
            $table->decimal('platform_fee', 12, 2)->default(0);
            $table->decimal('tax_deducted', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('earned_at');
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('payout_id')->nullable()->constrained()->nullOnDelete();
            $table->string('payout_reference')->nullable();
            $table->integer('fiscal_year')->nullable();
            $table->integer('fiscal_month')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('invoice_path')->nullable();
            $table->boolean('is_invoiced')->default(false);
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['instructor_id', 'status']);
            $table->index(['earned_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructor_earnings');
    }
};