<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VirtualClassroom extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_id', 'module_id', 'lesson_id', 'instructor_id',
        'institution_id', 'creator_id', 'title', 'slug',
        'description', 'type', 'status', 'scheduled_at',
        'duration_minutes', 'actual_start_at', 'actual_end_at',
        'provider', 'provider_room_id', 'provider_room_url',
        'recording_url', 'recording_provider_id',
        'recording_status', 'is_recorded', 'is_captions_enabled',
        'is_transcript_enabled', 'is_chat_enabled',
        'is_polls_enabled', 'is_breakout_rooms_enabled',
        'is_whiteboard_enabled', 'is_screen_sharing_enabled',
        'max_participants', 'waiting_room_enabled',
        'mute_on_entry', 'allow_join_before_start',
        'allow_recordings_download', 'access_code',
        'password', 'ip_whitelist', 'domain_whitelist',
        'is_archived', 'archived_at', 'settings',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'actual_start_at' => 'datetime',
        'actual_end_at' => 'datetime',
        'duration_minutes' => 'integer',
        'max_participants' => 'integer',
        'is_recorded' => 'boolean',
        'is_captions_enabled' => 'boolean',
        'is_transcript_enabled' => 'boolean',
        'is_chat_enabled' => 'boolean',
        'is_polls_enabled' => 'boolean',
        'is_breakout_rooms_enabled' => 'boolean',
        'is_whiteboard_enabled' => 'boolean',
        'is_screen_sharing_enabled' => 'boolean',
        'waiting_room_enabled' => 'boolean',
        'mute_on_entry' => 'boolean',
        'allow_join_before_start' => 'boolean',
        'allow_recordings_download' => 'boolean',
        'is_archived' => 'boolean',
        'archived_at' => 'datetime',
        'ip_whitelist' => 'array',
        'domain_whitelist' => 'array',
        'settings' => 'array',
    ];

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function module(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseModule::class);
    }

    public function lesson(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function instructor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function participants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClassroomParticipant::class);
    }
}