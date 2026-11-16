<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAchievement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'achievement_id', 'course_id', 'enrollment_id',
        'earned_at', 'earned_by', 'is_public', 'is_verified',
        'metadata', 'notes',
    ];

    protected $casts = [
        'earned_at' => 'datetime',
        'is_public' => 'boolean',
        'is_verified' => 'boolean',
        'metadata' => 'array',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function achievement(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Achievement::class);
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function earnedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'earned_by');
    }
}