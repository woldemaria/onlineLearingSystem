<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassroomParticipant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'virtual_classroom_id', 'user_id', 'role', 'status',
        'joined_at', 'left_at', 'duration_seconds', 'ip_address',
        'user_agent', 'is_recorded', 'is_muted', 'is_camera_on',
        'is_screen_sharing', 'hand_raised', 'chat_messages_count',
        'polls_responded', 'breakout_room_id', 'notes',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
        'duration_seconds' => 'integer',
        'is_recorded' => 'boolean',
        'is_muted' => 'boolean',
        'is_camera_on' => 'boolean',
        'is_screen_sharing' => 'boolean',
        'hand_raised' => 'boolean',
        'chat_messages_count' => 'integer',
        'polls_responded' => 'integer',
    ];

    public function classroom(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(VirtualClassroom::class, 'virtual_classroom_id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}