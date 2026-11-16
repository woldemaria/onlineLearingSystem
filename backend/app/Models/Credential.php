<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credential extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'issuer_id', 'issuer_type', 'recipient_id',
        'recipient_type', 'course_id', 'enrollment_id', 'type',
        'category', 'name', 'description', 'criteria',
        'image', 'image_alt', 'expiration_date', 'revoked',
        'revoked_at', 'revocation_reason', 'verification_code',
        'verification_url', 'qr_code_path', 'is_public',
        'is_verifiable', 'issued_at', 'expires_at',
        'metadata', 'attachments', 'tags', 'skills',
        'alignment', 'evidence', 'badge_class_id',
        'open_badges_id', 'clr_id', 'accreditation',
        'level', 'grade', 'score', 'score_max',
        'institution_id', 'organization_id',
    ];

    protected $casts = [
        'criteria' => 'array',
        'attachments' => 'array',
        'tags' => 'array',
        'skills' => 'array',
        'alignment' => 'array',
        'evidence' => 'array',
        'metadata' => 'array',
        'revoked' => 'boolean',
        'is_public' => 'boolean',
        'is_verifiable' => 'boolean',
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
        'score' => 'decimal:2',
        'score_max' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $cred) {
            if (empty($cred->uuid)) {
                $cred->uuid = (string) \Illuminate\Support\Str::uuid();
            }
            if (empty($cred->verification_code)) {
                $cred->verification_code = \Illuminate\Support\Str::random(12);
            }
        });
    }

    public function issuer(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo('issuer', 'issuer_type', 'issuer_id');
    }

    public function recipient(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo('recipient', 'recipient_type', 'recipient_id');
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function enrollment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function organization(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function verifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CredentialVerification::class);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true)->where('revoked', false);
    }
}