<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AiSession extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'institution_id', 'course_id', 'type',
        'context_type', 'context_id', 'model', 'provider',
        'prompt_tokens', 'completion_tokens', 'total_tokens',
        'cost', 'status', 'is_flagged', 'flag_reason',
        'safety_score', 'hallucination_risk', 'human_reviewed',
        'reviewed_by', 'reviewed_at', 'review_notes',
        'metadata', 'settings', 'ip_address', 'user_agent',
    ];

    protected $casts = [
        'prompt_tokens' => 'integer',
        'completion_tokens' => 'integer',
        'total_tokens' => 'integer',
        'cost' => 'decimal:6',
        'is_flagged' => 'boolean',
        'safety_score' => 'decimal:2',
        'hallucination_risk' => 'decimal:2',
        'human_reviewed' => 'boolean',
        'reviewed_at' => 'datetime',
        'metadata' => 'array',
        'settings' => 'array',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function reviewedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function messages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AiMessage::class);
    }
}