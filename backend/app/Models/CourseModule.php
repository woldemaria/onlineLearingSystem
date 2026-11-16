<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseModule extends Model
{
    use SoftDeletes;

    protected $fillable = ['course_id', 'parent_id', 'title', 'slug', 'description', 'order', 'is_published', 'settings'];
    protected $casts = ['is_published' => 'boolean', 'settings' => 'array', 'order' => 'integer'];

    protected static function booted(): void
    {
        static::creating(function (self $mod) {
            if (empty($mod->slug)) {
                $mod->slug = \Illuminate\Support\Str::slug($mod->title);
            }
        });
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseModule::class, 'parent_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseModule::class, 'parent_id');
    }

    public function lessons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Lesson::class, 'module_id')->ordered();
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}