<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSession extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'token', 'device_id', 'device_name', 'device_type',
        'ip_address', 'user_agent', 'location', 'is_current',
        'last_activity_at', 'expires_at', 'revoked_at', 'metadata',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'last_activity_at' => 'datetime',
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}