<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceSession extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'class_section_id', 'course_id', 'instructor_id', 'institution_id',
        'semester_id', 'academic_year_id', 'title', 'type',
        'scheduled_at', 'actual_start_at', 'actual_end_at',
        'duration_minutes', 'location', 'virtual_classroom_id',
        'method', 'status', 'notes', 'settings',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'actual_start_at' => 'datetime',
        'actual_end_at' => 'datetime',
        'duration_minutes' => 'integer',
        'status' => 'boolean',
        'settings' => 'array',
    ];

    public function classSection(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ClassSection::class);
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function records(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AttendanceRecord::class);
    }
}