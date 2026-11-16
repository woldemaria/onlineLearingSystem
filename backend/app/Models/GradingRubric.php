<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradingRubric extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_id', 'assignment_id', 'creator_id', 'institution_id',
        'name', 'slug', 'description', 'criteria', 'total_points',
        'scale_type', 'is_published', 'is_default', 'status',
        'settings', 'version', 'parent_rubric_id',
    ];

    protected $casts = [
        'criteria' => 'array',
        'is_published' => 'boolean',
        'is_default' => 'boolean',
        'status' => 'boolean',
        'settings' => 'array',
        'total_points' => 'decimal:2',
        'version' => 'integer',
    ];

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function assignment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}