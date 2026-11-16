<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamQuestion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'exam_id', 'question_id', 'order', 'points', 'shuffle_options',
        'settings',
    ];

    protected $casts = [
        'order' => 'integer',
        'points' => 'decimal:2',
        'shuffle_options' => 'boolean',
        'settings' => 'array',
    ];

    public function exam(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function question(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}