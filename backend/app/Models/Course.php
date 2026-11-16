<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'institution_id', 'organization_id', 'creator_id',
        'academic_program_id', 'department_id', 'faculty_id',
        'title', 'slug', 'subtitle', 'description', 'objectives',
        'category_id', 'subject_id', 'language', 'level',
        'type', 'status', 'state', 'visibility',
        'price', 'currency', 'is_free', 'enrollment_policy',
        'max_enrollments', 'current_enrollments',
        'credits', 'duration_hours', 'estimated_weeks',
        'prerequisites', 'co_requisites',
        'syllabus', 'grading_scheme', 'passing_grade',
        'published_at', 'scheduled_at', 'archived_at', 'retired_at',
        'version', 'parent_course_id', 'is_published',
        'is_listed', 'is_certified', 'thumbnail_path', 'preview_video_path',
        'settings', 'ai_tutor_enabled', 'offline_download_enabled',
        'proctoring_required', 'accreditation_body',
    ];

    protected $casts = [
        'objectives' => 'array',
        'prerequisites' => 'array',
        'co_requisites' => 'array',
        'syllabus' => 'array',
        'grading_scheme' => 'array',
        'settings' => 'array',
        'is_free' => 'boolean',
        'is_published' => 'boolean',
        'is_listed' => 'boolean',
        'is_certified' => 'boolean',
        'ai_tutor_enabled' => 'boolean',
        'offline_download_enabled' => 'boolean',
        'proctoring_required' => 'boolean',
        'price' => 'decimal:2',
        'max_enrollments' => 'integer',
        'current_enrollments' => 'integer',
        'credits' => 'integer',
        'duration_hours' => 'integer',
        'estimated_weeks' => 'integer',
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'archived_at' => 'datetime',
        'retired_at' => 'datetime',
        'version' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $course) {
            if (empty($course->slug)) {
                $course->slug = \Illuminate\Support\Str::slug($course->title);
            }
            if (empty($course->state)) {
                $course->state = 'draft';
            }
            if (empty($course->visibility)) {
                $course->visibility = 'private';
            }
        });
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function organization(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'category_id');
    }

    public function academicProgram(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AcademicProgram::class);
    }

    public function modules(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseModule::class)->ordered();
    }

    public function enrollments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function lessons(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Lesson::class, CourseModule::class, 'course_id', 'module_id');
    }

    public function instructors(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_instructor')
            ->withPivot(['role', 'is_primary', 'status', 'assigned_at'])
            ->withTimestamps();
    }

    public function scopePublished($query)
    {
        return $query->where('state', 'published')->where('visibility', 'public');
    }

    public function scopeListed($query)
    {
        return $query->where('is_listed', true);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%")
              ->orWhere('subtitle', 'like', "%{$term}%");
        });
    }
}