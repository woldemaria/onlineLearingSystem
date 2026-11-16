<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstitutionLicense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id', 'product_id', 'institution_id',
        'license_key', 'max_users', 'used_users', 'type',
        'status', 'starts_at', 'ends_at', 'seats_available',
        'seats_used', 'auto_renew', 'renewal_count',
        'billing_contact', 'billing_email', 'billing_address',
        'metadata', 'settings',
    ];

    protected $casts = [
        'max_users' => 'integer',
        'used_users' => 'integer',
        'seats_available' => 'integer',
        'seats_used' => 'integer',
        'auto_renew' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'billing_address' => 'array',
        'metadata' => 'array',
        'settings' => 'array',
    ];

    public function organization(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}