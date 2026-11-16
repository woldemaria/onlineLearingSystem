<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsentRecord extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'guardian_relationship_id', 'student_id', 'guardian_id',
        'consent_type', 'scope', 'granted', 'granted_at',
        'granted_by', 'revoked', 'revoked_at', 'revoked_by',
        'revocation_reason', 'expires_at', 'ip_address',
        'user_agent', 'metadata',
    ];

    protected $casts = [
        'granted' => 'boolean',
        'granted_at' => 'datetime',
        'revoked' => 'boolean',
        'revoked_at' => 'datetime',
        'expires_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function guardianRelationship(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(GuardianRelationship::class);
    }

    public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function guardian(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'guardian_id');
    }

    public function grantedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'granted_by');
    }
}