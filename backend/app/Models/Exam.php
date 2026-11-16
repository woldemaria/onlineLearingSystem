<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_id', 'module_id', 'creator_id', 'institution_id',
        'title', 'slug', 'description', 'type', 'status',
        'total_questions', 'total_marks', 'passing_marks',
        'duration_minutes', 'start_time', 'end_time',
        'allow_resume', 'max_attempts', 'shuffle_questions',
        'shuffle_options', 'show_results_immediately',
        'release_results_at', 'proctoring_enabled',
        'identity_verification_required', 'allowed_attempts',
        'question_bank_ids', 'question_selection',
        'random_question_count', 'random_question_marks',
        'negative_marking', 'negative_mark_value',
        'calculator_allowed', 'formula_sheet', 'notes_allowed',
        'accommodations', 'access_code', 'ip_restriction',
        'browser_lockdown', 'tab_switch_detection',
        'webcam_required', 'screen_sharing_required',
        'plagiarism_detection', 'plagiarism_threshold',
        'is_published', 'scheduled_at', 'published_at',
        'archived_at', 'settings', 'version', 'parent_exam_id',
    ];

    protected $casts = [
        'question_bank_ids' => 'array',
        'accommodations' => 'array',
        'formula_sheet' => 'array',
        'negative_marking' => 'boolean',
        'browser_lockdown' => 'boolean',
        'tab_switch_detection' => 'boolean',
        'webcam_required' => 'boolean',
        'screen_sharing_required' => 'boolean',
        'plagiarism_detection' => 'boolean',
        'is_published' => 'boolean',
        'total_questions' => 'integer',
        'total_marks' => 'integer',
        'passing_marks' => 'integer',
        'duration_minutes' => 'integer',
        'max_attempts' => 'integer',
        'allowed_attempts' => 'integer',
        'random_question_count' => 'integer',
        'random_question_marks' => 'integer',
        'negative_mark_value' => 'decimal:2',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'scheduled_at' => 'datetime',
        'published_at' => 'datetime',
        'archived_at' => 'datetime',
        'version' => 'integer',
        'settings' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $exam) {
            if (empty($exam->slug)) {
                $exam->slug = \Illuminate\Support\Str::slug($exam->title);
            }
        });
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function module(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseModule::class);
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function questions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function attempts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}