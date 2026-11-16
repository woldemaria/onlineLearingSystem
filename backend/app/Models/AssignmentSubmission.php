<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignmentSubmission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'assignment_id', 'user_id', 'group_id', 'submission_number',
        'status', 'content', 'files', 'external_url',
        'word_count', 'plagiarism_score', 'plagiarism_report',
        'ai_originality_score', 'ai_feedback', 'is_late', 'late_penalty',
        'submitted_at', 'graded_at', 'graded_by', 'grade', 'max_grade',
        'percentage', 'letter_grade', 'feedback', 'rubric_scores',
        'review_status', 'reviewed_by', 'reviewed_at', 'peer_reviews',
        'resubmission_requested', 'resubmission_deadline',
        'is_official', 'metadata', 'settings',
    ];

    protected $casts = [
        'content' => 'array',
        'files' => 'array',
        'plagiarism_report' => 'array',
        'ai_feedback' => 'array',
        'rubric_scores' => 'array',
        'peer_reviews' => 'array',
        'is_late' => 'boolean',
        'late_penalty' => 'decimal:2',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'grade' => 'decimal:2',
        'max_grade' => 'decimal:2',
        'percentage' => 'decimal:2',
        'review_status' => 'boolean',
        'resubmission_requested' => 'boolean',
        'resubmission_deadline' => 'datetime',
        'is_official' => 'boolean',
        'metadata' => 'array',
        'settings' => 'array',
        'submission_number' => 'integer',
        'word_count' => 'integer',
        'plagiarism_score' => 'decimal:2',
        'ai_originality_score' => 'decimal:2',
    ];

    public function assignment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gradedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    public function reviewedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}