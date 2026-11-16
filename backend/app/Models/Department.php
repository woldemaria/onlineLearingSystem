<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;

    protected $fillable = ['institution_id', 'faculty_id', 'parent_id', 'name', 'slug', 'code', 'head_id', 'settings', 'status'];
    protected $casts = ['settings' => 'array', 'status' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(function (self $dept) {
            if (empty($dept->slug)) {
                $dept->slug = \Illuminate\Support\Str::slug($dept->name);
            }
        });
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function faculty(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function programs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AcademicProgram::class, 'department_id');
    }

    public function head(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'head_id');
    }
}