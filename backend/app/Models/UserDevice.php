<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDevice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'device_id', 'device_name', 'device_type',
        'platform', 'app_version', 'push_token', 'push_provider',
        'is_trusted', 'last_used_at', 'last_ip', 'metadata',
    ];

    protected $casts = [
        'is_trusted' => 'boolean',
        'last_used_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}