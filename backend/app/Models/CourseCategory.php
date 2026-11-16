<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseCategory extends Model
{
    use SoftDeletes;

    protected $fillable = ['parent_id', 'name', 'slug', 'description', 'icon_path', 'status', 'settings'];
    protected $casts = ['settings' => 'array', 'status' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(function (self $cat) {
            if (empty($cat->slug)) {
                $cat->slug = \Illuminate\Support\Str::slug($cat->name);
            }
        });
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'parent_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseCategory::class, 'parent_id');
    }

    public function courses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Course::class, 'category_id');
    }
}