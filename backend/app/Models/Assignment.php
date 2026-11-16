<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_id', 'module_id', 'lesson_id', 'creator_id',
        'institution_id', 'title', 'slug', 'description',
        'instructions', 'type', 'status', 'max_score', 'passing_score',
        'due_date', 'allow_late_submission', 'late_submission_penalty',
        'allow_resubmission', 'max_attempts', 'group_assignment',
        'group_size', 'peer_review_enabled', 'rubric_id',
        'plagiarism_detection', 'allowed_file_types',
        'max_file_size', 'external_tool', 'external_url',
        'is_published', 'scheduled_at', 'published_at',
        'ai_assistance_enabled', 'ai_feedback_enabled',
        'ai_originality_check', 'release_feedback_automatically',
        'release_grades_automatically', 'accommodations',
        'settings', 'version', 'parent_assignment_id',
    ];

    protected $casts = [
        'allowed_file_types' => 'array',
        'max_file_size' => 'integer',
        'due_date' => 'datetime',
        'allow_late_submission' => 'boolean',
        'late_submission_penalty' => 'decimal:2',
        'allow_resubmission' => 'boolean',
        'max_attempts' => 'integer',
        'group_assignment' => 'boolean',
        'group_size' => 'integer',
        'peer_review_enabled' => 'boolean',
        'plagiarism_detection' => 'boolean',
        'is_published' => 'boolean',
        'scheduled_at' => 'datetime',
        'published_at' => 'datetime',
        'ai_assistance_enabled' => 'boolean',
        'ai_feedback_enabled' => 'boolean',
        'ai_originality_check' => 'boolean',
        'release_feedback_automatically' => 'boolean',
        'release_grades_automatically' => 'boolean',
        'accommodations' => 'array',
        'settings' => 'array',
        'version' => 'integer',
        'max_score' => 'decimal:2',
        'passing_score' => 'decimal:2',
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

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function rubric(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(GradingRubric::class, 'rubric_id');
    }

    public function submissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}