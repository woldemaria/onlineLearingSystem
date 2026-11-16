<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CredentialVerification extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'credential_id', 'verified_by', 'verification_method',
        'ip_address', 'user_agent', 'result', 'result_message',
        'requested_at', 'metadata',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function credential(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Credential::class);
    }

    public function verifiedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}