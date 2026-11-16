<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Achievement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'institution_id', 'organization_id', 'name', 'slug',
        'description', 'icon_path', 'badge_path', 'type',
        'category', 'criteria', 'points', 'is_public',
        'is_active', 'status', 'settings',
    ];

    protected $casts = [
        'criteria' => 'array',
        'points' => 'integer',
        'is_public' => 'boolean',
        'is_active' => 'boolean',
        'status' => 'boolean',
        'settings' => 'array',
    ];

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function organization(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_achievements')
            ->withTimestamps();
    }
}