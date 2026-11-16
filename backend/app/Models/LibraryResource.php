<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibraryResource extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'institution_id', 'organization_id', 'creator_id',
        'title', 'slug', 'description', 'type', 'category_id',
        'author_id', 'authors', 'publisher', 'isbn', 'issn',
        'doi', 'publication_date', 'edition', 'language',
        'media_id', 'file_path', 'file_size', 'mime_type',
        'access_level', 'license', 'license_type',
        'is_open_access', 'is_borrowable', 'borrow_duration_days',
        'status', 'is_published', 'published_at',
        'citation_count', 'views_count', 'downloads_count',
        'ratings_count', 'average_rating', 'tags', 'subjects',
        'metadata', 'settings',
    ];

    protected $casts = [
        'authors' => 'array',
        'tags' => 'array',
        'subjects' => 'array',
        'metadata' => 'array',
        'settings' => 'array',
        'is_open_access' => 'boolean',
        'is_borrowable' => 'boolean',
        'is_published' => 'boolean',
        'file_size' => 'integer',
        'borrow_duration_days' => 'integer',
        'citation_count' => 'integer',
        'views_count' => 'integer',
        'downloads_count' => 'integer',
        'ratings_count' => 'integer',
        'average_rating' => 'decimal:2',
        'published_at' => 'datetime',
        'publication_date' => 'date',
    ];

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function organization(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LibraryCategory::class, 'category_id');
    }

    public function media(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function collections(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(LibraryCollection::class, 'collection_resource');
    }

    public function scopeOpenAccess($query)
    {
        return $query->where('is_open_access', true);
    }
}