<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeachingAssignment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'instructor_id', 'course_id', 'institution_id', 'semester_id',
        'academic_year_id', 'class_id', 'section', 'role',
        'status', 'assigned_at', 'unassigned_at', 'notes',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'unassigned_at' => 'datetime',
    ];

    public function instructor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function semester(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function academicYear(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}