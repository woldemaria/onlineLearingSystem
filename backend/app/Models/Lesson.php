<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'module_id', 'course_id', 'title', 'slug', 'description',
        'type', 'content', 'content_format', 'media_id', 'external_url',
        'duration_minutes', 'order', 'is_published', 'is_free_preview',
        'prerequisites', 'learning_objectives', 'settings',
        'offline_download_enabled', 'ai_summary', 'ai_transcript',
    ];

    protected $casts = [
        'content' => 'array',
        'prerequisites' => 'array',
        'learning_objectives' => 'array',
        'settings' => 'array',
        'is_published' => 'boolean',
        'is_free_preview' => 'boolean',
        'offline_download_enabled' => 'boolean',
        'duration_minutes' => 'integer',
        'order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $lesson) {
            if (empty($lesson->slug)) {
                $lesson->slug = \Illuminate\Support\Str::slug($lesson->title);
            }
        });
    }

    public function module(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function media(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function activities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LearningActivity::class, 'lesson_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}