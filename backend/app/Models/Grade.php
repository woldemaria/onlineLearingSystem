<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'enrollment_id', 'course_id', 'assignment_id',
        'exam_attempt_id', 'assignment_submission_id', 'graded_by',
        'approved_by', 'category', 'type', 'title', 'description',
        'score', 'max_score', 'weight', 'percentage',
        'letter_grade', 'numeric_grade', 'grade_point',
        'is_official', 'is_published', 'released_at',
        'is_adjusted', 'adjustment_reason', 'adjustment_at',
        'previous_score', 'previous_letter_grade',
        'previous_percentage', 'previous_grade_point',
        'previous_released_at', 'previous_approved_by',
        'previous_approved_at', 'previous_category',
        'previous_type', 'previous_title',
        'previous_description', 'previous_weight',
        'previous_max_score', 'previous_numeric_grade',
        'previous_is_official', 'previous_is_published',
        'previous_is_adjusted', 'previous_adjustment_reason',
        'metadata', 'settings',
    ];

    protected $casts = [
        'graded_at' => 'datetime',
        'approved_at' => 'datetime',
        'adjustment_at' => 'datetime',
        'weight' => 'decimal:2',
        'score' => 'decimal:2',
        'max_score' => 'decimal:2',
        'percentage' => 'decimal:2',
        'numeric_grade' => 'decimal:2',
        'grade_point' => 'decimal:2',
        'is_official' => 'boolean',
        'is_published' => 'boolean',
        'released_at' => 'datetime',
        'is_adjusted' => 'boolean',
        'previous_score' => 'decimal:2',
        'previous_max_score' => 'decimal:2',
        'previous_percentage' => 'decimal:2',
        'previous_numeric_grade' => 'decimal:2',
        'previous_grade_point' => 'decimal:2',
        'previous_released_at' => 'datetime',
        'previous_approved_at' => 'datetime',
        'metadata' => 'array',
        'settings' => 'array',
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

    public function assignment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function gradedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    public function approvedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeOfficial($query)
    {
        return $query->where('is_official', true);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}