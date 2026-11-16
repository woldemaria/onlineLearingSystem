<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'institution_id', 'program_id', 'academic_year_id',
        'student_number', 'admission_year', 'expected_graduation_year',
        'class_of', 'academic_status', 'enrollment_status',
        'program_enrollment_id', 'advisor_id', 'guardian_ids',
        'emergency_contact', 'disability_accommodations',
        'boarding_status', 'hostel_id', 'room_id',
        'transport_id', 'fee_status', 'library_id', 'email',
        'phone', 'date_of_birth', 'gender', 'nationality',
        'blood_group', 'religion', 'caste_category',
        'mother_name', 'father_name', 'guardian_occupation',
        'guardian_income', 'previous_school', 'previous_board',
        'previous_percentage', 'transfer_certificate',
        'medical_history', 'immunization_record', 'photo',
        'is_active', 'settings',
    ];

    protected $casts = [
        'admission_year' => 'integer',
        'expected_graduation_year' => 'integer',
        'guardian_ids' => 'array',
        'emergency_contact' => 'array',
        'disability_accommodations' => 'array',
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
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

    public function programEnrollment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProgramEnrollment::class);
    }

    public function advisor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }

    public function enrollments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Enrollment::class, 'user_id');
    }

    public function attendances(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('enrollment_status', 'active');
    }
}