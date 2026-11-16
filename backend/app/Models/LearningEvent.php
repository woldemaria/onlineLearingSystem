<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LearningEvent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'enrollment_id', 'course_id', 'lesson_id',
        'module_id', 'activity_id', 'type', 'context_type',
        'context_id', 'duration_seconds', 'metadata',
        'ip_address', 'user_agent', 'occurred_at',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'metadata' => 'array',
        'occurred_at' => 'datetime',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function enrollment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lesson(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function module(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseModule::class);
    }

    public function activity(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LearningActivity::class, 'activity_id');
    }
}