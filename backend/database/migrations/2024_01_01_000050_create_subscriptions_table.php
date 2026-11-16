<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status', 30)->default('active');
            $table->string('provider', 50)->nullable();
            $table->string('provider_subscription_id')->nullable();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('renewal_at')->nullable();
            $table->integer('renewal_count')->default(0);
            $table->string('currency', 3)->default('USD');
            $table->decimal('amount', 12, 2)->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['user_id', 'status']);
            $table->index(['product_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};