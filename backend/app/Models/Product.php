<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_id', 'organization_id', 'instructor_id',
        'name', 'slug', 'description', 'type', 'status',
        'price', 'currency', 'is_free', 'billing_cycle',
        'billing_interval', 'trial_days', 'billing_amount',
        'billing_currency', 'max_users', 'features',
        'metadata', 'is_published', 'published_at',
        'settings',
    ];

    protected $casts = [
        'features' => 'array',
        'metadata' => 'array',
        'is_free' => 'boolean',
        'is_published' => 'boolean',
        'price' => 'decimal:2',
        'billing_amount' => 'decimal:2',
        'max_users' => 'integer',
        'trial_days' => 'integer',
        'published_at' => 'datetime',
        'settings' => 'array',
    ];

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function organization(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function instructor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }

    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function licenses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InstitutionLicense::class);
    }
}