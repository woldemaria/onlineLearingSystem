<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Institution extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'organization_id', 'name', 'slug', 'type',
        'country_id', 'region_id', 'campus_id', 'status',
        'verification_status', 'contact_email', 'contact_phone',
        'address', 'website', 'logo_path', 'branding',
        'settings', 'academic_calendar', 'grading_policies',
        'subscription_plan_id', 'subscription_ends_at',
        'onboarded_at', 'suspended_at',
    ];

    protected $casts = [
        'branding' => 'array',
        'settings' => 'array',
        'academic_calendar' => 'array',
        'grading_policies' => 'array',
        'status' => 'boolean',
        'suspended_at' => 'datetime',
        'onboarded_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $inst) {
            if (empty($inst->uuid)) {
                $inst->uuid = (string) Str::uuid();
            }
            if (empty($inst->slug)) {
                $inst->slug = Str::slug($inst->name);
            }
        });
    }

    public function organization(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function campus(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function campuses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Campus::class);
    }

    public function faculties(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Faculty::class);
    }

    public function departments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function programs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AcademicProgram::class);
    }

    public function courses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'institution_user')
            ->withPivot([
                'role', 'status', 'is_primary',
                'employment_type', 'employment_started_at',
                'student_number', 'admission_year', 'expected_graduation_year',
                'academic_status', 'class_of',
            ])
            ->withTimestamps();
    }

    public function scopeApproved($query)
    {
        return $query->where('verification_status', 'approved')->where('status', true);
    }
}