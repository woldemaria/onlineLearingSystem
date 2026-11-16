<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instructor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'institution_id', 'organization_id',
        'employee_id', 'department_id', 'faculty_id',
        'designation', 'employment_type', 'employment_started_at',
        'employment_ended_at', 'qualifications', 'experience',
        'teaching_subjects', 'languages', 'specializations',
        'office_hours', 'office_location', 'contact_email',
        'contact_phone', 'is_verified', 'verification_documents',
        'verification_status', 'verified_at', 'verified_by',
        'rating', 'total_reviews', 'total_courses',
        'total_students', 'earnings_total', 'earnings_pending',
        'earnings_paid', 'bank_details', 'tax_id', 'is_active',
        'settings', 'bio', 'profile_photo_path', 'website',
    ];

    protected $casts = [
        'qualifications' => 'array',
        'experience' => 'array',
        'teaching_subjects' => 'array',
        'languages' => 'array',
        'specializations' => 'array',
        'office_hours' => 'array',
        'verification_documents' => 'array',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'rating' => 'decimal:2',
        'total_reviews' => 'integer',
        'total_courses' => 'integer',
        'total_students' => 'integer',
        'earnings_total' => 'decimal:2',
        'earnings_pending' => 'decimal:2',
        'earnings_paid' => 'decimal:2',
        'bank_details' => 'array',
        'is_active' => 'boolean',
        'employment_started_at' => 'date',
        'employment_ended_at' => 'date',
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

    public function organization(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function faculty(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function courses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_instructor')
            ->withPivot(['role', 'is_primary', 'status', 'assigned_at'])
            ->withTimestamps();
    }

    public function earningsTransactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InstructorEarning::class);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true)->where('verification_status', 'approved');
    }
}