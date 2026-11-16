<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_id', 'user_id', 'semester_id', 'academic_year_id',
        'status', 'enrollment_type', 'enrollment_date', 'completion_date',
        'grade', 'progress_percent', 'last_accessed_at',
        'completed_lessons_count', 'total_lessons_count',
        'is_offline_access_granted', 'certificate_issued',
        'final_grade', 'letter_grade', 'credits_earned',
        'withdrawal_reason', 'withdrawn_at', 'settings',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'completion_date' => 'date',
        'progress_percent' => 'decimal:2',
        'last_accessed_at' => 'datetime',
        'completed_lessons_count' => 'integer',
        'total_lessons_count' => 'integer',
        'is_offline_access_granted' => 'boolean',
        'certificate_issued' => 'boolean',
        'withdrawn_at' => 'datetime',
        'settings' => 'array',
    ];

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function semester(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function academicYear(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}