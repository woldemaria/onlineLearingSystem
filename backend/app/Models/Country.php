<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Country extends Model
{
    protected $fillable = ['code', 'name', 'iso2', 'phone_code', 'region_id', 'status', 'data_residency_required', 'settings'];
    protected $casts = ['settings' => 'array', 'data_residency_required' => 'boolean', 'status' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(function (self $country) {
            if (empty($country->code)) {
                $country->code = Str::upper($country->iso2 ?? Str::substr($country->name, 0, 2));
            }
        });
    }
}