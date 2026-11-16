<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Region extends Model
{
    protected $fillable = ['uuid', 'name', 'code', 'country_id', 'parent_id', 'status', 'settings'];
    protected $casts = ['settings' => 'array', 'status' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(function (self $region) {
            if (empty($region->uuid)) {
                $region->uuid = (string) Str::uuid();
            }
        });
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class, 'parent_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Region::class, 'parent_id');
    }
}