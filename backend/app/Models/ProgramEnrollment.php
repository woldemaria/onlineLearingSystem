<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramEnrollment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'program_id', 'user_id', 'institution_id', 'academic_year_id',
        'admission_date', 'expected_graduation', 'actual_graduation',
        'status', 'student_number', 'academic_standing', 'gpa', 'cgpa',
        'credits_earned', 'credits_required', 'graduation_eligibility',
        'advisor_id', 'settings',
    ];

    protected $casts = [
        'admission_date' => 'date',
        'expected_graduation' => 'date',
        'actual_graduation' => 'date',
        'gpa' => 'decimal:2',
        'cgpa' => 'decimal:2',
        'credits_earned' => 'integer',
        'credits_required' => 'integer',
        'graduation_eligibility' => 'boolean',
        'settings' => 'array',
    ];

    public function program(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AcademicProgram::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function academicYear(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function advisor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }
}