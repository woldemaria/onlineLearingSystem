<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'type', 'channel', 'notifiable_type', 'notifiable_id',
        'title', 'message', 'data', 'action_url', 'action_label',
        'priority', 'status', 'read_at', 'read_by', 'sent_at',
        'delivery_status', 'delivery_response', 'scheduled_at',
        'expires_at', 'group_key', 'is_ignored',
    ];

    protected $casts = [
        'data' => 'array',
        'priority' => 'integer',
        'status' => 'boolean',
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_ignored' => 'boolean',
        'delivery_response' => 'array',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }
}