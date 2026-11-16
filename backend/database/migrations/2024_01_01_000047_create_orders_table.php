<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->string('number', 50)->unique();
            $table->string('status', 30)->default('pending');
            $table->string('type', 30)->default('purchase');
            $table->string('currency', 3)->default('USD');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->json('billing_address')->nullable();
            $table->json('shipping_address')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('placed_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['user_id', 'status']);
            $table->index(['number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};