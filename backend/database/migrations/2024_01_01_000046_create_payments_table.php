<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 30)->default('purchase');
            $table->string('status', 30)->default('pending');
            $table->string('provider', 50);
            $table->string('provider_payment_id')->nullable();
            $table->string('provider_intent_id')->nullable();
            $table->string('provider_subscription_id')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->bigInteger('amount_minor');
            $table->decimal('fee_amount', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->string('idempotency_key', 100)->unique();
            $table->boolean('captured')->default(false);
            $table->timestamp('captured_at')->nullable();
            $table->string('failure_code')->nullable();
            $table->text('failure_message')->nullable();
            $table->boolean('refunded')->default(false);
            $table->timestamp('refunded_at')->nullable();
            $table->foreignId('refunded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('refund_reason')->nullable();
            $table->boolean('is_disputed')->default(false);
            $table->timestamp('disputed_at')->nullable();
            $table->text('dispute_reason')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('payment_method')->nullable();
            $table->json('billing_details')->nullable();
            $table->json('shipping_details')->nullable();
            $table->boolean('reconciled')->default(false);
            $table->timestamp('reconciled_at')->nullable();
            $table->foreignId('reconciled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reconciliation_reference')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['user_id', 'status']);
            $table->index(['provider_payment_id']);
            $table->index(['idempotency_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};