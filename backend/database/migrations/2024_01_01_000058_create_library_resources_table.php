<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->longText('description')->nullable();
            $table->string('type', 30);
            $table->foreignId('category_id')->nullable()->constrained('library_categories')->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('library_authors')->nullOnDelete();
            $table->json('authors')->nullable();
            $table->string('publisher')->nullable();
            $table->string('isbn', 20)->nullable();
            $table->string('issn', 10)->nullable();
            $table->string('doi')->nullable();
            $table->date('publication_date')->nullable();
            $table->string('edition')->nullable();
            $table->string('language', 10)->default('en');
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('file_path')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('access_level', 30)->default('public');
            $table->string('license', 50)->nullable();
            $table->string('license_type', 30)->nullable();
            $table->boolean('is_open_access')->default(false);
            $table->boolean('is_borrowable')->default(false);
            $table->integer('borrow_duration_days')->default(14);
            $table->string('status', 20)->default('draft');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->integer('citation_count')->default(0);
            $table->integer('views_count')->default(0);
            $table->integer('downloads_count')->default(0);
            $table->integer('ratings_count')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->json('tags')->nullable();
            $table->json('subjects')->nullable();
            $table->json('metadata')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['institution_id', 'is_published']);
            $table->index(['category_id', 'is_published']);
            $table->index(['type', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_resources');
    }
};