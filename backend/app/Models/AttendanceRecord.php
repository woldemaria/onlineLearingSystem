<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceRecord extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'attendance_session_id', 'user_id', 'student_id', 'status',
        'arrival_time', 'departure_time', 'minutes_present',
        'minutes_absent', 'excuse', 'excuse_status', 'excuse_approved_by',
        'excuse_approved_at', 'notes', 'method', 'verified_by',
        'verified_at', 'metadata',
    ];

    protected $casts = [
        'arrival_time' => 'datetime',
        'departure_time' => 'datetime',
        'minutes_present' => 'integer',
        'minutes_absent' => 'integer',
        'excuse_status' => 'boolean',
        'excuse_approved_at' => 'datetime',
        'verified_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function session(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AttendanceSession::class, 'attendance_session_id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function excuseApprovedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'excuse_approved_by');
    }

    public function verifiedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}