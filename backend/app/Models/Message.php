<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sender_id', 'receiver_id', 'course_id', 'institution_id',
        'parent_message_id', 'subject', 'body', 'type',
        'is_read', 'read_at', 'is_starred', 'is_archived',
        'is_flagged', 'attachments', 'metadata',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'is_starred' => 'boolean',
        'is_archived' => 'boolean',
        'is_flagged' => 'boolean',
        'attachments' => 'array',
        'metadata' => 'array',
    ];

    public function sender(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Message::class, 'parent_message_id');
    }

    public function replies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Message::class, 'parent_message_id');
    }
}