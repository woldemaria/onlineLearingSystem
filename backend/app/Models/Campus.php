<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campus extends Model
{
    use SoftDeletes;

    protected $fillable = ['institution_id', 'name', 'slug', 'code', 'address', 'city', 'country_id', 'settings', 'status'];
    protected $casts = ['settings' => 'array', 'status' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(function (self $campus) {
            if (empty($campus->slug)) {
                $campus->slug = \Illuminate\Support\Str::slug($campus->name);
            }
        });
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}