<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmissionApplication extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'institution_id', 'program_id', 'academic_year_id',
        'application_number', 'status', 'submitted_at', 'reviewed_at',
        'decision_at', 'decision', 'decision_notes', 'documents',
        'interview_scheduled_at', 'interview_completed_at',
        'waitlist_position', 'admission_offer_id', 'settings',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'decision_at' => 'datetime',
        'documents' => 'array',
        'interview_scheduled_at' => 'datetime',
        'interview_completed_at' => 'datetime',
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

    public function program(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AcademicProgram::class);
    }

    public function academicYear(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}