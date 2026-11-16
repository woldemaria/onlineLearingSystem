<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuardianRelationship extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'guardian_id', 'student_id', 'relationship_type',
        'status', 'verified', 'verified_by', 'verified_at',
        'verification_method', 'verification_documents',
        'consent_records', 'access_level', 'permissions',
        'notifications_enabled', 'is_primary', 'revoked',
        'revoked_at', 'revocation_reason', 'expires_at',
        'notes', 'metadata',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'verified_at' => 'datetime',
        'verification_documents' => 'array',
        'consent_records' => 'array',
        'access_level' => 'array',
        'permissions' => 'array',
        'notifications_enabled' => 'boolean',
        'is_primary' => 'boolean',
        'revoked' => 'boolean',
        'revoked_at' => 'datetime',
        'expires_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function guardian(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'guardian_id');
    }

    public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function verifiedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}