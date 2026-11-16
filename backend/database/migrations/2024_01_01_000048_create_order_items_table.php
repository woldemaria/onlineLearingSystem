<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 30);
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->json('metadata')->nullable();
            $table->timestamp('purchased_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};