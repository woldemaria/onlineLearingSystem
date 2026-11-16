<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->text('reason')->nullable();
            $table->string('status', 30)->default('pending');
            $table->string('provider', 50)->nullable();
            $table->string('provider_refund_id')->nullable();
            $table->string('failure_code')->nullable();
            $table->text('failure_message')->nullable();
            $table->timestamp('requested_at');
            $table->timestamp('processed_at')->nullable();
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['payment_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};