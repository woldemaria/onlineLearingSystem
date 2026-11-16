<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicProgram extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'institution_id', 'department_id', 'faculty_id', 'campus_id',
        'name', 'slug', 'code', 'degree_type', 'level', 'credits_required',
        'duration_years', 'admission_requirements', 'curriculum',
        'accreditation_body', 'status', 'published_at',
    ];

    protected $casts = [
        'admission_requirements' => 'array',
        'curriculum' => 'array',
        'status' => 'boolean',
        'published_at' => 'datetime',
        'credits_required' => 'integer',
        'duration_years' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $program) {
            if (empty($program->slug)) {
                $program->slug = \Illuminate\Support\Str::slug($program->name);
            }
        });
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function faculty(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function enrollments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProgramEnrollment::class);
    }

    public function courses(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Course::class, ProgramCourse::class, 'program_id', 'id', 'id', 'course_id');
    }
}