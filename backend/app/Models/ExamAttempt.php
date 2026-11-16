<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamAttempt extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'exam_id', 'user_id', 'enrollment_id', 'attempt_number',
        'status', 'started_at', 'submitted_at', 'graded_at',
        'time_remaining_seconds', 'answers', 'score', 'max_score',
        'percentage', 'grade', 'pass_fail', 'ip_address',
        'user_agent', 'device_id', 'browser_lockdown_events',
        'tab_switch_count', 'violations', 'plagiarism_score',
        'plagiarism_flagged', 'identity_verified', 'identity_verification',
        'proctoring_flags', 'review_status', 'review_notes',
        'reviewed_by', 'reviewed_at', 'is_graded', 'graded_by',
        'is_official', 'released_at', 'release_status',
        'metadata', 'settings',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'answers' => 'array',
        'score' => 'decimal:2',
        'max_score' => 'decimal:2',
        'percentage' => 'decimal:2',
        'browser_lockdown_events' => 'array',
        'violations' => 'array',
        'plagiarism_score' => 'decimal:2',
        'plagiarism_flagged' => 'boolean',
        'identity_verified' => 'boolean',
        'identity_verification' => 'array',
        'proctoring_flags' => 'array',
        'is_graded' => 'boolean',
        'is_official' => 'boolean',
        'released_at' => 'datetime',
        'metadata' => 'array',
        'settings' => 'array',
        'tab_switch_count' => 'integer',
        'attempt_number' => 'integer',
    ];

    public function exam(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function enrollment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function reviewedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function gradedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}