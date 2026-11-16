<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Organization extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'parent_id', 'name', 'slug', 'type',
        'country_id', 'region_id', 'status', 'verification_status',
        'verification_documents', 'contact_email', 'contact_phone',
        'address', 'website', 'logo_path', 'branding',
        'settings', 'subscription_plan_id', 'subscription_ends_at',
        'onboarded_at', 'suspended_at',
    ];

    protected $casts = [
        'verification_documents' => 'array',
        'branding' => 'array',
        'settings' => 'array',
        'status' => 'boolean',
        'suspended_at' => 'datetime',
        'onboarded_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $org) {
            if (empty($org->uuid)) {
                $org->uuid = (string) Str::uuid();
            }
            if (empty($org->slug)) {
                $org->slug = Str::slug($org->name);
            }
        });
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Organization::class, 'parent_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Organization::class, 'parent_id');
    }

    public function institutions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Institution::class);
    }

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_user')
            ->withPivot(['status', 'joined_at', 'role'])
            ->withTimestamps();
    }

    public function scopeApproved($query)
    {
        return $query->where('verification_status', 'approved')->where('status', true);
    }
}