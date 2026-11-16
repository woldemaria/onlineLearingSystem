<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'author_id', 'institution_id', 'organization_id', 'course_id',
        'audience_type', 'audience_id', 'audience_ids',
        'title', 'slug', 'content', 'attachments',
        'is_pinned', 'is_published', 'published_at',
        'expires_at', 'priority', 'requires_acknowledgment',
        'acknowledgment_deadline', 'metadata', 'settings',
    ];

    protected $casts = [
        'audience_ids' => 'array',
        'attachments' => 'array',
        'is_pinned' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'priority' => 'integer',
        'requires_acknowledgment' => 'boolean',
        'acknowledgment_deadline' => 'datetime',
        'metadata' => 'array',
        'settings' => 'array',
    ];

    public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function organization(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}