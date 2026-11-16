<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Semester extends Model
{
    use SoftDeletes;

    protected $fillable = ['academic_year_id', 'institution_id', 'name', 'code', 'term', 'start_date', 'end_date', 'registration_start', 'registration_end', 'is_current', 'status'];
    protected $casts = [
        'start_date' => 'date', 'end_date' => 'date',
        'registration_start' => 'date', 'registration_end' => 'date',
        'is_current' => 'boolean', 'status' => 'boolean',
    ];

    public function academicYear(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}