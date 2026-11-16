<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faculty extends Model
{
    use SoftDeletes;

    protected $fillable = ['institution_id', 'parent_id', 'name', 'slug', 'code', 'dean_id', 'settings', 'status'];
    protected $casts = ['settings' => 'array', 'status' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(function (self $faculty) {
            if (empty($faculty->slug)) {
                $faculty->slug = \Illuminate\Support\Str::slug($faculty->name);
            }
        });
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'parent_id');
    }

    public function departments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Department::class, 'faculty_id');
    }

    public function dean(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'dean_id');
    }
}