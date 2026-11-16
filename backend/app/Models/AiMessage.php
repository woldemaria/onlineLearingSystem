<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AiMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ai_session_id', 'role', 'content', 'sources',
        'tokens_used', 'latency_ms', 'model', 'provider',
        'is_flagged', 'flag_reason', 'metadata',
    ];

    protected $casts = [
        'sources' => 'array',
        'tokens_used' => 'integer',
        'latency_ms' => 'integer',
        'is_flagged' => 'boolean',
        'metadata' => 'array',
    ];

    public function session(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AiSession::class, 'ai_session_id');
    }
}