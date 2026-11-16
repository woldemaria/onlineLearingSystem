<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('instructor_id')->nullable()->constrained('instructors')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('type', 30)->default('course');
            $table->string('status', 20)->default('draft');
            $table->decimal('price', 12, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->boolean('is_free')->default(false);
            $table->string('billing_cycle', 20)->nullable();
            $table->integer('billing_interval')->default(1);
            $table->integer('trial_days')->default(0);
            $table->decimal('billing_amount', 12, 2)->nullable();
            $table->string('billing_currency', 3)->nullable();
            $table->integer('max_users')->nullable();
            $table->json('features')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};