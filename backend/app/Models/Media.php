<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'type', 'disk', 'file_path', 'file_name', 'mime_type',
        'size', 'width', 'height', 'duration_seconds', 'bitrate',
        'title', 'description', 'alt_text', 'caption',
        'is_public', 'visibility', 'storage_provider',
        'processing_status', 'processing_metadata', 'cdn_url',
        'checksum', 'expires_at',
    ];

    protected $casts = [
        'size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'duration_seconds' => 'integer',
        'bitrate' => 'integer',
        'is_public' => 'boolean',
        'processing_metadata' => 'array',
        'expires_at' => 'datetime',
    ];
}