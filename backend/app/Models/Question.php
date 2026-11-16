<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'question_bank_id', 'exam_id', 'creator_id', 'institution_id',
        'type', 'question_text', 'media_id', 'explanation',
        'options', 'correct_answer', 'correct_answers',
        'points', 'difficulty', 'topic', 'skills', 'keywords',
        'is_ai_generated', 'ai_generated_by', 'ai_reviewed_by',
        'ai_reviewed_at', 'ai_confidence', 'usage_count',
        'success_rate', 'status', 'version', 'parent_question_id',
        'settings',
    ];

    protected $casts = [
        'options' => 'array',
        'correct_answer' => 'array',
        'correct_answers' => 'array',
        'skills' => 'array',
        'keywords' => 'array',
        'is_ai_generated' => 'boolean',
        'usage_count' => 'integer',
        'success_rate' => 'decimal:2',
        'ai_confidence' => 'decimal:2',
        'status' => 'boolean',
        'ai_reviewed_at' => 'datetime',
        'version' => 'integer',
        'settings' => 'array',
        'points' => 'decimal:2',
    ];

    public function questionBank(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(QuestionBank::class);
    }

    public function exam(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function aiReviewedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'ai_reviewed_by');
    }
}