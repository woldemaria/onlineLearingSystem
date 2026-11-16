<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LearningActivity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_id', 'module_id', 'lesson_id', 'creator_id',
        'title', 'type', 'description', 'content',
        'media_id', 'external_url', 'duration_minutes',
        'order', 'is_published', 'is_required',
        'completion_criteria', 'settings',
        'max_score', 'passing_score', 'ai_assisted',
    ];

    protected $casts = [
        'content' => 'array',
        'completion_criteria' => 'array',
        'settings' => 'array',
        'is_published' => 'boolean',
        'is_required' => 'boolean',
        'ai_assisted' => 'boolean',
        'duration_minutes' => 'integer',
        'order' => 'integer',
        'max_score' => 'integer',
        'passing_score' => 'integer',
    ];

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function module(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseModule::class);
    }

    public function lesson(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function media(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function submissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ActivitySubmission::class);
    }
}