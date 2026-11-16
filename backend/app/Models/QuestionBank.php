<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionBank extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'institution_id', 'course_id', 'creator_id', 'category_id',
        'title', 'description', 'status', 'settings',
    ];

    protected $casts = ['status' => 'boolean', 'settings' => 'array'];

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function questions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Question::class);
    }
}