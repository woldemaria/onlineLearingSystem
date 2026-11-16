<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassSection extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'institution_id', 'course_id', 'academic_program_id',
        'academic_year_id', 'semester_id', 'faculty_id', 'department_id',
        'name', 'code', 'section', 'capacity', 'current_enrollment',
        'schedule', 'room', 'instructor_id', 'status', 'settings',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'current_enrollment' => 'integer',
        'schedule' => 'array',
        'status' => 'boolean',
        'settings' => 'array',
    ];

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function academicProgram(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AcademicProgram::class);
    }

    public function academicYear(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function semester(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function faculty(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function instructor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }
}